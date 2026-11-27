<?php

namespace App\Console\Commands;

use App\Models\AnnouncementBanner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Roastery;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

/**
 * Úvodní slovenské překlady obsahu (kavibox.sk) z database/seeders/sk/*.json.
 *
 * Formát souboru: [{"id": 12, "fields": {"name_sk": {"src": "<sha1 českého zdroje>", "text": "…"},
 * "attributes.origin_sk": {…}}}]. Pole se vyplní jen když je prázdné (nepřepíše ruční
 * úpravy z adminu) a český zdroj má pořád stejný sha1 (jinak by šel na web překlad
 * starého textu) – takové případy se vypíšou k ručnímu doplnění.
 *
 * Interní odkazy v obsahu článků se přepíšou z českých cest na slovenské
 * (/darky → /darceky, /blog/{slug_cs} → /blog/{slug_sk}).
 */
class ImportSlovakContent extends Command
{
    protected $signature = 'content:import-sk
                            {--dry-run : Jen vypíše, co by se změnilo}
                            {--path= : Složka s JSON soubory (výchozí database/seeders/sk)}';

    protected $description = 'Doplní slovenské překlady produktů, pražíren, článků, štítků a hlášek ze souborů JSON';

    /** tabulka => [model, české zdrojové sloupce podle cílového sloupce] */
    private const TABLES = [
        'products' => [Product::class, ['name_sk' => 'name', 'description_sk' => 'description', 'short_description_sk' => 'short_description']],
        'roasteries' => [Roastery::class, ['name_sk' => 'name', 'short_description_sk' => 'short_description', 'full_description_sk' => 'full_description', 'country_sk' => 'country', 'city_sk' => 'city']],
        'tags' => [Tag::class, ['name_sk' => 'name_cs', 'slug_sk' => 'slug_cs']],
        'posts' => [Post::class, ['title_sk' => 'title_cs', 'slug_sk' => 'slug_cs', 'perex_sk' => 'perex_cs', 'content_sk' => 'content_cs']],
        'announcement_banners' => [AnnouncementBanner::class, ['message_sk' => 'message_cs', 'title_sk' => 'title_cs', 'link_label_sk' => 'link_label_cs']],
    ];

    /** @var array<string, array<string, int>> */
    private array $stats = [];

    /** @var list<string> */
    private array $manual = [];

