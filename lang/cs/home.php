<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Homepage Translations
    |--------------------------------------------------------------------------
    */

    // Meta značky úvodní stránky (home.blade.php)
    'meta' => [
        'title' => 'Kávové předplatné | Výběrová káva | KAVI.cz',
        'description' => 'Objevte tu nejlepší výběrovou kávu z celé Evropy. Čerstvě pražená, pečlivě vybraná, doručená přímo k vám. Flexibilní předplatné s dopravou zdarma.',
        'og_title' => 'KAVI.cz - Kávové předplatné s výběrovou kávou z Evropy',
        'og_description' => 'Prémiová výběrová káva doručovaná měsíčně. Objevte jedinečné chutě z nejlepších evropských pražíren. Doprava zdarma, zrušení kdykoliv.',
    ],

    // Strukturovaná data (JSON-LD) úvodní stránky
    'schema' => [
        'description' => 'Prémiové kávové předplatné s výběrovou kávou. Čerstvě pražená káva z nejlepších evropských pražíren doručená přímo k vám.',
        'language' => 'Czech',
        'in_language' => 'cs-CZ',
    ],

    'hero' => [
        'badge' => 'Každý měsíc něco nového',
        'title' => 'Objevte tu nejlepší kávu z celé Evropy',
        'subtitle' => 'Výběrová káva s pravidelným předplatným. Čerstvě pražená, pečlivě vybraná, doručená přímo k vám.',
        'cta_primary' => 'Sestavte si vlastní box',
        'cta_secondary' => 'Kávový obchod',

        // Hero s videem (redesign 2026-10)
        'tagline' => 'Předplatné výběrové kávy z nejlepších evropských pražíren.',
        'kicker' => 'Kávové předplatné',
        'headline_top' => 'Tři evropské',
        'headline_accent' => 'pražírny',
        'headline_bottom' => 'každý měsíc',
        'lead' => 'Výběrová káva z mikropražíren, které v Česku běžně nekoupíte. Doprava zdarma, bez závazku.',
        'cta_box' => 'Sestavit box',
        'cta_gift' => 'Darovat předplatné',
        'cta_shop' => 'Obchod',
    ],

    // Sekce „Co přijde domů“
    'box' => [
        'image_alt' => 'Otevřený KAVI box se třemi balíčky kávy',
        'title' => 'Co přijde domů',
        'lead' => 'Zrnková káva z evropských pražíren, čerstvě pražená a doručená na výdejní místo.',
        'items' => [
            '2 až 4 balíčky po 250 g podle velikosti boxu',
            'Kávy ze tří evropských pražíren, každý měsíc jiných',
            'Pohlednice s profilem kávy a pražírny ke každé kávě',
            'Doprava Zásilkovnou zdarma, pauza nebo zrušení jedním klikem',
        ],
        'cta_box' => 'Sestavit box',
        'cta_coffee_of_month' => 'Káva měsíce',
    ],

    // Sekce „Jak to funguje“
    'how' => [
        'title' => 'Jak to funguje',
        'subtitle' => 'Čtyři jednoduché kroky k perfektní kávě',
        'steps' => [
            1 => ['title' => 'Vyberte si plán', 'text' => 'Zvolte množství kávy a frekvenci dodání podle vašich potřeb'],
            2 => ['title' => 'Personalizujte', 'text' => 'Vyberte typ kávy, způsob přípravy a doručovací adresu'],
            3 => ['title' => 'Vyzvedněte si box', 'text' => 'Čerstvě praženou kávu doručíme na vámi vybrané místo'],
            4 => ['title' => 'Vychutnejte si', 'text' => 'Užijte si skvělou výběrovou kávu a těšte se na další kávový box'],
        ],
    ],

    // Sekce „Darujte kávu“
    'gifts' => [
        'aria' => 'Dárky',
        'season' => 'Vánoce :year',
        'title' => 'Darujte',
        'title_accent' => 'kávu',
        'text' => 'Certifikát přijde hned e-mailem v PDF k tisku:season_note. Platíte jednou, nic se samo neobnoví.',
        'season_note' => ', stihnete ho i na Štědrý den',
        'from' => 'od :price',
        'or' => 'nebo',
        'months_3' => '3 měsíce',
        'months_3_desc' => '3 boxy, jeden každý měsíc',
        'months_6' => '6 měsíců',
        'months_6_desc' => '6 boxů, jeden každý měsíc',
        'voucher' => 'Poukaz',
        'voucher_desc' => ':amounts na e-shop i boxy',
        // Parametr odkazu na /darky: jen hodnoty, které zná GiftController::index (typ|type, poukaz|voucher)
        'query_param' => 'typ',
        'query_voucher' => 'poukaz',
    ],
    
    'features' => [
        'title' => 'Proč si vybrat KAVI?',
        'subtitle' => 'Víme, co dělá kávu výjimečnou. A rádi se s vámi podělíme.',
        
        'fresh_title' => 'Čerstvá káva',
        'fresh_desc' => 'Každá pražírna nám dodává čerstvě praženou kávu do vašich kávových boxů.',
        
        'flavors_title' => 'Výjimečné chutě',
        'flavors_desc' => 'Žádné nudné a monotonní kávy. Každý měsíc objevíte nové chutě z různých koutů světa.',
        
        'shipping_title' => 'Doprava zdarma',
        'shipping_desc' => 'Doprava zdarma pro všechna předplatná. Ať už si vyberete jakoukoliv velikost boxu.',
    ],
    
    'love_coffee' => [
        'title' => 'Káva, kterou budete milovat',
        'subtitle' => 'Pečlivě vybíráme nejkvalitnější kávu z ověřených pražíren. Každý měsíc objevte nové chutě přímo u vás doma.',
        
        'feature1_title' => 'Nikde v ČR nekoupíte',
        'feature1_desc' => 'Dodáváme vám kávu z pražíren, které nejsou dostupné v ČR.',
        
        'feature2_title' => 'Bezkonkurenční cena',
        'feature2_desc' => 'Kávy v našich boxech jsou výhodnější než kdekoliv jinde.',
        
        'feature3_title' => 'Flexibilní předplatné',
        'feature3_desc' => 'Přizpůsobte množství a frekvenci podle svých potřeb.',
        
        'cta' => 'Začít předplatné',
    ],
    
    'plans' => [
        'title' => 'Vyberte si ideální kávový box',
        'subtitle' => 'Flexibilní předplatné přizpůsobené vašim potřebám. Zrušte kdykoliv bez poplatku.',
        'popular' => 'Nejoblíbenější',
        'select' => 'Vybrat',
        'note' => 'Další nastavení vašeho kávového předplatného následuje v dalším kroku.',
    ],
    
    'testimonials' => [
        'title' => 'Co říkají naši zákazníci',
        'subtitle' => 'Přidejte se k řadě spokojených milovníků kávy',
    ],
    
    'impact' => [
        'title' => 'Káva s pozitivním dopadem',
        'subtitle' => 'V KAVI věříme, že výběrová káva může měnit svět. Z každého kávového boxu proto věnujeme 5 Kč organizaci Water.org, která zajišťuje přístup k čisté vodě v rozvojových zemích. Každý box tak poskytne jednomu člověku vodu na půl roku.',
        'water_stat' => '6 měsíců',
        'water_label' => 'vody za jeden box',
        'partner_label' => 'darujeme napřímo',

        // Sekce Water.org na úvodní stránce (redesign 2026-10)
        'mission' => 'V KAVI věříme, že výběrová káva může měnit svět. Z každého kávového boxu věnujeme 5 Kč organizaci Water.org, která zajišťuje přístup k čisté vodě v rozvojových zemích.',
        'headline' => 'Každý box poskytne jednomu člověku vodu na půl roku',
        'water_months' => '6 měsíců vody',
        'direct' => 'Přímá podpora',
    ],
    
    'cta_final' => [
        'title' => 'Začněte svou kávovou cestu ještě dnes',
        'subtitle' => 'Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků.',
        'primary' => 'Vybrat předplatné',
        'secondary' => 'Procházet kávy',
    ],

    // Časté dotazy na homepage (redesign 2026-10). :cutoff a :shipment doplní
    // HomeController z aktuálního cyklu v ShipmentSchedule.
    'faq' => [
        ['q' => 'Kdy mi přijde první box?', 'a' => 'Objednávky uzavíráme :cutoff o půlnoci a rozesíláme :shipment, Zásilkovnou na výdejní místo. Další boxy pak vždy kolem 20. dne v měsíci.'],
        ['q' => 'Mohu předplatné pozastavit nebo zrušit?', 'a' => 'Ano, kdykoli v účtu a bez poplatků. Změna platí pro box, jehož uzávěrka ještě neproběhla.'],
        ['q' => 'Jaké kávy dostanu?', 'a' => 'Každý měsíc kávy ze tří evropských pražíren. Pražírna se vrací zhruba jednou za rok, takže se kávy opakují málokdy.'],
        ['q' => 'Espresso, nebo filtr?', 'a' => 'Espresso má plné tělo a tmavší pražení, filtr lehčí tělo a světlejší pražení. Kombinace pošle obojí.'],
        ['q' => 'Mohu předplatné darovat?', 'a' => 'Ano, na 3 nebo 6 měsíců předem, bez automatického obnovení.', 'link' => ['label' => 'Dárkové předplatné', 'route' => 'gifts.index']],
        // Jen kavi.cz: odkaz i otázka se schovají, když stránka Pro firmy není (HomeController::faq)
        ['q' => 'Nabízíte dárky pro firmy?', 'a' => 'Ano, od :min kusů s logem vaší firmy na pásce boxu a kartičkou se vzkazem. Množstevní sleva až :max %, faktura na firmu.', 'link' => ['label' => 'Firemní dárky', 'route' => 'corporate.index']],
    ],

];
