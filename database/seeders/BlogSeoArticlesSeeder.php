<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BlogSeoArticlesSeeder extends Seeder
{
    /**
     * SEO blog articles about specialty coffee (výběrová káva).
     *
     * Idempotent: keyed on slug_cs via updateOrCreate, so it can be re-run
     * safely (e.g. after EN translations are added).
     */
    public function run(): void
    {
        $articles = $this->articles();

        // slug_en is UNIQUE while we match on slug_cs, so a pre-existing post
        // using one of our EN slugs would blow up mid-run. Check first.
        foreach ($articles as $article) {
            $conflict = Post::where('slug_en', $article['slug_en'])
                ->where('slug_cs', '!=', $article['slug_cs'])
                ->first();

            if ($conflict) {
                $this->command->error(
                    "Kolize slug_en '{$article['slug_en']}' s postem #{$conflict->id} ({$conflict->slug_cs}). Nic nebylo zapsáno."
                );

                return;
            }
        }

        DB::transaction(function () use ($articles) {
            foreach ($articles as $article) {
                $post = Post::updateOrCreate(
                    ['slug_cs' => $article['slug_cs']],
                    array_merge($article, [
                        'author' => 'KAVI',
                        'status' => 'published',
                        'published_at' => $article['published_at'] ?? Carbon::now(),
                    ])
                );

                $this->command->info(
                    ($post->wasRecentlyCreated ? 'Vytvořeno: ' : 'Aktualizováno: ').$article['slug_cs']
                );
            }
        });
    }

    /**
     * Reusable subscription CTA block, inserted inside each article.
     * Uses only Purify-allowed tags (blockquote, h3, p, strong, a).
     */
    private function cta(): string
    {
        return <<<'HTML'
<blockquote>
<h3>Chcete čerstvou výběrovou kávu každý měsíc?</h3>
<p>S kávovým předplatným KAVI vám pošleme čerstvě pražená zrna od těch nejlepších pražíren rovnou domů. Sami si zvolíte druh, množství i jak často – a kdykoli můžete odběr pozastavit nebo zrušit.</p>
<p><strong><a href="/predplatne">Prohlédnout kávové předplatné →</a></strong></p>
</blockquote>
HTML;
    }

    /**
     * English variant of the subscription CTA block.
     * Links to the EN subscription route (/subscription).
     */
    private function ctaEn(): string
    {
        return <<<'HTML'
<blockquote>
<h3>Want fresh specialty coffee every month?</h3>
<p>With a KAVI coffee subscription we deliver freshly roasted beans from the best roasteries straight to your door. You choose the coffee, the amount and how often – and you can pause or cancel any time.</p>
<p><strong><a href="/subscription">Explore our coffee subscription →</a></strong></p>
</blockquote>
HTML;
    }

    private function articles(): array
    {
        $cta = $this->cta();
        $ctaEn = $this->ctaEn();

        return [
            // 1. Arabica vs Robusta
            [
                'title_cs' => 'Arabica vs. Robusta: jaký je rozdíl?',
                'slug_cs' => 'arabica-vs-robusta-rozdil',
                'perex_cs' => 'Arabica, nebo robusta? Poradíme, jak se oba nejrozšířenější druhy kávy liší chutí, obsahem kofeinu, cenou i pěstováním – a proč výběrová káva staví hlavně na arabice.',
                'content_cs' => <<<HTML
<p>Řešíte, jaký je <strong>rozdíl mezi arabicou a robustou</strong>? Na obalech kávy narazíte pořád na tato dvě jména. Jde o dva nejrozšířenější druhy kávovníku, které se liší nejen chutí, ale i obsahem kofeinu, cenou a nároky na pěstování. V tomhle článku si podrobně vysvětlíme, čím se <strong>arabica a robusta</strong> odlišují, jak poznat kvalitu a proč se svět výběrové (specialty) kávy točí především kolem arabiky.</p>

<h2>Arabica a robusta: dva různé druhy kávovníku</h2>
<p>Přestože obě rostliny patří do stejného rodu <em>Coffea</em>, jde o odlišné botanické druhy s jinými vlastnostmi. Dohromady pokrývají naprostou většinu světové produkce kávy – arabica zhruba 60–70 %, robusta zbytek. Rozdíl mezi arabicou a robustou přitom nezačíná v šálku, ale už na plantáži: v nadmořské výšce, klimatu i odolnosti rostliny.</p>

<h2>Arabica: jemnost, aroma a bohatství chutí</h2>
<p>Arabica (<em>Coffea arabica</em>) roste ve vyšších nadmořských výškách, nejčastěji mezi 900 a 2 000 metry nad mořem. Je náročnější na pěstování, citlivější na nemoci i výkyvy počasí a plodí méně. Právě proto je dražší – ale odměnou je výrazně komplexnější a jemnější chuť, kvůli které si ji milovníci kávy tolik cení.</p>
<ul>
<li><strong>Chuť:</strong> jemná, aromatická, s ovocnými, květinovými, čokoládovými či oříškovými tóny</li>
<li><strong>Kyselost:</strong> vyšší a příjemná, dodává kávě „živost" a svěžest</li>
<li><strong>Obsah kofeinu:</strong> nižší, přibližně 1,2–1,5 %</li>
<li><strong>Pěstování:</strong> vyšší polohy, náročnější a citlivější rostlina</li>
</ul>
<p>Díky nižšímu obsahu kofeinu a vyšší kyselosti působí arabica plněji a vyváženěji. Je to volba všude tam, kde chcete v šálku poznat původ zrna a jeho charakter.</p>

<h2>Robusta: síla, kofein, hořkost a crema</h2>
<p>Robusta (<em>Coffea canephora</em>) je odolnější druh, který roste i v nižších polohách a snáší teplejší a vlhčí klima. Obsahuje téměř dvojnásobek kofeinu než arabica, což ji přirozeně chrání před škůdci. Chuťově je výraznější, hořčí a „zemitější", a proto se často používá do espresso směsí.</p>
<ul>
<li><strong>Chuť:</strong> silná, hořká, zemitá, někdy až ořechová nebo dřevitá</li>
<li><strong>Kyselost:</strong> nízká</li>
<li><strong>Obsah kofeinu:</strong> vysoký, přibližně 2,2–2,7 %</li>
<li><strong>Crema:</strong> tvoří hustší a stabilnější pěnu na espressu</li>
<li><strong>Pěstování:</strong> nižší polohy, odolná a vydatnější rostlina</li>
</ul>
<p>Kvalitní robusta má na kávové scéně své místo – zejména jako součást espresso směsí, kterým dodává tělo, hořkou strukturu a výraznou cremu. Levná a špatně zpracovaná robusta ale bývá důvodem, proč některé komerční kávy chutnají ploše a hořce.</p>

<h2>Arabica vs. robusta: hlavní rozdíly přehledně</h2>
<p>Když si chcete <strong>rozdíl mezi arabicou a robustou</strong> zapamatovat jednoduše, drží se to několika bodů:</p>
<ul>
<li><strong>Chuť:</strong> arabica jemná a aromatická × robusta silná a hořká</li>
<li><strong>Kofein:</strong> arabica méně × robusta téměř dvojnásobek</li>
<li><strong>Kyselost:</strong> arabica vyšší a příjemná × robusta nízká</li>
<li><strong>Cena:</strong> arabica dražší × robusta levnější</li>
<li><strong>Pěstování:</strong> arabica vyšší polohy a náročná × robusta nížiny a odolná</li>
</ul>

<h2>Proč výběrová káva sází na arabiku?</h2>
<p>Výběrová (specialty) káva se hodnotí podle chuťové komplexnosti, čistoty a vyváženosti. A právě v tom arabica vyniká – dokáže nabídnout desítky rozpoznatelných chuťových tónů, které odrážejí odrůdu, půdu, nadmořskou výšku i způsob zpracování. Proto naprostá většina výběrových káv vzniká ze 100% arabiky.</p>
<p>To ale neznamená, že robusta do specialty světa nepatří. Roste zájem o kvalitně pěstovanou a pečlivě zpracovanou robustu, která umí být překvapivě čistá a zajímavá. Klíčem není druh sám o sobě, ale kvalita a péče při pěstování i zpracování.</p>

{$cta}

<h2>Jak poznat kvalitní kávu bez ohledu na druh</h2>
<p>Ať už sáhnete po arabice, nebo směsi s robustou, kvalitu pozná i laik podle několika vodítek:</p>
<ul>
<li><strong>Datum pražení</strong> na obalu (ne jen datum spotřeby) – čerstvost je zásadní</li>
<li>Informace o <strong>původu</strong> kávy (země, region, farma či pražírna)</li>
<li>Uvedený <strong>způsob zpracování</strong> a chuťový profil</li>
<li>Neprůhledný obal s <strong>jednosměrným ventilkem</strong>, který kávu chrání</li>
</ul>

<h2>Která káva je tedy lepší?</h2>
<p>Neexistuje univerzální vítěz – záleží na tom, co od kávy čekáte. Pokud hledáte jemnou, aromatickou kávu s příběhem a širokou paletou chutí, sáhněte po <strong>100% arabice</strong>. Pokud máte rádi silné, hořké espresso s výraznou cremou a vyšší dávkou kofeinu, oceníte kvalitní směs s podílem robusty.</p>
<p>Nejlepší způsob, jak si najít svého favorita, je zkoušet. Ochutnávejte kávy různého původu, druhu i zpracování a všímejte si, co vám na chuti sedí nejvíc.</p>

<h2>Časté dotazy</h2>
<h3>Je arabica vždycky lepší než robusta?</h3>
<p>Ne nutně. Arabica bývá jemnější a aromatičtější, ale kvalitně zpracovaná robusta může být skvělá – hlavně v espressu. Rozhoduje kvalita a čerstvost, ne jen druh.</p>
<h3>Má robusta víc kofeinu?</h3>
<p>Ano. Robusta obsahuje přibližně dvojnásobek kofeinu oproti arabice, což je i důvod její výraznější a hořčejší chuti.</p>
<h3>Proč je arabica dražší?</h3>
<p>Roste ve vyšších polohách, je náročnější na pěstování, citlivější na nemoci a plodí méně. Vyšší cena odráží náročnější produkci a lepší chuťové vlastnosti.</p>
HTML,
                'title_en' => 'Arabica vs. Robusta: What Is the Difference?',
                'slug_en' => 'arabica-vs-robusta-difference',
                'perex_en' => 'Arabica or robusta? We explain how the two most common coffee species differ in taste, caffeine content, price and cultivation – and why specialty coffee is built mainly on arabica.',
                'content_en' => <<<HTML
<p>Wondering what the <strong>difference between arabica and robusta</strong> actually is? These two names appear on almost every bag of coffee. They are the two most widely grown coffee species, and they differ not only in taste but also in caffeine content, price and growing conditions. In this article we explain in detail how <strong>arabica and robusta</strong> differ, how to spot quality, and why the world of specialty coffee revolves mainly around arabica.</p>

<h2>Arabica and robusta: two different coffee species</h2>
<p>Although both plants belong to the same genus <em>Coffea</em>, they are distinct botanical species with different properties. Together they cover the vast majority of world coffee production – arabica roughly 60–70 %, robusta the rest. The difference between arabica and robusta does not start in the cup, but on the plantation: in altitude, climate and the resilience of the plant.</p>

<h2>Arabica: delicacy, aroma and a wealth of flavours</h2>
<p>Arabica (<em>Coffea arabica</em>) grows at higher altitudes, most often between 900 and 2,000 metres above sea level. It is harder to cultivate, more sensitive to disease and weather swings, and yields less fruit. That is exactly why it costs more – but the reward is a far more complex and delicate flavour that coffee lovers value so highly.</p>
<ul>
<li><strong>Taste:</strong> delicate, aromatic, with fruity, floral, chocolate or nutty notes</li>
<li><strong>Acidity:</strong> higher and pleasant, giving the coffee liveliness and freshness</li>
<li><strong>Caffeine content:</strong> lower, roughly 1.2–1.5 %</li>
<li><strong>Cultivation:</strong> higher altitudes, a more demanding and sensitive plant</li>
</ul>
<p>Thanks to its lower caffeine content and higher acidity, arabica tastes fuller and more balanced. It is the choice wherever you want to taste the origin of the bean and its character.</p>

<h2>Robusta: strength, caffeine, bitterness and crema</h2>
<p>Robusta (<em>Coffea canephora</em>) is the more resilient species, growing at lower altitudes and tolerating hotter, more humid climates. It contains almost twice as much caffeine as arabica, which naturally protects it from pests. In terms of flavour it is bolder, more bitter and more earthy, which is why it is often used in espresso blends.</p>
<ul>
<li><strong>Taste:</strong> strong, bitter, earthy, sometimes nutty or woody</li>
<li><strong>Acidity:</strong> low</li>
<li><strong>Caffeine content:</strong> high, roughly 2.2–2.7 %</li>
<li><strong>Crema:</strong> produces a thicker, more stable foam on espresso</li>
<li><strong>Cultivation:</strong> lower altitudes, a hardy and more productive plant</li>
</ul>
<p>Quality robusta has its place on the coffee scene – particularly as part of espresso blends, where it adds body, a bitter structure and a rich crema. Cheap, poorly processed robusta, however, is often the reason some commercial coffees taste flat and bitter.</p>

<h2>Arabica vs. robusta: the main differences at a glance</h2>
<p>If you want to remember the <strong>difference between arabica and robusta</strong> simply, it comes down to a few points:</p>
<ul>
<li><strong>Taste:</strong> arabica delicate and aromatic × robusta strong and bitter</li>
<li><strong>Caffeine:</strong> arabica less × robusta almost double</li>
<li><strong>Acidity:</strong> arabica higher and pleasant × robusta low</li>
<li><strong>Price:</strong> arabica more expensive × robusta cheaper</li>
<li><strong>Cultivation:</strong> arabica high altitudes and demanding × robusta lowlands and hardy</li>
</ul>

<h2>Why does specialty coffee rely on arabica?</h2>
<p>Specialty coffee is judged on flavour complexity, cleanliness and balance. And that is exactly where arabica excels – it can offer dozens of recognisable flavour notes that reflect the variety, the soil, the altitude and the processing method. This is why the vast majority of specialty coffees are made from 100% arabica.</p>
<p>That does not mean robusta has no place in the specialty world. Interest is growing in carefully grown and meticulously processed robusta, which can be surprisingly clean and interesting. The key is not the species itself, but the quality and care in growing and processing.</p>

{$ctaEn}

<h2>How to recognise quality coffee regardless of species</h2>
<p>Whether you reach for arabica or a blend with robusta, even a beginner can spot quality from a few clues:</p>
<ul>
<li>A <strong>roast date</strong> on the bag (not just a best-before date) – freshness is essential</li>
<li>Information about the coffee's <strong>origin</strong> (country, region, farm or roastery)</li>
<li>A stated <strong>processing method</strong> and flavour profile</li>
<li>An opaque bag with a <strong>one-way valve</strong> that protects the coffee</li>
</ul>

<h2>So which coffee is better?</h2>
<p>There is no universal winner – it depends on what you want from your coffee. If you are looking for a delicate, aromatic coffee with a story and a wide palette of flavours, reach for <strong>100% arabica</strong>. If you love strong, bitter espresso with a rich crema and a higher caffeine kick, you will appreciate a quality blend containing robusta.</p>
<p>The best way to find your favourite is to experiment. Taste coffees of different origins, species and processing methods, and pay attention to what suits your palate most.</p>

<h2>Frequently asked questions</h2>
<h3>Is arabica always better than robusta?</h3>
<p>Not necessarily. Arabica tends to be more delicate and aromatic, but well-processed robusta can be excellent – especially in espresso. Quality and freshness matter more than species alone.</p>
<h3>Does robusta have more caffeine?</h3>
<p>Yes. Robusta contains roughly twice as much caffeine as arabica, which is also why it tastes bolder and more bitter.</p>
<h3>Why is arabica more expensive?</h3>
<p>It grows at higher altitudes, is harder to cultivate, more susceptible to disease and yields less. The higher price reflects more demanding production and better flavour characteristics.</p>
HTML,
                'published_at' => '2026-07-01 09:00:00',
            ],

            // 2. Zrnková vs mletá
            [
                'title_cs' => 'Zrnková, nebo mletá káva? Rozhoduje čerstvost',
                'slug_cs' => 'zrnkova-vs-mleta-kava',
                'perex_cs' => 'Zrnková, nebo mletá káva? Vysvětlíme, proč čerstvě namletá zrna chutnají výrazně lépe, jak rychle mletá káva ztrácí aroma, jakou roli hraje mlýnek a na co si dát pozor při nákupu.',
                'content_cs' => <<<HTML
<p><strong>Zrnková, nebo mletá káva?</strong> Tahle zdánlivě banální otázka rozhoduje o tom, jak vaše káva nakonec bude chutnat. Rozdíl přitom není v druhu kávy, ale v <strong>čerstvosti</strong> – a ta se u mleté kávy vytrácí mnohem rychleji, než byste čekali. V článku si vysvětlíme, proč čerstvě mletá káva vyhrává, jakou roli hraje mlýnek a kdy naopak dává mletá káva smysl.</p>

<h2>Zrnková vs. mletá káva: v čem je hlavní rozdíl</h2>
<p>Zrnková i mletá káva může pocházet ze stejných zrn, stejné pražírny i stejné šarže. Rozdíl nastává v okamžiku mletí. Jakmile kávu namelete, dramaticky se zvětší její povrch, který přijde do styku se vzduchem. Aromatické látky – to nejcennější, co káva má – začnou okamžitě oxidovat a unikat.</p>
<p>Zatímco celá zrna si drží chuť týdny, mletá káva ztrácí většinu aroma už během několika desítek minut až hodin. Proto <strong>čerstvě mletá káva</strong> voní a chutná nesrovnatelně lépe než káva namletá před týdny.</p>

<h2>Proč zrnková káva vyhrává</h2>
<ul>
<li><strong>Aroma:</strong> čerstvě namletá zrna voní intenzivněji a plněji</li>
<li><strong>Chuť:</strong> zůstává vyvážená, bez „ploché" a oxidované pachuti</li>
<li><strong>Kontrola:</strong> hrubost mletí si nastavíte přesně podle způsobu přípravy</li>
<li><strong>Trvanlivost:</strong> celá zrna vydrží čerstvá výrazně déle než mletá káva</li>
</ul>
<p>Když si kávu melete až těsně před přípravou, dostanete z každého zrnka maximum. Je to jednoznačně největší a nejlevnější „upgrade", který můžete své domácí kávě dopřát.</p>

<h2>Jak rychle mletá káva ztrácí aroma</h2>
<p>Oxidace u mleté kávy postupuje překvapivě rychle. Nejvýraznější aromatické látky se ztrácejí už během prvních minut po namletí. Během několika hodin káva znatelně „vyprchá" a po pár dnech v otevřeném obalu chutná plošší a hořčeji. To je důvod, proč i drahá výběrová káva zklame, pokud ji koupíte namletou a pomalu ji doma spotřebováváte.</p>

<h2>Role mlýnku: srdce dobré kávy</h2>
<p>Kdo to s kávou myslí vážně, ocení vlastní mlýnek. A záleží na jeho typu:</p>
<ul>
<li><strong>Kotoučový (žernovový) mlýnek</strong> – melte rovnoměrně a umožní nastavit hrubost. Ideální volba pro každou metodu přípravy.</li>
<li><strong>Nožový mlýnek</strong> – zrna spíš rozseká nerovnoměrně. Výsledkem je nevyrovnaná extrakce: část kávy je přepálená a hořká, část nedochucená a kyselá.</li>
</ul>
<p>Správná hrubost mletí je zásadní. Pro French press potřebujete hrubé mletí, pro filtr střední a pro moka konvičku či espresso jemné. Vlastní mlýnek vám dá volnost si tohle pokaždé přizpůsobit.</p>

{$cta}

<h2>Kdy má mletá káva smysl</h2>
<p>Mletá káva je pohodlná a pro spoustu lidí naprosto v pořádku – zvlášť pokud ji spotřebujete rychle a skladujete správně. Pokud po vlastním mlýnku zatím sáhnout nechcete, řiďte se pár pravidly:</p>
<ul>
<li>Kupujte <strong>menší balení</strong>, aby vám káva nezvětrala</li>
<li>Vybírejte kávu s vyznačeným <strong>datem pražení</strong>, ne jen datem spotřeby</li>
<li>Skladujte ji ve <strong>vzduchotěsné, neprůhledné dóze</strong> na chladném a suchém místě</li>
<li>Nechte si kávu namlít na <strong>konkrétní způsob přípravy</strong>, který doma používáte</li>
</ul>

<h2>Na co si dát pozor při nákupu kávy</h2>
<ul>
<li><strong>Datum pražení</strong> – čím čerstvější, tím lépe (ideál je káva stará týdny, ne měsíce)</li>
<li><strong>Velikost balení</strong> – kupujte tolik, kolik za pár týdnů vypijete</li>
<li><strong>Obal</strong> – kvalitní káva bývá v neprůhledném sáčku s jednosměrným ventilkem</li>
<li><strong>Původ a profil</strong> – informace o zemi, pražírně a chuti svědčí o poctivém přístupu</li>
</ul>

<h2>Časté dotazy</h2>
<h3>Je zrnková káva lepší než mletá?</h3>
<p>Z hlediska čerstvosti a chuti ano. Celá zrna si drží aroma mnohem déle a namletím těsně před přípravou z nich dostanete maximum.</p>
<h3>Jak dlouho vydrží mletá káva čerstvá?</h3>
<p>Nejlepší je spotřebovat ji během několika dní. Aroma se ztrácí už během minut po namletí a v otevřeném obalu káva rychle vyprchá.</p>
<h3>Vyplatí se koupit vlastní mlýnek?</h3>
<p>Rozhodně. Kotoučový mlýnek je jedno z nejlepších vylepšení domácí kávy – zajistí čerstvost i správnou hrubost mletí pro vaši metodu přípravy.</p>
<p>Závěr je jednoduchý: pokud chcete z kávy dostat maximum, sáhněte po zrnkové a melte až těsně před přípravou. Rozdíl v šálku poznáte hned.</p>
HTML,
                'title_en' => 'Whole Bean or Ground Coffee? Freshness Decides',
                'slug_en' => 'whole-bean-vs-ground-coffee',
                'perex_en' => 'Whole bean or ground coffee? We explain why freshly ground beans taste noticeably better, how quickly ground coffee loses its aroma, what role the grinder plays and what to watch for when buying.',
                'content_en' => <<<HTML
<p><strong>Whole bean or ground coffee?</strong> This seemingly trivial question decides how your coffee will ultimately taste. The difference is not in the type of coffee, but in <strong>freshness</strong> – and with ground coffee it disappears far faster than you would expect. In this article we explain why freshly ground coffee wins, what role the grinder plays, and when ground coffee makes sense after all.</p>

<h2>Whole bean vs. ground coffee: the main difference</h2>
<p>Whole bean and ground coffee can come from the same beans, the same roastery and the same batch. The difference happens at the moment of grinding. As soon as you grind coffee, its surface area exposed to air increases dramatically. The aromatic compounds – the most precious thing coffee has – immediately begin to oxidise and escape.</p>
<p>While whole beans hold their flavour for weeks, ground coffee loses most of its aroma within tens of minutes to a few hours. That is why <strong>freshly ground coffee</strong> smells and tastes incomparably better than coffee ground weeks ago.</p>

<h2>Why whole bean coffee wins</h2>
<ul>
<li><strong>Aroma:</strong> freshly ground beans smell more intense and fuller</li>
<li><strong>Taste:</strong> stays balanced, without a flat, oxidised aftertaste</li>
<li><strong>Control:</strong> you set the grind size exactly to your brewing method</li>
<li><strong>Shelf life:</strong> whole beans stay fresh significantly longer than ground coffee</li>
</ul>
<p>When you grind your coffee right before brewing, you get the maximum out of every bean. It is by far the biggest and cheapest upgrade you can give your coffee at home.</p>

<h2>How fast ground coffee loses its aroma</h2>
<p>Oxidation in ground coffee progresses surprisingly quickly. The most distinctive aromatic compounds are lost within the first few minutes after grinding. Within hours the coffee noticeably fades, and after a few days in an open bag it tastes flatter and more bitter. This is why even expensive specialty coffee disappoints if you buy it pre-ground and slowly work through it at home.</p>

<h2>The role of the grinder: the heart of good coffee</h2>
<p>If you are serious about coffee, you will appreciate having your own grinder. And the type matters:</p>
<ul>
<li><strong>Burr grinder</strong> – grinds evenly and lets you adjust the grind size. The ideal choice for every brewing method.</li>
<li><strong>Blade grinder</strong> – tends to chop the beans unevenly. The result is uneven extraction: part of the coffee is over-extracted and bitter, part under-extracted and sour.</li>
</ul>
<p>The right grind size is essential. For a French press you need a coarse grind, for filter a medium one, and for a moka pot or espresso a fine one. Your own grinder gives you the freedom to adjust this every time.</p>

{$ctaEn}

<h2>When ground coffee makes sense</h2>
<p>Ground coffee is convenient and perfectly fine for plenty of people – especially if you use it quickly and store it properly. If you do not want to invest in your own grinder yet, follow a few rules:</p>
<ul>
<li>Buy <strong>smaller bags</strong> so the coffee does not go stale on you</li>
<li>Choose coffee with a marked <strong>roast date</strong>, not just a best-before date</li>
<li>Store it in an <strong>airtight, opaque container</strong> in a cool, dry place</li>
<li>Have the coffee ground for the <strong>specific brewing method</strong> you use at home</li>
</ul>

<h2>What to watch for when buying coffee</h2>
<ul>
<li><strong>Roast date</strong> – the fresher the better (ideally coffee that is weeks old, not months)</li>
<li><strong>Bag size</strong> – buy as much as you will drink in a few weeks</li>
<li><strong>Packaging</strong> – quality coffee comes in an opaque bag with a one-way valve</li>
<li><strong>Origin and profile</strong> – details about the country, roastery and flavour signal an honest approach</li>
</ul>

<h2>Frequently asked questions</h2>
<h3>Is whole bean coffee better than ground?</h3>
<p>In terms of freshness and taste, yes. Whole beans retain their aroma much longer, and grinding right before brewing gets the most out of them.</p>
<h3>How long does ground coffee stay fresh?</h3>
<p>It is best used within a few days. Aroma starts disappearing within minutes of grinding, and in an open bag the coffee fades quickly.</p>
<h3>Is it worth buying your own grinder?</h3>
<p>Absolutely. A burr grinder is one of the best upgrades for coffee at home – it guarantees freshness and the right grind size for your brewing method.</p>
<p>The conclusion is simple: if you want to get the most out of your coffee, buy whole beans and grind them right before brewing. You will taste the difference immediately.</p>
HTML,
                'published_at' => '2026-07-08 09:00:00',
            ],

            // 3. Příprava kávy doma
            [
                'title_cs' => 'Jak připravit kávu doma: návod na 4 metody',
                'slug_cs' => 'jak-pripravit-kavu-doma',
                'perex_cs' => 'Jak připravit skvělou kávu doma i bez drahého kávovaru? Praktický návod na poměry kávy a vody, hrubost mletí, teplotu i časy pro filtr (V60), French press, Aeropress a moka konvičku.',
                'content_cs' => <<<HTML
<p>Ptáte se, <strong>jak připravit dobrou kávu doma</strong> i bez drahého espresso kávovaru? Dobrá zpráva: stačí čerstvá výběrová zrna, správný poměr kávy a vody a trocha praxe. V tomhle návodu na přípravu kávy si projdeme čtyři nejoblíbenější metody – filtr (V60), French press, Aeropress a moka konvičku – a u každé si řekneme klíčová čísla i postup krok za krokem.</p>

<h2>Základní pravidla přípravy kávy, která platí vždy</h2>
<p>Ať zvolíte jakoukoli metodu, tři věci rozhodují o výsledku nejvíc:</p>
<ul>
<li><strong>Poměr kávy a vody:</strong> jako výchozí bod používejte zhruba 60 g kávy na 1 litr vody (tedy 6 g na 100 ml)</li>
<li><strong>Teplota vody:</strong> ideálně 92–96 °C – ne vroucí, chvíli po převaření</li>
<li><strong>Čerstvost a mletí:</strong> melte až těsně před přípravou a zvolte správnou hrubost</li>
</ul>
<p>Kvalitní čerstvá voda je také důležitá – tvoří přes 98 % nápoje. Vyhněte se silně tvrdé nebo chlorované vodě.</p>

<h2>Jak připravit filtrovanou kávu (V60 / překapávaná)</h2>
<p>Filtrovaná káva je čistá a aromatická, vyniknou v ní ovocné a květinové tóny. Patří k nejoblíbenějším metodám přípravy výběrové kávy doma.</p>
<ul>
<li><strong>Hrubost mletí:</strong> středně jemné (jako krystalový cukr)</li>
<li><strong>Poměr:</strong> 15 g kávy na 250 ml vody</li>
<li><strong>Teplota:</strong> 92–94 °C</li>
</ul>
<p><strong>Postup:</strong> Filtr propláchněte horkou vodou (zbavíte se papírové pachuti a nahřejete nádobu). Nasypte kávu, udělejte důlek. Nejdřív zalijte jen malým množstvím vody (dvojnásobek hmotnosti kávy) a nechte 30–45 sekund „nakvést" – tomu se říká bloom a uvolní se při něm oxid uhličitý. Pak dolévejte vodu v pomalých kruzích. Celková doba přípravy vyjde na zhruba 2:30–3:00 minuty.</p>

<h2>Jak připravit kávu ve French pressu</h2>
<p>French press dělá plnou, tělnatou kávu s minimem vybavení. Je ideální pro začátečníky, kteří chtějí vědět, jak uvařit dobrou kávu bez složité techniky.</p>
<ul>
<li><strong>Hrubost mletí:</strong> hrubé</li>
<li><strong>Poměr:</strong> 30 g kávy na 500 ml vody</li>
<li><strong>Teplota:</strong> 93–96 °C</li>
</ul>
<p><strong>Postup:</strong> Nasypte kávu, zalijte vodou a zamíchejte. Nechte louhovat 4 minuty. Poté opatrně sundejte pěnu z hladiny, pomalu stlačte píst a kávu <strong>ihned přelijte</strong> do šálku nebo karafy – jinak by dál extrahovala a zhořkla.</p>

{$cta}

<h2>Jak připravit kávu v Aeropressu</h2>
<p>Aeropress je rychlá a univerzální metoda s čistou chutí. Je skvělá i na cesty a odpouští drobné chyby, takže je vděčná pro experimentování.</p>
<ul>
<li><strong>Hrubost mletí:</strong> středně jemné</li>
<li><strong>Poměr:</strong> 15 g kávy na 220 ml vody</li>
<li><strong>Teplota:</strong> 85–92 °C</li>
</ul>
<p><strong>Postup:</strong> Vložte filtr, nasypte kávu, zalijte vodou a zamíchejte. Po zhruba 1:00–1:30 minutě nasaďte píst a pomalu prolisujte do šálku. Experimentujte s teplotou i časem – Aeropress má nespočet receptů.</p>

<h2>Jak připravit kávu v moka konvičce</h2>
<p>Moka konvička dělá silnou, výraznou kávu blízkou espressu. Je to klasika do každé kuchyně, která nepotřebuje elektřinu.</p>
<ul>
<li><strong>Hrubost mletí:</strong> jemné (ne však tak jemné jako na espresso)</li>
<li><strong>Poměr:</strong> naplňte sítko kávou zarovnané, bez upěchování</li>
</ul>
<p><strong>Postup:</strong> Do spodní části nalijte horkou vodu těsně pod ventilek. Sítko naplňte kávou a zarovnejte (nepěchujte). Konvičku zavřete a postavte na mírný plamen. Jakmile začne káva bublat a vytékat, stáhněte plamen a v okamžiku, kdy zvuk začne „prskat", sundejte konvičku z tepla. Spodek můžete zchladit pod studenou vodou, aby káva nezhořkla.</p>

<h2>Jak vyladit chuť podle sebe</h2>
<p>Uvedená čísla berte jako výchozí bod. Chuť lze snadno doladit:</p>
<ul>
<li><strong>Káva je příliš hořká?</strong> Zvolte hrubší mletí, nižší teplotu nebo kratší čas.</li>
<li><strong>Káva je kyselá a „prázdná"?</strong> Zkuste jemnější mletí, vyšší teplotu nebo delší extrakci.</li>
<li><strong>Slabá a vodová?</strong> Přidejte kávu nebo uberte vodu.</li>
</ul>
<p>Postupným laděním najdete šálek přesně podle své chuti. A základ úspěchu? Čerstvě pražená a čerstvě namletá výběrová káva.</p>

<h2>Časté dotazy</h2>
<h3>Jaký je nejlepší poměr kávy a vody?</h3>
<p>Osvědčený výchozí poměr je zhruba 60 g kávy na 1 litr vody (6 g na 100 ml). Podle chuti si ho pak upravte.</p>
<h3>Jak teplá má být voda na kávu?</h3>
<p>Ideálně 92–96 °C, tedy krátce po převaření. Vroucí voda kávu spálí a zhořkne.</p>
<h3>Která metoda přípravy je pro začátečníky nejjednodušší?</h3>
<p>French press. Nepotřebuje žádnou zvláštní techniku, stačí zalít, počkat a přelít.</p>
HTML,
                'title_en' => 'How to Make Coffee at Home: 4 Methods',
                'slug_en' => 'how-to-make-coffee-at-home',
                'perex_en' => 'How do you make great coffee at home without an expensive machine? A practical guide to coffee-to-water ratios, grind size, temperature and timings for filter (V60), French press, AeroPress and the moka pot.',
                'content_en' => <<<HTML
<p>Wondering <strong>how to make good coffee at home</strong> without an expensive espresso machine? Good news: all you need is fresh specialty beans, the right coffee-to-water ratio and a little practice. In this coffee brewing guide we go through the four most popular methods – filter (V60), French press, AeroPress and the moka pot – with the key numbers and a step-by-step process for each.</p>

<h2>Basic brewing rules that always apply</h2>
<p>Whichever method you choose, three things decide the result more than anything else:</p>
<ul>
<li><strong>Coffee-to-water ratio:</strong> as a starting point use roughly 60 g of coffee per 1 litre of water (i.e. 6 g per 100 ml)</li>
<li><strong>Water temperature:</strong> ideally 92–96 °C – not boiling, a moment after the kettle stops</li>
<li><strong>Freshness and grinding:</strong> grind right before brewing and choose the correct grind size</li>
</ul>
<p>Fresh, good-quality water matters too – it makes up over 98 % of the drink. Avoid very hard or heavily chlorinated water.</p>

<h2>How to brew filter coffee (V60 / pour over)</h2>
<p>Filter coffee is clean and aromatic, letting fruity and floral notes shine. It is one of the most popular ways to brew specialty coffee at home.</p>
<ul>
<li><strong>Grind size:</strong> medium-fine (like granulated sugar)</li>
<li><strong>Ratio:</strong> 15 g of coffee to 250 ml of water</li>
<li><strong>Temperature:</strong> 92–94 °C</li>
</ul>
<p><strong>Method:</strong> Rinse the filter with hot water (this removes any papery taste and preheats the vessel). Add the coffee and make a small well in the centre. First pour just a small amount of water (about twice the weight of the coffee) and let it "bloom" for 30–45 seconds – this releases carbon dioxide. Then pour the rest in slow circles. Total brew time works out to roughly 2:30–3:00 minutes.</p>

<h2>How to brew coffee in a French press</h2>
<p>The French press makes full-bodied coffee with minimal equipment. It is ideal for beginners who want to know how to brew good coffee without complicated gear.</p>
<ul>
<li><strong>Grind size:</strong> coarse</li>
<li><strong>Ratio:</strong> 30 g of coffee to 500 ml of water</li>
<li><strong>Temperature:</strong> 93–96 °C</li>
</ul>
<p><strong>Method:</strong> Add the coffee, pour in the water and stir. Let it steep for 4 minutes. Then carefully skim the foam off the surface, press the plunger down slowly and <strong>pour the coffee out immediately</strong> into a cup or carafe – otherwise it keeps extracting and turns bitter.</p>

{$ctaEn}

<h2>How to brew coffee in an AeroPress</h2>
<p>The AeroPress is a fast, versatile method with a clean taste. It is great for travelling and forgives small mistakes, which makes it rewarding to experiment with.</p>
<ul>
<li><strong>Grind size:</strong> medium-fine</li>
<li><strong>Ratio:</strong> 15 g of coffee to 220 ml of water</li>
<li><strong>Temperature:</strong> 85–92 °C</li>
</ul>
<p><strong>Method:</strong> Insert the filter, add the coffee, pour in the water and stir. After roughly 1:00–1:30 minutes fit the plunger and press slowly into your cup. Experiment with temperature and time – the AeroPress has countless recipes.</p>

<h2>How to brew coffee in a moka pot</h2>
<p>The moka pot makes strong, bold coffee close to espresso. It is a kitchen classic that needs no electricity.</p>
<ul>
<li><strong>Grind size:</strong> fine (but not as fine as for espresso)</li>
<li><strong>Ratio:</strong> fill the basket with coffee level, without tamping</li>
</ul>
<p><strong>Method:</strong> Pour hot water into the bottom chamber, just below the valve. Fill the basket with coffee and level it off (do not tamp). Close the pot and place it on a moderate flame. Once the coffee starts bubbling up, lower the flame, and the moment the sound starts to sputter, take the pot off the heat. You can cool the base under cold water so the coffee does not turn bitter.</p>

<h2>How to tune the taste to your liking</h2>
<p>Treat the numbers above as a starting point. The flavour is easy to fine-tune:</p>
<ul>
<li><strong>Coffee too bitter?</strong> Go coarser on the grind, lower the temperature or shorten the brew time.</li>
<li><strong>Coffee sour and "empty"?</strong> Try a finer grind, higher temperature or longer extraction.</li>
<li><strong>Weak and watery?</strong> Add more coffee or use less water.</li>
</ul>
<p>With gradual tweaking you will land on a cup that matches your taste exactly. And the foundation of success? Freshly roasted, freshly ground specialty coffee.</p>

<h2>Frequently asked questions</h2>
<h3>What is the best coffee-to-water ratio?</h3>
<p>A proven starting ratio is roughly 60 g of coffee per 1 litre of water (6 g per 100 ml). Adjust it to taste from there.</p>
<h3>How hot should the water be for coffee?</h3>
<p>Ideally 92–96 °C, so shortly after boiling. Boiling water scorches the coffee and makes it bitter.</p>
<h3>Which brewing method is easiest for beginners?</h3>
<p>The French press. It needs no special technique – just pour, wait and decant.</p>
HTML,
                'published_at' => '2026-07-15 09:00:00',
            ],

            // 4. Světlé vs tmavé pražení
            [
                'title_cs' => 'Světlé vs. tmavé pražení kávy: co dělá s chutí',
                'slug_cs' => 'svetle-vs-tmave-prazeni-kavy',
                'perex_cs' => 'Jak se liší světlé, střední a tmavé pražení kávy? Vysvětlíme, co se při pražení děje, jak stupeň pražení ovlivňuje chuť a kyselost a proč tmavší pražení neznamená víc kofeinu.',
                'content_cs' => <<<HTML
<p>Stupeň pražení zásadně mění charakter kávy – ze stejných zrn dokáže udělat svěží, ovocný nápoj i temně hořké espresso. Řešíte <strong>světlé vs. tmavé pražení</strong> a nevíte, které si vybrat? V tomhle článku si vysvětlíme, co se při pražení kávy děje, jak jednotlivé stupně pražení mění chuť a jak si vybrat to pravé podle způsobu přípravy i vlastní chuti.</p>

<h2>Co se při pražení kávy děje</h2>
<p>Syrová zelená zrna nemají téměř žádnou chuť a voní spíš travnatě. Teprve pražením se v nich rozvinou aromatické látky, cukry karamelizují a zrna mění barvu, objem i hmotnost. Během pražení proběhne takzvaný „first crack" (první prasknutí), kdy zrna zvětší objem, a při delším pražení i „second crack". Čím déle a intenzivněji se káva praží, tím tmavší a hořčejší výsledek.</p>
<p>Platí jednoduché pravidlo: <strong>čím světlejší pražení, tím víc vynikne původní charakter zrna</strong> (ovocnost, kyselost, květinové tóny). Čím tmavší pražení, tím víc převládne chuť samotného pražení (hořká čokoláda, karamel, pražené tóny).</p>

<h2>Světlé pražení kávy</h2>
<p>Zrna se praží kratší dobu, zůstávají světle hnědá a bez olejnatého povrchu. Světlé pražení je typické pro výběrovou kávu, kde chcete poznat původ a odrůdu.</p>
<ul>
<li><strong>Chuť:</strong> svěží, ovocná, květinová, s výraznější a příjemnou kyselostí</li>
<li><strong>Tělo:</strong> lehčí</li>
<li><strong>Hodí se pro:</strong> filtrované metody (V60, Aeropress), kde vyniknou jemné tóny</li>
</ul>

<h2>Střední pražení kávy</h2>
<p>Zlatá střední cesta – vyvážená chuť bez extrémů. Nejuniverzálnější volba, pokud si nejste jistí.</p>
<ul>
<li><strong>Chuť:</strong> vyvážená, s tóny karamelu, oříšků a mléčné čokolády</li>
<li><strong>Tělo:</strong> plnější</li>
<li><strong>Hodí se pro:</strong> univerzální použití, filtr, moka konvičku i espresso</li>
</ul>

{$cta}

<h2>Tmavé pražení kávy</h2>
<p>Zrna jsou tmavě hnědá až téměř černá, často s olejnatým povrchem. Tmavé pražení potlačí kyselost a přinese výraznou, hořkou chuť.</p>
<ul>
<li><strong>Chuť:</strong> výrazná, hořká, s tóny hořké čokolády a pražení; nízká kyselost</li>
<li><strong>Tělo:</strong> plné a těžké</li>
<li><strong>Hodí se pro:</strong> espresso a mléčné nápoje (káva „prorazí" mlékem)</li>
</ul>

<h2>Jaké pražení k jaké přípravě</h2>
<p>Stupeň pražení a metoda přípravy spolu úzce souvisí:</p>
<ul>
<li><strong>Filtr / V60 / Aeropress:</strong> světlé až střední pražení, vyniknou jemné a ovocné tóny</li>
<li><strong>Moka konvička:</strong> střední až tmavší pražení</li>
<li><strong>Espresso a mléčné nápoje:</strong> střední až tmavé pražení kvůli tělu a cremě</li>
</ul>

<h2>Mýtus: tmavší pražení = víc kofeinu</h2>
<p>Častý omyl. Tmavší pražení <strong>neznamená více kofeinu</strong> – rozdíly mezi stupni pražení jsou minimální. Silnější „kopanec" tmavé kávy je dán spíš výraznější hořkou chutí, ne obsahem kofeinu. Ten mnohem víc ovlivňuje druh kávy (arabica vs. robusta), dávka a způsob přípravy. Zajímavost: měřeno na objem, mají zrna světlého pražení dokonce nepatrně víc kofeinu, protože jsou hustší a těžší.</p>

<h2>Jak si vybrat stupeň pražení</h2>
<p>Milovníci ovocných a jemných chutí sáhnou po světlém pražení, fanoušci silného espressa po tmavém. Pokud si nejste jistí, začněte středním pražením – je nejuniverzálnější a odpustí drobné chyby v přípravě. A nezapomeňte, že stejné pražení může u různých káv chutnat úplně jinak, takže se vyplatí zkoušet a porovnávat.</p>

<h2>Časté dotazy</h2>
<h3>Má tmavé pražení víc kofeinu než světlé?</h3>
<p>Ne. Rozdíly v obsahu kofeinu mezi stupni pražení jsou minimální. Silnější chuť tmavé kávy je dána hořkostí, ne kofeinem.</p>
<h3>Které pražení je nejlepší na espresso?</h3>
<p>Nejčastěji střední až tmavé, protože dodá tělo, nižší kyselost a výraznou cremu. Řada pražíren ale nabízí i světlejší espresso profily.</p>
<h3>Proč je světlé pražení kyselejší?</h3>
<p>Kratší pražení zachová víc přírodních kyselin ze zrna. Tato kyselost je u výběrové kávy žádaná – dodává svěžest a ovocné tóny.</p>
HTML,
                'title_en' => 'Light vs. Dark Roast Coffee: What It Does to Taste',
                'slug_en' => 'light-vs-dark-roast-coffee',
                'perex_en' => 'How do light, medium and dark roast coffee differ? We explain what happens during roasting, how the roast level affects taste and acidity, and why a darker roast does not mean more caffeine.',
                'content_en' => <<<HTML
<p>The roast level fundamentally changes the character of coffee – from the same beans it can produce a fresh, fruity drink or a darkly bitter espresso. Torn between <strong>light vs. dark roast</strong> and not sure which to choose? In this article we explain what happens when coffee is roasted, how each roast level changes the flavour, and how to pick the right one for your brewing method and your palate.</p>

<h2>What happens when coffee is roasted</h2>
<p>Raw green beans have almost no flavour and smell rather grassy. Only roasting develops their aromatic compounds, caramelises the sugars and changes the beans' colour, volume and weight. During roasting there is a so-called "first crack", when the beans expand, and with longer roasting a "second crack" as well. The longer and more intensely coffee is roasted, the darker and more bitter the result.</p>
<p>A simple rule applies: <strong>the lighter the roast, the more the bean's original character shines through</strong> (fruitiness, acidity, floral notes). The darker the roast, the more the flavour of the roasting itself takes over (dark chocolate, caramel, roasted notes).</p>

<h2>Light roast coffee</h2>
<p>The beans are roasted for a shorter time, stay light brown and have no oily surface. Light roasts are typical for specialty coffee, where you want to taste the origin and the variety.</p>
<ul>
<li><strong>Taste:</strong> fresh, fruity, floral, with more pronounced and pleasant acidity</li>
<li><strong>Body:</strong> lighter</li>
<li><strong>Best for:</strong> filter methods (V60, AeroPress), where delicate notes shine</li>
</ul>

<h2>Medium roast coffee</h2>
<p>The golden middle road – balanced flavour without extremes. The most versatile choice if you are unsure.</p>
<ul>
<li><strong>Taste:</strong> balanced, with notes of caramel, nuts and milk chocolate</li>
<li><strong>Body:</strong> fuller</li>
<li><strong>Best for:</strong> all-round use – filter, moka pot and espresso alike</li>
</ul>

{$ctaEn}

<h2>Dark roast coffee</h2>
<p>The beans are dark brown to almost black, often with an oily surface. A dark roast suppresses acidity and delivers a bold, bitter flavour.</p>
<ul>
<li><strong>Taste:</strong> bold, bitter, with notes of dark chocolate and roast; low acidity</li>
<li><strong>Body:</strong> full and heavy</li>
<li><strong>Best for:</strong> espresso and milk-based drinks (the coffee cuts through the milk)</li>
</ul>

<h2>Which roast for which brewing method</h2>
<p>Roast level and brewing method are closely linked:</p>
<ul>
<li><strong>Filter / V60 / AeroPress:</strong> light to medium roast, letting delicate and fruity notes shine</li>
<li><strong>Moka pot:</strong> medium to slightly darker roast</li>
<li><strong>Espresso and milk drinks:</strong> medium to dark roast for body and crema</li>
</ul>

<h2>Myth: a darker roast means more caffeine</h2>
<p>A common misconception. A darker roast <strong>does not mean more caffeine</strong> – the differences between roast levels are minimal. The stronger "kick" of dark coffee comes from its more pronounced bitterness, not its caffeine content. Caffeine is influenced far more by the coffee species (arabica vs. robusta), the dose and the brewing method. A fun fact: measured by volume, light roast beans actually contain marginally more caffeine, because they are denser and heavier.</p>

<h2>How to choose your roast level</h2>
<p>Lovers of fruity, delicate flavours will reach for a light roast; fans of strong espresso for a dark one. If you are unsure, start with a medium roast – it is the most versatile and forgives small brewing mistakes. And remember that the same roast level can taste completely different across coffees, so it pays to try and compare.</p>

<h2>Frequently asked questions</h2>
<h3>Does dark roast have more caffeine than light roast?</h3>
<p>No. Differences in caffeine content between roast levels are minimal. The stronger taste of dark coffee comes from bitterness, not caffeine.</p>
<h3>Which roast is best for espresso?</h3>
<p>Most often medium to dark, because it delivers body, lower acidity and a rich crema. Many roasteries also offer lighter espresso profiles, though.</p>
<h3>Why is light roast more acidic?</h3>
<p>A shorter roast preserves more of the bean's natural acids. In specialty coffee this acidity is desirable – it adds freshness and fruity notes.</p>
HTML,
                'published_at' => '2026-07-22 09:00:00',
            ],

            // 5. Skladování kávy
            [
                'title_cs' => 'Jak skladovat kávu, aby zůstala čerstvá',
                'slug_cs' => 'jak-skladovat-kavu-cerstvost',
                'perex_cs' => 'Jak skladovat kávu, aby vydržela čerstvá? Poradíme, čeho se vyvarovat, v čem kávu uchovávat, jak dlouho vydrží po pražení a proč lednice ani mrazák nejsou dobrý nápad.',
                'content_cs' => <<<HTML
<p>I tu nejlepší výběrovou kávu dokáže zničit špatné skladování. Aroma zrn je totiž křehké a rychle se vytrácí. Když víte, <strong>jak skladovat kávu správně</strong>, udržíte ji čerstvou a chutnou mnohem déle. V článku si projdeme, co kávě škodí, v čem ji uchovávat, jak dlouho vydrží po pražení a proč lednice ani mrazák většinou nejsou dobrý nápad.</p>

<h2>Čtyři nepřátelé čerstvé kávy</h2>
<p>Káva stárne kvůli čtyřem faktorům. Když je omezíte, výrazně prodloužíte její čerstvost:</p>
<ul>
<li><strong>Vzduch (kyslík):</strong> způsobuje oxidaci a ztrátu aroma – největší nepřítel</li>
<li><strong>Světlo:</strong> urychluje degradaci chuťových a aromatických látek</li>
<li><strong>Vlhkost:</strong> káva je hygroskopická, snadno nasákne vlhkost i cizí pachy</li>
<li><strong>Teplo:</strong> urychluje všechny výše uvedené procesy</li>
</ul>
<p>Cílem správného skladování kávy je tyhle čtyři vlivy co nejvíc omezit.</p>

<h2>Jak skladovat kávu správně</h2>
<p>Ideální skladování kávy je jednoduché a nevyžaduje žádné speciální vybavení:</p>
<ul>
<li>Uchovávejte ji ve <strong>vzduchotěsné, neprůhledné nádobě</strong> – ideálně keramické nebo nerezové dóze s těsněním</li>
<li>Umístěte ji na <strong>tmavé, suché a chladné místo</strong> – například do spíže či skříňky, dál od sporáku a okna</li>
<li>Kupujte <strong>menší balení</strong>, která spotřebujete během pár týdnů</li>
<li>Kávu <strong>melte až těsně před přípravou</strong> – celá zrna vydrží čerstvá výrazně déle než mletá</li>
<li>Necháváte-li kávu v <strong>originálním sáčku</strong> s ventilkem, po každém použití z něj vytlačte vzduch a pečlivě ho uzavřete</li>
</ul>

<h2>V čem kávu skladovat</h2>
<p>Nejlepší je vzduchotěsná dóza z neprůhledného materiálu – keramika, nerez nebo tmavé sklo. Vyhněte se skladování v průhledných nádobách na světle a v obyčejných otevřených dózách bez těsnění. Praktický je i originální sáček s jednosměrným ventilkem, který pouští ven CO₂, ale nepouští dovnitř vzduch.</p>

{$cta}

<h2>Mýtus: patří káva do lednice nebo mrazáku?</h2>
<p>Běžné skladování v lednici <strong>nedoporučujeme</strong>. Dochází ke kondenzaci vlhkosti a káva navíc snadno nasaje pachy z ostatních potravin. Mražení má smysl jen výjimečně – u větších zásob, které dlouho nespotřebujete. Pak platí přísná pravidla: kávu rozdělte na menší porce, zabalte vzduchotěsně, a rozmrazujte jen jednou a celou porci, bez opakovaného vytahování z mrazáku. Pro běžnou spotřebu je ale nejlepší pokojová teplota v uzavřené dóze.</p>

<h2>Jak dlouho káva vydrží čerstvá</h2>
<p>Nejlepší chuť má káva zhruba <strong>2 až 6 týdnů po pražení</strong>. Prvních pár dní po pražení se z čerstvé kávy ještě uvolňuje oxid uhličitý (káva „odpočívá"), pak nastává ideální chuťové okno. Po otevření balení se snažte zrna spotřebovat zhruba do měsíce.</p>
<ul>
<li><strong>Celá zrna:</strong> nejlepší 2–6 týdnů po pražení, po otevření do měsíce</li>
<li><strong>Mletá káva:</strong> aroma ztrácí nejrychleji – ideálně spotřebovat během pár dní</li>
</ul>
<p>Proto se vyplatí kupovat čerstvě praženou kávu pravidelně a v menším množství, místo velké zásoby, která vám doma zvětrá. Zrna sice po datu neztuchnou nebezpečně, ale postupně ztrácejí to nejlepší – vůni a plnou chuť.</p>

<h2>Časté dotazy</h2>
<h3>Má se káva skladovat v lednici?</h3>
<p>Ne. V lednici hrozí kondenzace vlhkosti a káva nasaje pachy. Lepší je vzduchotěsná dóza při pokojové teplotě na tmavém místě.</p>
<h3>Jak dlouho vydrží káva po otevření?</h3>
<p>Celá zrna zhruba měsíc, mletá káva ideálně jen pár dní. Rozhoduje čerstvost a správné uzavření obalu.</p>
<h3>Dá se káva zmrazit?</h3>
<p>Ano, ale jen pro dlouhodobé zásoby, ve vzduchotěsném obalu a bez opakovaného rozmrazování. Pro běžné pití to nemá smysl.</p>
HTML,
                'title_en' => 'How to Store Coffee to Keep It Fresh',
                'slug_en' => 'how-to-store-coffee-fresh',
                'perex_en' => 'How should you store coffee to keep it fresh? We cover what to avoid, what to keep coffee in, how long it lasts after roasting, and why the fridge and freezer are usually a bad idea.',
                'content_en' => <<<HTML
<p>Even the finest specialty coffee can be ruined by poor storage. The aroma of the beans is fragile and fades quickly. Once you know <strong>how to store coffee properly</strong>, you will keep it fresh and tasty for far longer. In this article we go through what harms coffee, what to keep it in, how long it lasts after roasting, and why the fridge and freezer are usually a bad idea.</p>

<h2>The four enemies of fresh coffee</h2>
<p>Coffee ages because of four factors. Limit them and you significantly extend its freshness:</p>
<ul>
<li><strong>Air (oxygen):</strong> causes oxidation and aroma loss – the biggest enemy</li>
<li><strong>Light:</strong> accelerates the degradation of flavour and aromatic compounds</li>
<li><strong>Moisture:</strong> coffee is hygroscopic and readily absorbs humidity and foreign odours</li>
<li><strong>Heat:</strong> accelerates all of the processes above</li>
</ul>
<p>The goal of good coffee storage is to limit these four influences as much as possible.</p>

<h2>How to store coffee properly</h2>
<p>Ideal coffee storage is simple and needs no special equipment:</p>
<ul>
<li>Keep it in an <strong>airtight, opaque container</strong> – ideally a ceramic or stainless steel canister with a seal</li>
<li>Place it somewhere <strong>dark, dry and cool</strong> – a pantry or cupboard, away from the stove and window</li>
<li>Buy <strong>smaller bags</strong> that you will finish within a few weeks</li>
<li><strong>Grind the coffee right before brewing</strong> – whole beans stay fresh far longer than ground</li>
<li>If you keep the coffee in its <strong>original bag</strong> with a valve, squeeze the air out after each use and seal it carefully</li>
</ul>

<h2>What to store coffee in</h2>
<p>The best option is an airtight container made of an opaque material – ceramic, stainless steel or dark glass. Avoid storing coffee in transparent containers in the light, or in ordinary open jars without a seal. The original bag with a one-way valve is practical too: it lets CO₂ out but keeps air from getting in.</p>

{$ctaEn}

<h2>Myth: should coffee go in the fridge or freezer?</h2>
<p>We <strong>do not recommend</strong> everyday storage in the fridge. Moisture condenses and the coffee easily absorbs odours from other food. Freezing makes sense only in exceptional cases – for larger stocks you will not use for a long time. Then strict rules apply: divide the coffee into smaller portions, wrap them airtight, and thaw only once and a whole portion at a time, without repeatedly taking it out of the freezer. For everyday use, room temperature in a sealed canister is best.</p>

<h2>How long coffee stays fresh</h2>
<p>Coffee tastes best roughly <strong>2 to 6 weeks after roasting</strong>. For the first few days after roasting the coffee is still releasing carbon dioxide (it is "resting"), and then the ideal flavour window opens. Once the bag is open, try to finish the beans within about a month.</p>
<ul>
<li><strong>Whole beans:</strong> best 2–6 weeks after roasting, within a month of opening</li>
<li><strong>Ground coffee:</strong> loses aroma fastest – ideally use within a few days</li>
</ul>
<p>This is why it pays to buy freshly roasted coffee regularly and in smaller amounts, instead of a large stock that goes stale at home. Beans will not spoil dangerously past their date, but they gradually lose the best part – the aroma and full flavour.</p>

<h2>Frequently asked questions</h2>
<h3>Should coffee be stored in the fridge?</h3>
<p>No. In the fridge there is a risk of condensation and the coffee absorbs odours. An airtight canister at room temperature in a dark place is better.</p>
<h3>How long does coffee last once opened?</h3>
<p>Whole beans about a month, ground coffee ideally just a few days. Freshness and properly sealing the bag are what matter.</p>
<h3>Can you freeze coffee?</h3>
<p>Yes, but only for long-term stocks, in airtight packaging and without repeated thawing. For everyday drinking there is no point.</p>
HTML,
                'published_at' => '2026-07-29 09:00:00',
            ],
        ];
    }
}
