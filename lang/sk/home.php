<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Homepage Translations
    |--------------------------------------------------------------------------
    */

    // Meta značky úvodnej stránky (home.blade.php)
    'meta' => [
        'title' => 'Kávové predplatné | Výberová káva | KAVIbox.sk',
        'description' => 'Objavte tú najlepšiu výberovú kávu z celej Európy. Čerstvo pražená, starostlivo vybraná, doručená priamo k vám. Flexibilné predplatné, balené s láskou.',
        'og_title' => 'KAVIbox.sk - Kávové predplatné s výberovou kávou z Európy',
        'og_description' => 'Prémiová výberová káva doručovaná každý mesiac. Objavte jedinečné chute z najlepších európskych pražiarní. Starostlivo zabalená, zrušenie kedykoľvek.',
    ],

    // Štruktúrované dáta (JSON-LD) úvodnej stránky
    'schema' => [
        'description' => 'Prémiové kávové predplatné s výberovou kávou. Čerstvo pražená káva z najlepších európskych pražiarní doručená priamo k vám.',
        'language' => 'Slovak',
        'in_language' => 'sk-SK',
    ],

    'hero' => [
        'badge' => 'Každý mesiac niečo nové',
        'title' => 'Objavte tú najlepšiu kávu z celej Európy',
        'subtitle' => 'Výberová káva s pravidelným predplatným. Čerstvo pražená, starostlivo vybraná, doručená priamo k vám.',
        'cta_primary' => 'Zostavte si vlastný box',
        'cta_secondary' => 'Kávový obchod',

        // Hero s videom (redesign 2026-10)
        'tagline' => 'Predplatné výberovej kávy z najlepších európskych pražiarní.',
        'kicker' => 'Kávové predplatné',
        'headline_top' => 'Tri európske',
        'headline_accent' => 'pražiarne',
        'headline_bottom' => 'každý mesiac',
        'lead' => 'Výberová káva z mikropražiarní, ktoré v bežných obchodoch len tak nenájdete. Bez záväzku.',
        'cta_box' => 'Zostaviť box',
        'cta_gift' => 'Darovať predplatné',
        'cta_shop' => 'Obchod',
    ],

    // Sekcia „Čo vám príde domov“
    'box' => [
        'image_alt' => 'Otvorený KAVI box s tromi balíčkami kávy',
        'title' => 'Čo vám príde domov',
        'lead' => 'Zrnková káva z európskych pražiarní, čerstvo pražená a doručená na výdajné miesto.',
        'items' => [
            '2 až 4 balíčky po 250 g podľa veľkosti boxu',
            'Kávy z troch európskych pražiarní, každý mesiac iných',
            'Pohľadnica s profilom kávy a pražiarne ku každej káve',
            'Doručenie cez Packetu, pauza alebo zrušenie jedným klikom',
        ],
        'cta_box' => 'Zostaviť box',
        'cta_coffee_of_month' => 'Káva mesiaca',
    ],

    // Sekcia „Ako to funguje“
    'how' => [
        'title' => 'Ako to funguje',
        'subtitle' => 'Štyri jednoduché kroky k dokonalej káve',
        'steps' => [
            1 => ['title' => 'Vyberte si plán', 'text' => 'Zvoľte množstvo kávy a frekvenciu doručenia podľa vašich potrieb'],
            2 => ['title' => 'Personalizujte', 'text' => 'Vyberte typ kávy, spôsob prípravy a doručovaciu adresu'],
            3 => ['title' => 'Vyzdvihnite si box', 'text' => 'Čerstvo praženú kávu doručíme na vami vybrané miesto'],
            4 => ['title' => 'Vychutnajte si', 'text' => 'Užite si skvelú výberovú kávu a tešte sa na ďalší kávový box'],
        ],
    ],

    // Sekcia „Darujte kávu“
    'gifts' => [
        'aria' => 'Darčeky',
        'season' => 'Vianoce :year',
        'title' => 'Darujte',
        'title_accent' => 'kávu',
        'text' => 'Certifikát príde hneď e-mailom v PDF na tlač:season_note. Platíte raz, nič sa samo neobnoví.',
        'season_note' => ', stihnete ho aj na Štedrý deň',
        'from' => 'od :price',
        'or' => 'alebo',
        'months_3' => '3 mesiace',
        'months_3_desc' => '3 boxy, jeden každý mesiac',
        'months_6' => '6 mesiacov',
        'months_6_desc' => '6 boxov, jeden každý mesiac',
        'voucher' => 'Poukážka',
        'voucher_desc' => ':amounts na e-shop aj boxy',
        // Parameter odkazu na /darceky: len hodnoty, ktoré pozná GiftController::index (typ|type, poukaz|voucher)
        'query_param' => 'typ',
        'query_voucher' => 'poukaz',
    ],

    'features' => [
        'title' => 'Prečo si vybrať KAVI?',
        'subtitle' => 'Vieme, čo robí kávu výnimočnou. A radi sa s vami podelíme.',

        'fresh_title' => 'Čerstvá káva',
        'fresh_desc' => 'Každá pražiareň nám dodáva čerstvo praženú kávu do vašich kávových boxov.',

        'flavors_title' => 'Výnimočné chute',
        'flavors_desc' => 'Žiadne nudné a monotónne kávy. Každý mesiac objavíte nové chute z rôznych kútov sveta.',

        'shipping_title' => 'Balené s láskou',
        'shipping_desc' => 'Čerstvo pražená, s láskou zabalená naším tímom a doručená až k vám.',
    ],

    'love_coffee' => [
        'title' => 'Káva, ktorú si zamilujete',
        'subtitle' => 'Starostlivo vyberáme najkvalitnejšiu kávu z overených pražiarní. Každý mesiac objavte nové chute priamo u vás doma.',

        'feature1_title' => 'Exkluzívny výber',
        'feature1_desc' => 'Prinášame vám kávu z pražiarní, ktoré u vás bežne nekúpite.',

        'feature2_title' => 'Bezkonkurenčná cena',
        'feature2_desc' => 'Kávy v našich boxoch sú výhodnejšie ako kdekoľvek inde.',

        'feature3_title' => 'Flexibilné predplatné',
        'feature3_desc' => 'Prispôsobte si množstvo a frekvenciu podľa svojich potrieb.',

        'cta' => 'Začať predplatné',
    ],

    'plans' => [
        'title' => 'Vyberte si ideálny kávový box',
        'subtitle' => 'Flexibilné predplatné prispôsobené vašim potrebám. Zrušte kedykoľvek bez poplatku.',
        'popular' => 'Najobľúbenejší',
        'select' => 'Vybrať',
        'note' => 'Ďalšie nastavenia vášho kávového predplatného nasledujú v ďalšom kroku.',
    ],

    'testimonials' => [
        'title' => 'Čo hovoria naši zákazníci',
        'subtitle' => 'Pridajte sa k mnohým spokojným milovníkom kávy',
    ],

    'impact' => [
        'title' => 'Káva s pozitívnym dopadom',
        'subtitle' => 'V KAVI veríme, že výberová káva môže meniť svet. Z každého kávového boxu preto prispievame organizácii Water.org, ktorá zabezpečuje prístup k čistej vode v rozvojových krajinách. Každý box tak poskytne jednému človeku vodu na pol roka.',
        'water_stat' => '6 mesiacov',
        'water_label' => 'vody za jeden box',
        'partner_label' => 'darujeme priamo',

        // Sekcia Water.org na úvodnej stránke (redesign 2026-10)
        'mission' => 'V KAVI veríme, že výberová káva môže meniť svet. Z každého kávového boxu prispievame organizácii Water.org, ktorá zabezpečuje prístup k čistej vode v rozvojových krajinách.',
        'headline' => 'Každý box poskytne jednému človeku vodu na pol roka',
        'water_months' => '6 mesiacov vody',
        'direct' => 'Priama podpora',
    ],

    'cta_final' => [
        'title' => 'Začnite svoju kávovú cestu ešte dnes',
        'subtitle' => 'Získajte prístup k najlepšej káve z celej Európy. Flexibilné predplatné, bez záväzkov.',
        'primary' => 'Vybrať predplatné',
        'secondary' => 'Prezrieť si kávy',
    ],

    // Časté otázky na homepage (redesign 2026-10). :cutoff a :shipment doplní
    // HomeController z aktuálneho cyklu v ShipmentSchedule.
    'faq' => [
        ['q' => 'Kedy mi príde prvý box?', 'a' => 'Objednávky uzatvárame :cutoff o polnoci a odosielame :shipment cez Packetu na výdajné miesto. Ďalšie boxy potom vždy okolo 20. dňa v mesiaci.'],
        ['q' => 'Môžem predplatné pozastaviť alebo zrušiť?', 'a' => 'Áno, kedykoľvek v účte a bez poplatkov. Zmena platí pre box, ktorého uzávierka ešte neprebehla.'],
        ['q' => 'Aké kávy dostanem?', 'a' => 'Každý mesiac kávy z troch európskych pražiarní. Pražiareň sa vracia zhruba raz za rok, takže sa kávy opakujú málokedy.'],
        ['q' => 'Espresso, alebo filter?', 'a' => 'Espresso má plné telo a tmavšie praženie, filter ľahšie telo a svetlejšie praženie. V kombinácii dostanete oboje.'],
        ['q' => 'Môžem predplatné darovať?', 'a' => 'Áno, na 3 alebo 6 mesiacov vopred, bez automatického obnovenia.', 'link' => ['label' => 'Darčekové predplatné', 'route' => 'gifts.index']],
        // Len kavi.cz: odkaz aj otázka sa skryjú, keď stránka Pro firmy nie je (HomeController::faq)
        ['q' => 'Ponúkate darčeky pre firmy?', 'a' => 'Áno, od :min kusov s logom vašej firmy na páske boxu a kartičkou s venovaním. Množstevná zľava až :max %, faktúra na firmu.', 'link' => ['label' => 'Firemné darčeky', 'route' => 'corporate.index']],
    ],

];
