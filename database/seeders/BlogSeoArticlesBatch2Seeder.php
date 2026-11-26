<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Druhá várka SEO článků (10 článků, CS + EN, listopad 2026).
 *
 * Těla článků jsou v database/seeders/blog/batch2/{slug_cs}.cs.html a .en.html,
 * tady jsou jen metadata. Perex = meta popis (šablona ho vypisuje pod H1 a používá
 * jako meta description), titulek = H1.
 *
 * Spouštět ručně: php artisan db:seed --class=BlogSeoArticlesBatch2Seeder --force
 * Idempotentní (klíčem je slug_cs). Přepíše titulky, perexy, obsah, štítky a datum
 * publikace; náhledový obrázek nahraný v adminu nechá být.
 * Související články nastavuje BlogRelatedPostsSeeder, odkazy z dřívějších článků
 * BlogExistingArticlesLinksSeeder – spouštět až po tomto seederu.
 */
class BlogSeoArticlesBatch2Seeder extends Seeder
{
    private const CONTENT_DIR = __DIR__ . '/blog/batch2';

    public function run(): void
    {
        $articles = $this->articles();

        // Před zápisem ověřit soubory, štítky a kolize slugů, ať se nic nezapíše napůl.
        $tags = Tag::pluck('id', 'slug_cs');

        foreach ($articles as $slug => $article) {
            foreach (['cs', 'en'] as $locale) {
                if (! is_file($this->contentPath($slug, $locale))) {
                    $this->command->error("Chybí soubor {$slug}.{$locale}.html. Nic nebylo zapsáno.");

                    return;
                }
            }

            if (! isset($tags[$article['tag']])) {
                $this->command->error("Štítek '{$article['tag']}' neexistuje. Nic nebylo zapsáno.");

                return;
            }

            $conflict = Post::where('slug_en', $article['slug_en'])
                ->where('slug_cs', '!=', $slug)
                ->first();

            if ($conflict) {
                $this->command->error(
                    "Kolize slug_en '{$article['slug_en']}' s postem #{$conflict->id} ({$conflict->slug_cs}). Nic nebylo zapsáno."
                );

                return;
            }
        }

        DB::transaction(function () use ($articles, $tags) {
            foreach ($articles as $slug => $article) {
                $post = Post::updateOrCreate(
                    ['slug_cs' => $slug],
                    [
                        'slug_en' => $article['slug_en'],
                        'title_cs' => $article['title_cs'],
                        'title_en' => $article['title_en'],
                        'perex_cs' => $article['perex_cs'],
                        'perex_en' => $article['perex_en'],
                        'content_cs' => $this->content($slug, 'cs'),
                        'content_en' => $this->content($slug, 'en'),
                        'author' => 'KAVI',
                        'status' => 'published',
                        'published_at' => $article['published_at'],
                    ]
                );

                $post->tags()->sync([$tags[$article['tag']]]);

                $this->command->info(
                    ($post->wasRecentlyCreated ? 'Vytvořeno: ' : 'Aktualizováno: ') . $slug . ' (' . $article['published_at'] . ')'
                );
            }
        });
    }

    private function contentPath(string $slug, string $locale): string
    {
        return self::CONTENT_DIR . "/{$slug}.{$locale}.html";
    }

    private function content(string $slug, string $locale): string
    {
        return trim(file_get_contents($this->contentPath($slug, $locale))) . "\n";
    }

