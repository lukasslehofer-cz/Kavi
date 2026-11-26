<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\BacksUpPostContent;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * SEO fáze 1, bod 4: odkazy v textu článků, jejichž text mluví o předplatném,
 * nevedou na konfigurátor (/predplatne, /subscription), ale na homepage.
 * Mění jen hodnotu href, zbytek HTML zůstává beze změny. Opakované spuštění nic nemění.
 */
class RepointBlogSubscriptionLinks extends Command
{
    use BacksUpPostContent;

    protected $signature = 'blog:repoint-subscription-links
                            {--force : Skutečně zapsat; bez tohoto přepínače jde jen o výpis}';

    protected $description = 'Odkazy „předplatné“ v obsahu článků přesměruje z konfigurátoru na homepage';

    /** Cesty konfigurátoru, ze kterých se odkaz přesměruje */
    private const SUBSCRIPTION_PATHS = ['/predplatne', '/subscription'];

    /** Vlastní domény: absolutní odkaz na ně je interní */
    private const OWN_HOSTS = ['kavi.cz', 'www.kavi.cz', 'kavibox.com', 'www.kavibox.com'];

    /** Text odkazu musí obsahovat aspoň jedno z toho (bez ohledu na velikost písmen) */
    private const TEXT_NEEDLES = ['předplatn', 'subscription'];

    /** Relativně, takže na kavibox.com vede na anglickou homepage */
    private const NEW_HREF = '/';

    private const COLUMNS = ['content_cs' => 'cs', 'content_en' => 'en'];

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        if (! $force) {
            $this->warn('DRY-RUN – nic se nezapisuje. Pro zápis přidej --force.');
        }

        $rows = [];
        $updated = [];
        $original = [];

        foreach (Post::orderBy('id')->get() as $post) {
            foreach (self::COLUMNS as $column => $locale) {
                $html = (string) $post->{$column};

                if ($html === '') {
                    continue;
                }

                $new = preg_replace_callback('/<a\s[^>]*>.*?<\/a>/isu', function (array $m) use (&$rows, $post, $locale) {
                    $tag = $m[0];

                    if (! preg_match('/\bhref\s*=\s*(["\'])(.*?)\1/i', $tag, $href) || ! $this->isSubscriptionUrl($href[2])) {
                        return $tag;
                    }

                    $text = mb_strtolower(html_entity_decode(strip_tags($tag), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

                    if (! Str::contains($text, self::TEXT_NEEDLES)) {
                        return $tag;
                    }

                    $rows[] = [$post->id, $post->slug_cs, $locale, $tag, self::NEW_HREF];

                    return preg_replace('/\bhref\s*=\s*(["\'])(.*?)\1/i', 'href=${1}' . self::NEW_HREF . '${1}', $tag, 1);
                }, $html);

                if ($new !== $html) {
                    $updated[$post->id][$column] = $new;
                    $original[$post->id][$column] = $html;
                }
            }
        }

        if (empty($rows)) {
            $this->info('Žádný odkaz „předplatné“ na konfigurátor v článcích není.');

            return self::SUCCESS;
        }

        $this->table(['ID', 'Článek', 'Jazyk', 'Původní odkaz', 'Nový href'], $rows);
        $this->line(count($rows) . ' odkazů v ' . count($updated) . ' článcích.');

        if (! $force) {
            $this->warn('Nic se neuložilo. Pro zápis spusť znovu s --force.');

            return self::SUCCESS;
        }

        $backupPath = $this->backupPostContent('repoint-subscription-links', $original);

        foreach ($updated as $postId => $columns) {
            Post::findOrFail($postId)->fill($columns)->save();
        }

        $this->info('Uloženo. Záloha původního obsahu: ' . $backupPath);

        return self::SUCCESS;
    }

    private function isSubscriptionUrl(string $href): bool
    {
        $parts = parse_url(html_entity_decode($href, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if ($parts === false) {
            return false;
        }

        if (isset($parts['host']) && ! in_array(strtolower($parts['host']), self::OWN_HOSTS, true)) {
            return false;
        }

        $path = rtrim($parts['path'] ?? '', '/');

        return in_array($path, self::SUBSCRIPTION_PATHS, true);
    }
}
