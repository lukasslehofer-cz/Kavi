<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Doplní do dříve publikovaných článků odkazy na druhou várku článků
 * (BlogSeoArticlesBatch2Seeder), v EN verzích opraví odkazy na české cesty
 * a opraví terminologii (zrna místo bobů, promytá místo praná) a údaje
 * o čerstvosti (sladěné s článkem o odpočinku kávy po pražení).
 *
 * Nepřepisuje celý obsah, jen nahradí přesné úryvky z blog/existing-links.php.
 * Úryvek se nahradí, jen když je v obsahu právě jednou; když už je nahrazený,
 * přeskočí se. Nenalezené úryvky (článek mezitím někdo upravil v adminu) jen vypíše.
 *
 * Spouštět ručně až po BlogSeoArticlesBatch2Seeder:
 *   php artisan db:seed --class=BlogExistingArticlesLinksSeeder --force
 */
class BlogExistingArticlesLinksSeeder extends Seeder
{
    public function run(): void
    {
        $entries = require __DIR__ . '/blog/existing-links.php';
        $posts = Post::whereIn('slug_cs', array_unique(array_column($entries, 'slug_cs')))
            ->get()
            ->keyBy('slug_cs');

        $applied = $skipped = $missing = 0;

        DB::transaction(function () use ($entries, $posts, &$applied, &$skipped, &$missing) {
            foreach ($entries as $entry) {
                $post = $posts[$entry['slug_cs']] ?? null;
                $column = 'content_' . $entry['locale'];
                $label = "{$entry['slug_cs']} [{$entry['locale']}]";

                if (! $post) {
                    $this->command?->warn("{$label}: článek neexistuje");
                    $missing++;
                    continue;
                }

                $content = (string) $post->{$column};

                // Nahrazení často obsahuje původní úryvek (obalený odkazem), proto
                // nejdřív kontrola, jestli už je hotové – jinak by se odkaz zanořil.
                if (str_contains($content, $entry['replace'])) {
                    $skipped++;
                    continue;
                }

                $count = substr_count($content, $entry['search']);

                if ($count !== 1) {
                    $this->command?->warn("{$label}: úryvek nalezen {$count}× – " . mb_substr(strip_tags($entry['search']), 0, 70));
                    $missing++;
                    continue;
                }

                $post->{$column} = str_replace($entry['search'], $entry['replace'], $content);
                $post->save();
                $applied++;
            }
        });

        $this->command?->info("Nahrazeno: {$applied}, už hotové: {$skipped}, nenalezeno: {$missing}");
    }
}
