<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Kontrola interních odkazů (SEO fáze 1b): projde všechny stránky ze sitemap a vypíše
 * - kolik stránek odkazuje na každý blogový článek,
 * - interní odkazy, které nevrací 200 (404, přesměrování…),
 * - odkazy na /blog/{id} a odkazy článku sám na sebe.
 *
 * Jen čte (GET), sekvenčně s pauzou. Snapshot se ukládá do storage/app/seo-audit,
 * --compare porovná počty odkazů na články s dřívějším snapshotem.
 */
class SeoAuditLinks extends Command
{
    protected $signature = 'seo:audit-links
                            {base=https://kavi.cz : Web, který se kontroluje (bez lomítka na konci)}
                            {--save= : Název snapshotu (výchozí datum a host)}
                            {--compare= : Cesta k dřívějšímu snapshotu (JSON) pro porovnání}
                            {--delay=150 : Pauza mezi požadavky v ms}';

    protected $description = 'Projde stránky ze sitemap a zkontroluje interní odkazy a odkazy na blogové články';

    private string $base;

    private string $host;

    /** @var array<string, array{status:int, location:?string}> */
    private array $statusCache = [];

    public function handle(): int
    {
        $this->base = rtrim($this->argument('base'), '/');
        $this->host = $this->normalizeHost((string) parse_url($this->base, PHP_URL_HOST));

        $pages = $this->sitemapUrls($this->base . '/sitemap.xml');

        if (empty($pages)) {
            $this->error('Sitemap je prázdná nebo nedostupná: ' . $this->base . '/sitemap.xml');

            return self::FAILURE;
        }

        $this->info(count($pages) . ' stránek ze sitemap, procházím…');

        $inbound = [];       // cesta článku => [zdrojové stránky]
        $linkSources = [];   // cesta odkazu => [zdrojové stránky]
        $numericLinks = [];
        $selfLinks = [];
        $sitemapProblems = [];

        $bar = $this->output->createProgressBar(count($pages));

        foreach ($pages as $pageUrl) {
            $bar->advance();
            $response = $this->fetch($pageUrl);
            $pagePath = $this->pathOf($pageUrl);

            if ($response['status'] !== 200) {
                $sitemapProblems[] = [$pagePath, $response['status'], $response['location'] ?? ''];
                continue;
            }

            foreach ($this->internalLinks($response['body'], $pageUrl) as $target) {
                $linkSources[$target][$pagePath] = true;

                if (preg_match('~^/blog/(\d+)$~', $target)) {
                    $numericLinks[] = [$target, $pagePath];
                }

                if ($this->isArticlePath($target) && $target !== $pagePath) {
                    $inbound[$target][$pagePath] = true;
                }

                if ($this->isArticlePath($pagePath) && $target === $pagePath) {
                    $selfLinks[$pagePath] = true;
                }
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Stav všech odkazovaných interních URL
        $this->info('Kontroluji ' . count($linkSources) . ' unikátních interních odkazů…');
        $broken = [];
        foreach ($linkSources as $target => $sources) {
            $check = $this->fetch($this->base . $target, false);
            if ($check['status'] !== 200) {
                $broken[] = [$target, $check['status'], $check['location'] ?? '', count($sources), array_key_first($sources)];
            }
        }

        $inboundCounts = array_map('count', $inbound);
        arsort($inboundCounts);

        $this->line('<comment>Odkazy na blogové články (počet odkazujících stránek)</comment>');
        $this->table(['Článek', 'Stránek'], collect($inboundCounts)->map(fn ($n, $path) => [$path, $n])->values()->all());

        $this->line('<comment>Interní odkazy s jiným kódem než 200</comment>');
        $broken ? $this->table(['Odkaz', 'Kód', 'Přesměrování na', 'Stránek', 'Např. z'], $broken) : $this->info('Žádné.');

        $this->line('<comment>Odkazy na /blog/{id}</comment>');
        $numericLinks ? $this->table(['Odkaz', 'Ze stránky'], $numericLinks) : $this->info('Žádné.');

        $this->line('<comment>Články, které odkazují samy na sebe</comment>');
        $selfLinks ? $this->table(['Článek'], array_map(fn ($p) => [$p], array_keys($selfLinks))) : $this->info('Žádné.');

        if ($sitemapProblems) {
            $this->line('<comment>Stránky ze sitemap, které nevrací 200</comment>');
            $this->table(['Stránka', 'Kód', 'Přesměrování na'], $sitemapProblems);
        }

        $snapshot = [
            'base' => $this->base,
            'generated_at' => now()->toIso8601String(),
            'pages' => count($pages),
            'inbound' => $inboundCounts,
            'broken' => $broken,
            'numeric_links' => $numericLinks,
            'self_links' => array_keys($selfLinks),
            'sitemap_problems' => $sitemapProblems,
        ];

        $name = $this->option('save') ?: now()->format('Y-m-d_His') . '-' . Str::slug($this->host);
        $path = 'seo-audit/' . $name . '.json';
        Storage::disk('local')->put($path, json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->info('Snapshot: ' . storage_path('app/' . $path));

        if ($compare = $this->option('compare')) {
            $this->compareWith($compare, $inboundCounts);
        }

        return self::SUCCESS;
    }

    private function compareWith(string $file, array $after): void
    {
        $path = is_file($file) ? $file : storage_path('app/seo-audit/' . basename($file, '.json') . '.json');

        if (! is_file($path)) {
            $this->warn('Snapshot pro porovnání nenalezen: ' . $file);

            return;
        }

        $before = json_decode((string) file_get_contents($path), true)['inbound'] ?? [];
        $rows = [];
        foreach (array_unique(array_merge(array_keys($before), array_keys($after))) as $article) {
            $b = $before[$article] ?? 0;
            $a = $after[$article] ?? 0;
            $rows[] = [$article, $b, $a, ($a - $b >= 0 ? '+' : '') . ($a - $b)];
        }
        usort($rows, fn ($x, $y) => $y[2] <=> $x[2]);

        $this->line('<comment>Porovnání s ' . basename($path) . '</comment>');
        $this->table(['Článek', 'Před', 'Po', 'Rozdíl'], $rows);
    }

    /**
     * URL stránek ze sitemap (rozbalí i sitemap index).
     */
    private function sitemapUrls(string $url): array
    {
        $response = $this->fetch($url);

        if ($response['status'] !== 200 || ! preg_match_all('~<loc>\s*(.*?)\s*</loc>~s', $response['body'], $m)) {
            return [];
        }

        // Sitemap index lokálně uvádí produkční doménu: vše přepíšeme na kontrolovaný web,
        // ať se nikdy nechodí jinam než na base
        $locs = array_map(fn ($loc) => $this->onBase(html_entity_decode($loc, ENT_QUOTES | ENT_XML1, 'UTF-8')), $m[1]);

        if (str_contains($response['body'], '<sitemapindex')) {
            $urls = [];
            foreach ($locs as $sitemap) {
                $urls = array_merge($urls, $this->sitemapUrls($sitemap));
            }

            return array_values(array_unique($urls));
        }

        return $locs;
    }

    /**
     * Interní odkazy ze stránky jako cesty (bez fragmentu).
     */
    private function internalLinks(string $html, string $pageUrl): array
    {
        preg_match_all('~<a\s[^>]*?href\s*=\s*(["\'])(.*?)\1~is', $html, $m);

        $links = [];
        foreach ($m[2] as $href) {
            $href = trim(html_entity_decode($href, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            if ($href === '' || $href[0] === '#' || preg_match('~^(mailto|tel|javascript|data):~i', $href)) {
                continue;
            }

            if (str_starts_with($href, '//')) {
                $href = 'https:' . $href;
            } elseif (! preg_match('~^https?://~i', $href)) {
                $href = str_starts_with($href, '/')
                    ? $this->base . $href
                    : rtrim(dirname($pageUrl . 'x'), '/') . '/' . $href;
            }

            if ($this->normalizeHost((string) parse_url($href, PHP_URL_HOST)) !== $this->host) {
                continue;
            }

            $links[$this->pathOf($href)] = true;
        }

        return array_keys($links);
    }

    /**
     * Cesta + query, bez lomítka na konci (kromě kořene) a bez fragmentu.
     */
    private function pathOf(string $url): string
    {
        $parts = parse_url($url);
        $path = rtrim($parts['path'] ?? '', '/');
        $path = $path === '' ? '/' : $path;

        return $path . (isset($parts['query']) ? '?' . $parts['query'] : '');
    }

    private function onBase(string $url): string
    {
        return $this->base . $this->pathOf($url);
    }

    private function isArticlePath(string $path): bool
    {
        return (bool) preg_match('~^/blog/[^/?]+$~', $path);
    }

    private function normalizeHost(string $host): string
    {
        return preg_replace('~^www\.~', '', strtolower($host));
    }

    /**
     * GET bez následování přesměrování, výsledek se pamatuje.
     *
     * @return array{status:int, location:?string, body:string}
     */
    private function fetch(string $url, bool $withBody = true): array
    {
        $key = $this->pathOf($url) . '|' . $this->normalizeHost((string) parse_url($url, PHP_URL_HOST));

        if (isset($this->statusCache[$key]) && (! $withBody || isset($this->statusCache[$key]['body']))) {
            return $this->statusCache[$key] + ['body' => ''];
        }

        usleep(max(0, (int) $this->option('delay')) * 1000);

        try {
            $response = Http::withoutRedirecting()
                ->withHeaders(['User-Agent' => 'KAVI-SEO-Audit/1.0'])
                ->timeout(30)
                ->get($url);

            $result = [
                'status' => $response->status(),
                'location' => $response->header('Location') ?: null,
                'body' => $response->status() === 200 ? $response->body() : '',
            ];
        } catch (\Throwable $e) {
            $result = ['status' => 0, 'location' => $e->getMessage(), 'body' => ''];
        }

        $this->statusCache[$key] = $result;

        return $result;
    }
}
