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

        // Druhá várka (BlogSeoArticlesBatch2Seeder)
        'darek-pro-milovnika-kavy' => ['predplatne-kavy-jak-vybrat', 'nejlepsi-prazirny-kavy-v-evrope', 'jak-ochutnavat-kavu-doma-cupping'],
        'predplatne-kavy-jak-vybrat' => ['kolik-salku-kavy-z-250-g', 'jak-vybrat-zrnkovou-kavu', 'darek-pro-milovnika-kavy'],
        'nejlepsi-prazirny-kavy-v-evrope' => ['z-jake-zeme-je-nejlepsi-kava', 'svetle-vs-tmave-prazeni-kavy', 'predplatne-kavy-jak-vybrat'],
        'z-jake-zeme-je-nejlepsi-kava' => ['odrudy-kavy-gesha', 'metody-zpracovani-kavy', 'jak-ochutnavat-kavu-doma-cupping'],
        'kolik-salku-kavy-z-250-g' => ['jak-pripravit-kavu-doma', 'jak-pripravit-espresso', 'predplatne-kavy-jak-vybrat'],
        'jak-dlouho-nechat-kavu-odpocinout-po-prazeni' => ['jak-skladovat-kavu-cerstvost', 'jak-vybrat-zrnkovou-kavu', 'kava-na-espresso-nebo-na-filtr'],
        'jak-ochutnavat-kavu-doma-cupping' => ['chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', 'z-jake-zeme-je-nejlepsi-kava', 'metody-zpracovani-kavy'],
        'jak-vybrat-zrnkovou-kavu' => ['jak-dlouho-nechat-kavu-odpocinout-po-prazeni', 'odrudy-kavy-gesha', 'kava-na-espresso-nebo-na-filtr'],
        'odrudy-kavy-gesha' => ['z-jake-zeme-je-nejlepsi-kava', 'arabica-vs-robusta-rozdil', 'metody-zpracovani-kavy'],
        'vyberova-kava-s-mlekem' => ['jak-pripravit-espresso', 'kava-na-espresso-nebo-na-filtr', 'kolik-salku-kavy-z-250-g'],
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