    /**
     * slug_cs => metadata. Antidatováno po týdnu od posledního článku z 19. 9. 2026,
     * nejnovější je dárkový článek.
     */
    private function articles(): array
    {
        return [
            'z-jake-zeme-je-nejlepsi-kava' => [
                'slug_en' => 'which-country-has-the-best-coffee',
                'title_cs' => 'Z jaké země je nejlepší káva? Průvodce kávovými zeměmi a jejich chutí',
                'title_en' => 'Which Country Has the Best Coffee? A Guide to Origins and Flavors',
                'perex_cs' => 'Etiopie, Keňa, Kolumbie, nebo Brazílie? Jak chutná káva z hlavních pěstitelských zemí a podle čeho najít tu, která bude nejlepší právě pro vás.',
                'perex_en' => 'Which country has the best coffee? How coffees from Ethiopia, Kenya, Colombia, Brazil, Panama and more taste, and how to find the origin you like best.',
                'tag' => 'vyberova-kava',
                'published_at' => '2026-09-24 09:30:00',
            ],
            'jak-vybrat-zrnkovou-kavu' => [
                'slug_en' => 'how-to-choose-coffee-beans',
                'title_cs' => 'Jak vybrat zrnkovou kávu: co prozradí obal výběrové kávy',
                'title_en' => 'How to Choose Coffee Beans: What to Look for on the Bag',
                'perex_cs' => 'Jak vybrat dobrou zrnkovou kávu? Co znamenají údaje na obalu – původ, odrůda, zpracování, datum pražení – a jak vybrat kávu podle kávovaru i chuti.',
                'perex_en' => 'How to choose coffee beans: pick by brewer and taste, read the roast date, origin and processing on the bag, and avoid labels like "100% Arabica" and "intensity".',
                'tag' => 'vyberova-kava',
                'published_at' => '2026-10-01 10:15:00',
            ],
            'kolik-salku-kavy-z-250-g' => [
                'slug_en' => 'how-many-cups-of-coffee-in-250g',
                'title_cs' => 'Kolik šálků kávy je v 250 g a v 1 kg? Výpočet, dávkování a cena za šálek',
                'title_en' => 'How Many Cups of Coffee in 250 g? Doses for Every Method',
                'perex_cs' => 'Kolik šálků kávy připravíte z 250 g nebo 1 kg zrn? Dávkování pro espresso, filtr, French press i turka, spotřeba za měsíc a cena za šálek.',
                'perex_en' => 'How many cups of coffee are in a 250 g or 1 kg bag? Doses for espresso, filter and French press, monthly consumption and the real cost per cup at home.',
                'tag' => 'tipy',
                'published_at' => '2026-10-08 09:00:00',
            ],
            'jak-dlouho-nechat-kavu-odpocinout-po-prazeni' => [
                'slug_en' => 'how-long-to-rest-coffee-after-roasting',
                'title_cs' => 'Jak dlouho nechat kávu odpočinout po pražení? Průvodce odplyněním',
                'title_en' => 'How Long to Rest Coffee After Roasting: Filter vs. Espresso',
                'perex_cs' => 'Čerstvě upražená káva není hned nejlepší. Co je odplynění, kolik dní nechat kávu odpočinout na filtr a na espresso a kdy chutná nejlépe.',
                'perex_en' => 'How long to rest coffee after roasting: 1–2 weeks for filter, 2–4 for espresso. What degassing does, when light roasts peak and how long they stay fresh.',
                'tag' => 'tipy',
                'published_at' => '2026-10-15 11:20:00',
            ],
            'odrudy-kavy-gesha' => [
                'slug_en' => 'coffee-varieties-gesha',
                'title_cs' => 'Odrůdy kávy: Gesha, Bourbon, SL28 a další, které stojí za ochutnání',
                'title_en' => 'Coffee Varieties: From Typica and Bourbon to Gesha',
                'perex_cs' => 'Co jsou odrůdy kávy a proč na nich záleží? Gesha, Bourbon, Typica, SL28 i Pacamara – jak chutnají, odkud pocházejí a proč je Gesha nejdražší káva světa.',
                'perex_en' => 'Coffee varieties shape flavor the way grapes shape wine. Meet Typica, Bourbon, SL28, Pacamara and Gesha, and see why Gesha can sell for $30,000 a kilo.',
                'tag' => 'vyberova-kava',
                'published_at' => '2026-10-22 09:45:00',
            ],
            'vyberova-kava-s-mlekem' => [
                'slug_en' => 'specialty-coffee-with-milk',
                'title_cs' => 'Výběrová káva s mlékem: jakou zvolit na cappuccino, flat white i latte',
                'title_en' => 'Specialty Coffee with Milk: Which Beans to Choose and Why',
                'perex_cs' => 'Hodí se výběrová káva do mléka? Ano – jen vědět jakou. Která káva chutná nejlépe v cappuccinu, flat white i latte a jak doma napěnit mléko.',
                'perex_en' => 'Is specialty coffee with milk a waste? Not at all. Which beans suit cappuccino and flat white, how milk drinks differ and how to steam milk at home.',
                'tag' => 'recepty',
                'published_at' => '2026-10-29 10:30:00',
            ],
            'jak-ochutnavat-kavu-doma-cupping' => [
                'slug_en' => 'how-to-taste-coffee-at-home-cupping',
                'title_cs' => 'Jak ochutnávat kávu doma: cupping krok za krokem',
                'title_en' => 'How to Taste Coffee at Home: A Step-by-Step Cupping Guide',
                'perex_cs' => 'Naučte se ochutnávat kávu jako profesionálové. Domácí cupping krok za krokem: co potřebujete, poměr kávy a vody, jak srkat a jak popsat chuť.',
                'perex_en' => 'Learn how to taste coffee like a pro with a simple home cupping: what you need, eight easy steps, and how to describe acidity, sweetness, body and flavor.',
                'tag' => 'recepty',
                'published_at' => '2026-11-05 09:15:00',
            ],
            'nejlepsi-prazirny-kavy-v-evrope' => [
                'slug_en' => 'best-coffee-roasters-in-europe',
                'title_cs' => 'Nejlepší pražírny kávy v Evropě: 15 adres, které stojí za ochutnání',
                'title_en' => 'Best Coffee Roasters in Europe: 15 Worth Tasting',
                'perex_cs' => 'Berlín, Rotterdam, Barcelona i Vídeň. Vybrali jsme 15 nejlepších pražíren kávy v Evropě, jejich styl a příběh – a jak jejich kávy ochutnat bez cestování.',
                'perex_en' => 'The best coffee roasters in Europe, from Berlin and Rotterdam to Barcelona: 15 roasteries worth tasting and how to try their coffee without traveling.',
                'tag' => 'vyberova-kava',
                'published_at' => '2026-11-12 10:00:00',
            ],
            'predplatne-kavy-jak-vybrat' => [
                'slug_en' => 'how-to-choose-a-coffee-subscription',
                'title_cs' => 'Předplatné kávy: jak vybrat to pravé a čím se služby liší',
                'title_en' => 'How to Choose a Coffee Subscription: 9 Things to Check',
                'perex_cs' => 'Jak funguje předplatné kávy, jaké typy najdete v Česku a podle čeho vybírat? 9 kritérií, výpočet ceny za šálek a odpovědi na časté otázky.',
                'perex_en' => 'How to choose a coffee subscription: roast date, whole beans, espresso or filter, flexibility and price per cup. 9 criteria plus a quick checklist to compare.',
                'tag' => 'vyberova-kava',
                'published_at' => '2026-11-19 09:30:00',
            ],
            'darek-pro-milovnika-kavy' => [
                'slug_en' => 'coffee-lover-gift-ideas',
                'title_cs' => 'Dárek pro milovníka kávy: 12 tipů, které opravdu potěší',
                'title_en' => "Gifts for Coffee Lovers: 12 Ideas They'll Actually Use",
                'perex_cs' => 'Hledáte dárek pro milovníka kávy? 12 tipů od předplatného výběrové kávy přes mlýnek až po kurz – a jak vybrat podle toho, jak obdarovaný kávu pije.',
                'perex_en' => 'Looking for a gift for a coffee lover? 12 ideas they will actually use, from a specialty coffee subscription to a hand grinder, a scale, a book or a voucher.',
                'tag' => 'tipy',
                'published_at' => '2026-11-25 09:00:00',
            ],
        ];
    }
}