    public function handle(): int
    {
        $dir = rtrim($this->option('path') ?: database_path('seeders/sk'), '/');
        $dry = (bool) $this->option('dry-run');

        if (! is_dir($dir)) {
            $this->error("Složka {$dir} neexistuje.");

            return self::FAILURE;
        }

        // Štítky a články první – přepis odkazů v obsahu potřebuje slovenské slugy
        foreach (self::TABLES as $table => [$modelClass, $sources]) {
            $file = "{$dir}/{$table}.json";
            if (! is_file($file)) {
                $this->line("{$table}: soubor chybí, přeskočeno");

                continue;
            }

            $entries = json_decode((string) file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
            $this->stats[$table] = ['filled' => 0, 'already' => 0, 'stale' => 0, 'missing' => 0];

            foreach ($entries as $entry) {
                $model = $modelClass::find($entry['id'] ?? null);
                if (! $model) {
                    $this->stats[$table]['missing']++;

                    continue;
                }

                $this->importEntry($table, $model, $sources, (array) ($entry['fields'] ?? []), $dry);
            }
        }

        $this->rewritePostLinks($dry);

        $this->table(['tabulka', 'vyplněno', 'už vyplněno', 'změněný zdroj', 'chybí řádek'], collect($this->stats)
            ->map(fn ($s, $t) => [$t, $s['filled'], $s['already'], $s['stale'], $s['missing']])->values()->all());

        if ($this->manual !== []) {
            $this->warn('Český zdroj se od překladu změnil – doplnit ručně v adminu (tlačítko Přeložit):');
            foreach ($this->manual as $line) {
                $this->line("  {$line}");
            }
        }

        if ($dry) {
            $this->info('Dry run – nic se neuložilo.');
        }

        return self::SUCCESS;
    }

    /**
     * @param  array<string, string>  $sources
     * @param  array<string, array{src: string, text: mixed}>  $fields
     */
    private function importEntry(string $table, Model $model, array $sources, array $fields, bool $dry): void
    {
        $changes = [];
        $attributes = null;

        foreach ($fields as $target => $translation) {
            $attrKey = null;
            if (str_starts_with($target, 'attributes.')) {
                // attributes.origin_sk → klíč origin_sk v JSON, zdroj origin
                $attrKey = substr($target, strlen('attributes.'));
                $attributes ??= (array) ($model->getAttribute('attributes') ?? []);
                $source = $attributes[substr($attrKey, 0, -3)] ?? null;
                $current = $attributes[$attrKey] ?? null;
            } elseif (isset($sources[$target])) {
                $source = $model->getAttribute($sources[$target]);
                $current = $model->getAttribute($target);
            } else {
                $this->manual[] = "{$table} #{$model->getKey()}: neznámé pole {$target}";

                continue;
            }

            if (! empty($current)) {
                $this->stats[$table]['already']++;

                continue;
            }

            if (sha1(self::sourceString($source)) !== ($translation['src'] ?? null)) {
                $this->stats[$table]['stale']++;
                $this->manual[] = "{$table} #{$model->getKey()} {$target}";

                continue;
            }

            if ($attrKey !== null) {
                $attributes[$attrKey] = $translation['text'];
                $changes['attributes'] = $attributes;
            } else {
                $changes[$target] = $translation['text'];
            }
            $this->stats[$table]['filled']++;
        }

        if ($changes !== [] && ! $dry) {
            // Bez observerů a eventů: jde jen o doplnění překladu
            $model->forceFill($changes)->saveQuietly();
        }
    }

    private static function sourceString(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        return is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Relativní odkazy v content_sk: české cesty na slovenské, slugy článků na slovenské.
     */
    private function rewritePostLinks(bool $dry): void
    {
        $cs = require lang_path('cs/routes.php');
        $sk = require lang_path('sk/routes.php');

        // Jen statické části cest (bez parametrů), od nejdelší, aby /darky/certifikat šlo před /darky
        $map = [];
        foreach ($sk as $key => $skPath) {
            $csPath = $cs[$key] ?? null;
            if (! $csPath || $csPath === $skPath) {
                continue;
            }
            $csStatic = explode('/{', $csPath)[0];
            $skStatic = explode('/{', $skPath)[0];
            if ($csStatic !== $skStatic) {
                $map['/' . $csStatic] = '/' . $skStatic;
            }
        }
        uksort($map, fn ($a, $b) => strlen($b) <=> strlen($a));

        $postSlugs = Post::whereNotNull('slug_sk')->pluck('slug_sk', 'slug_cs')->all();
        $rewritten = 0;

        Post::whereNotNull('content_sk')->each(function (Post $post) use ($map, $postSlugs, $dry, &$rewritten) {
            $content = preg_replace_callback('/href="(\/[^"#?]*)([^"]*)"/', function ($m) use ($map, $postSlugs) {
                $path = $m[1];
                if (preg_match('#^/blog/([^/]+)$#', $path, $b) && isset($postSlugs[$b[1]])) {
                    return 'href="/blog/' . $postSlugs[$b[1]] . $m[2] . '"';
                }
                foreach ($map as $from => $to) {
                    if ($path === $from || str_starts_with($path, $from . '/')) {
                        return 'href="' . $to . substr($path, strlen($from)) . $m[2] . '"';
                    }
                }

                return $m[0];
            }, $post->content_sk);

            if ($content !== $post->content_sk) {
                $rewritten++;
                if (! $dry) {
                    $post->forceFill(['content_sk' => $content])->saveQuietly();
                }
            }
        });

        $this->line("Články s přepsanými odkazy na slovenské cesty: {$rewritten}");
    }
}
