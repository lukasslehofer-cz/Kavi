<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Kontrola hreflang mezi kavi.cz a kavibox.com: projde sitemapy obou webů a u každé
 * stránky s hreflang ověří, že
 * - má právě cs + en (žádný x-default) a odkaz na sebe se shoduje s canonical i s URL stránky,
 * - každý cíl vrací 200 bez přesměrování, jeho canonical je stejný jako href
 *   a cíl odkazuje zpět (reciprocita).
 *
 * Jen čte (GET), sekvenčně s pauzou. Výstupem je seznam chyb (má být prázdný).
 * --via pošle požadavky na jiný server (např. lokální) s hlavičkou Host kontrolovaného webu.
 */
class SeoCheckHreflang extends Command
{
    protected $signature = 'seo:check-hreflang
                            {--cs=https://kavi.cz : Česká verze (bez lomítka na konci)}
                            {--en=https://kavibox.com : Anglická verze (bez lomítka na konci)}
                            {--via= : Posílat požadavky na tento server (např. http://127.0.0.1:8124) s hlavičkou Host}
                            {--delay=150 : Pauza mezi požadavky v ms}';

    protected $description = 'Ověří vzájemné hreflang značky kavi.cz ↔ kavibox.com podle sitemap obou webů';

    /** @var array<string, array{status:int, location:?string, body:string}> */
    private array $cache = [];

    /** @var array<int, array{0:string, 1:string}> */
    private array $errors = [];

    public function handle(): int
    {
        $sites = [
            'cs' => rtrim((string) $this->option('cs'), '/'),
            'en' => rtrim((string) $this->option('en'), '/'),
        ];

        $pages = [];
        foreach ($sites as $lang => $base) {
            $urls = $this->sitemapUrls($base . '/sitemap.xml');
            if (empty($urls)) {
                $this->error("Sitemap je prázdná nebo nedostupná: {$base}/sitemap.xml");

                return self::FAILURE;
            }
            foreach ($urls as $url) {
                $pages[$url] = $lang;
            }
        }

        $this->info(count($pages) . ' stránek ze sitemap obou webů, procházím…');
        $bar = $this->output->createProgressBar(count($pages));

        $withHreflang = 0;
        $without = [];

        foreach ($pages as $url => $lang) {
            $bar->advance();

            if (! str_starts_with($url, $sites[$lang] . '/') && $url !== $sites[$lang]) {
                $this->problem($url, 'URL v sitemapě nepatří webu ' . $sites[$lang]);
                continue;
            }

            $page = $this->fetch($url);
            if ($page['status'] !== 200) {
                $this->problem($url, 'stránka ze sitemap vrací ' . $page['status'] . ($page['location'] ? ' → ' . $page['location'] : ''));
                continue;
            }

            $alternates = $this->alternates($page['body']);
            if (empty($alternates)) {
                $without[$this->section($url)] = ($without[$this->section($url)] ?? 0) + 1;
                continue;
            }

            $withHreflang++;
            $this->checkPage($url, $lang, $page['body'], $alternates);
        }

        $bar->finish();
        $this->newLine(2);

        $this->line("Stránek s hreflang: {$withHreflang}");
        if ($without) {
            ksort($without);
            $this->line('Stránky bez hreflang (pro kontrolu, nejsou chybou):');
            $this->table(['Sekce', 'Počet'], collect($without)->map(fn ($n, $s) => [$s, $n])->values()->all());
        }

        if (empty($this->errors)) {
            $this->info('Žádné chyby.');

            return self::SUCCESS;
        }

        $this->error(count($this->errors) . ' chyb:');
        $this->table(['Stránka', 'Problém'], $this->errors);

        return self::FAILURE;
    }

    /**
     * @param  array<string, array<int, string>>  $alternates
     */
    private function checkPage(string $url, string $lang, string $body, array $alternates): void
    {
        $canonical = $this->canonical($body);

        if ($canonical !== $url) {
            $this->problem($url, 'canonical (' . ($canonical ?? 'chybí') . ') se neshoduje s URL stránky');
        }

        if (isset($alternates['x-default'])) {
            $this->problem($url, 'obsahuje x-default');
        }

        foreach ($alternates as $hreflang => $hrefs) {
            if (count($hrefs) > 1) {
                $this->problem($url, "hreflang=\"{$hreflang}\" je uveden víckrát");
            }
        }

        $langs = array_keys($alternates);
        sort($langs);
        if ($langs !== ['cs', 'en']) {
            $this->problem($url, 'hreflang jazyky jsou [' . implode(', ', $langs) . '], očekává se [cs, en]');
        }

        if (($alternates[$lang][0] ?? null) !== $url) {
            $this->problem($url, "odkaz na sebe (hreflang=\"{$lang}\") je " . ($alternates[$lang][0] ?? 'chybí'));
        }

        foreach ($alternates as $hreflang => $hrefs) {
            if ($hreflang === $lang || $hreflang === 'x-default') {
                continue;
            }

            $target = $hrefs[0];
            $response = $this->fetch($target);

            if ($response['status'] !== 200) {
                $this->problem($url, "cíl {$target} vrací " . $response['status'] . ($response['location'] ? ' → ' . $response['location'] : ''));
                continue;
            }

            $targetCanonical = $this->canonical($response['body']);
            if ($targetCanonical !== $target) {
                $this->problem($url, "cíl {$target} má canonical " . ($targetCanonical ?? '(chybí)'));
            }

            $back = $this->alternates($response['body'])[$lang] ?? [];
            if (! in_array($url, $back, true)) {
                $this->problem($url, "cíl {$target} neodkazuje zpět (hreflang=\"{$lang}\" je " . ($back[0] ?? 'chybí') . ')');
            }
        }
    }

    private function problem(string $url, string $problem): void
    {
        $this->errors[] = [$url, $problem];
    }

    /**
     * Sekce pro přehled stránek bez hreflang: první část cesty, homepage = "/".
     */
    private function section(string $url): string
    {
        $host = (string) parse_url($url, PHP_URL_HOST);
        $segment = explode('/', trim((string) parse_url($url, PHP_URL_PATH), '/'))[0];
        $path = (string) parse_url($url, PHP_URL_PATH);

        // Statické stránky vypíšeme celé, u detailů jen sekci
        $isDetail = substr_count(trim($path, '/'), '/') >= 1;

        return $host . ' /' . ($isDetail ? $segment . '/…' : trim($path, '/'));
    }

    /**
     * <link rel="alternate" hreflang="…" href="…"> ze stránky (pořadí atributů libovolné).
     *
     * @return array<string, array<int, string>>
     */
    private function alternates(string $html): array
    {
        $result = [];

        foreach ($this->linkTags($html) as $attrs) {
            if (strtolower($attrs['rel'] ?? '') !== 'alternate' || ! isset($attrs['hreflang'], $attrs['href'])) {
                continue;
            }
            $result[strtolower($attrs['hreflang'])][] = $attrs['href'];
        }

        return $result;
    }

    private function canonical(string $html): ?string
    {
        foreach ($this->linkTags($html) as $attrs) {
            if (strtolower($attrs['rel'] ?? '') === 'canonical' && isset($attrs['href'])) {
                return $attrs['href'];
            }
        }

        return null;
    }

    /**
     * Atributy všech <link> v <head>.
     *
     * @return array<int, array<string, string>>
     */
    private function linkTags(string $html): array
    {
        $head = preg_match('~<head\b.*?</head>~is', $html, $h) ? $h[0] : $html;
        preg_match_all('~<link\b([^>]*)>~i', $head, $m);

        $tags = [];
        foreach ($m[1] as $attrString) {
            preg_match_all('~([a-zA-Z:-]+)\s*=\s*(["\'])(.*?)\2~s', $attrString, $a, PREG_SET_ORDER);
            $attrs = [];
            foreach ($a as $pair) {
                $attrs[strtolower($pair[1])] = html_entity_decode($pair[3], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
            $tags[] = $attrs;
        }

        return $tags;
    }

    /**
     * URL stránek ze sitemap (rozbalí i sitemap index).
     *
     * @return array<int, string>
     */
    private function sitemapUrls(string $url): array
    {
        $response = $this->fetch($url);

        if ($response['status'] !== 200 || ! preg_match_all('~<loc>\s*(.*?)\s*</loc>~s', $response['body'], $m)) {
            return [];
        }

        $locs = array_map(fn ($loc) => html_entity_decode($loc, ENT_QUOTES | ENT_XML1, 'UTF-8'), $m[1]);

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
     * GET bez následování přesměrování, výsledek se pamatuje.
     *
     * @return array{status:int, location:?string, body:string}
     */
    private function fetch(string $url): array
    {
        if (isset($this->cache[$url])) {
            return $this->cache[$url];
        }

        usleep(max(0, (int) $this->option('delay')) * 1000);

        $headers = ['User-Agent' => 'KAVI-SEO-Hreflang/1.0'];
        $requestUrl = $url;

        if ($via = rtrim((string) $this->option('via'), '/')) {
            $parts = parse_url($url);
            $headers['Host'] = $parts['host'] ?? '';
            $requestUrl = $via . ($parts['path'] ?? '/') . (isset($parts['query']) ? '?' . $parts['query'] : '');
        }

        try {
            $response = Http::withoutRedirecting()
                ->withHeaders($headers)
                ->timeout(30)
                ->get($requestUrl);

            $result = [
                'status' => $response->status(),
                'location' => $response->header('Location') ?: null,
                'body' => $response->status() === 200 ? $response->body() : '',
            ];
        } catch (\Throwable $e) {
            $result = ['status' => 0, 'location' => $e->getMessage(), 'body' => ''];
        }

        return $this->cache[$url] = $result;
    }
}
