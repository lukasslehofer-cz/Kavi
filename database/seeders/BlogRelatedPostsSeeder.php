<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

/**
 * Výchozí ruční přiřazení souvisejících článků (SEO fáze 1b, bod 9).
 * Spouštět ručně: php artisan db:seed --class=BlogRelatedPostsSeeder --force
 * Opakované spuštění přepíše přiřazení u uvedených článků na tuto tabulku.
 */
class BlogRelatedPostsSeeder extends Seeder
{
    /** slug_cs článku => související slug_cs v tomto pořadí */
    private const RELATED = [
        'vyberova-kava-vs-klasicka-kava' => ['arabica-vs-robusta-rozdil', 'metody-zpracovani-kavy', 'chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit'],
        'bezkofeinova-kava-myty-a-fakta-pro-rok-2026' => ['kolik-kofeinu-ma-kava', 'kava-na-espresso-nebo-na-filtr', 'jak-pripravit-kavu-doma'],
        'chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit' => ['metody-zpracovani-kavy', 'svetle-vs-tmave-prazeni-kavy', 'vyberova-kava-vs-klasicka-kava'],
        'arabica-vs-robusta-rozdil' => ['vyberova-kava-vs-klasicka-kava', 'kolik-kofeinu-ma-kava', 'svetle-vs-tmave-prazeni-kavy'],
        'zrnkova-vs-mleta-kava' => ['jak-skladovat-kavu-cerstvost', 'jak-pripravit-kavu-doma', 'jak-pripravit-espresso'],
        'jak-pripravit-kavu-doma' => ['zrnkova-vs-mleta-kava', 'jak-pripravit-espresso', 'kava-na-espresso-nebo-na-filtr'],
        'svetle-vs-tmave-prazeni-kavy' => ['kava-na-espresso-nebo-na-filtr', 'chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', 'vyberova-kava-vs-klasicka-kava'],
        'jak-skladovat-kavu-cerstvost' => ['zrnkova-vs-mleta-kava', 'jak-pripravit-kavu-doma', 'vyberova-kava-vs-klasicka-kava'],
        'metody-zpracovani-kavy' => ['chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', 'svetle-vs-tmave-prazeni-kavy', 'vyberova-kava-vs-klasicka-kava'],
        'jak-pripravit-espresso' => ['kava-na-espresso-nebo-na-filtr', 'zrnkova-vs-mleta-kava', 'chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit'],
        'kava-na-espresso-nebo-na-filtr' => ['svetle-vs-tmave-prazeni-kavy', 'jak-pripravit-espresso', 'jak-pripravit-kavu-doma'],
        'kolik-kofeinu-ma-kava' => ['bezkofeinova-kava-myty-a-fakta-pro-rok-2026', 'arabica-vs-robusta-rozdil', 'svetle-vs-tmave-prazeni-kavy'],
    ];

    public function run(): void
    {
        $ids = Post::pluck('id', 'slug_cs');

        foreach (self::RELATED as $slug => $relatedSlugs) {
            $post = isset($ids[$slug]) ? Post::find($ids[$slug]) : null;

            if (! $post) {
                $this->command?->warn("Článek {$slug} neexistuje, přeskočeno.");
                continue;
            }

            $relatedIds = [];
            foreach ($relatedSlugs as $relatedSlug) {
                if (isset($ids[$relatedSlug])) {
                    $relatedIds[] = $ids[$relatedSlug];
                } else {
                    $this->command?->warn("  {$slug}: související {$relatedSlug} neexistuje, vynechán.");
                }
            }

            $post->syncRelatedPosts($relatedIds);
            $this->command?->info("{$slug}: " . count($relatedIds) . ' souvisejících');
        }
    }
}
