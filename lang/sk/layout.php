<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Layouty a partials (layouts/app, layouts/dashboard, partials/*)
    |--------------------------------------------------------------------------
    */

    // Predvolené meta značky, stránky ich prepisujú vlastnými sekciami
    'meta' => [
        'title' => 'Kávové predplatné | Výberová káva | KAVIbox.sk',
        'description' => 'Prémiové kávové predplatné s výberovou kávou. Čerstvo pražená káva z najlepších európskych pražiarní doručená priamo k vám domov.',
        'og_title' => 'KAVIbox.sk - Kávové predplatné s výberovou kávou',
        'dashboard_title' => 'Dashboard - KAVIbox.sk',
    ],

    // Profil webu na Instagrame (päta, JSON-LD na úvodnej stránke)
    'social' => [
        'instagram' => 'https://www.instagram.com/kavi.cz',
    ],

    // Hlavné menu (desktop aj mobil)
    'nav' => [
        'home' => 'Úvod',
        'home_mobile' => 'Domov',
        'logo_home' => 'KAVIbox.sk, úvodná stránka',
        'boxes' => 'Kávové boxy',
        'coffee_of_month' => 'Káva mesiaca',
        'shop' => 'Obchod',
        'roasteries' => 'Naše pražiarne',
        'gifts' => 'Darčeky',
        'account' => 'Účet',
        'my_account' => 'Môj účet',
        'sign_in' => 'Prihlásiť',
        'sign_in_mobile' => 'Prihlásiť sa',
        'register' => 'Registrovať',
        'sign_out' => 'Odhlásiť sa',
        'cart' => 'Košík',
        'build_box' => 'Zostavte si box',
    ],

    'footer' => [
        'tagline' => 'Výberová káva s pravidelnými dodávkami priamo k vám. Objavte svet chutí z najlepších pražiarní Európy.',
        'subscription' => 'Predplatné',
        'configurator' => 'Konfigurátor',
        'one_time_box' => 'Jednorazový box',
        'gift_subscription' => 'Darčekové predplatné',
        'coffee_of_month' => 'Káva mesiaca',
        'shop' => 'Obchod',
        'all_products' => 'Všetky produkty',
        'espresso' => 'Espresso káva',
        'filter' => 'Filtrovaná káva',
        'decaf' => 'Bezkofeínová káva',
        'guide' => 'Sprievodca kávou',
        // Interné odkazy na články, ktoré chceme posilniť: slug článku v jazyku webu => text odkazu
        'guide_articles' => [
            'vyberova-kava-vs-klasicka-kava' => 'Výberová vs. klasická káva',
            'arabica-vs-robusta-aky-je-rozdiel' => 'Arabica vs. robusta',
            'ako-pripravit-espresso-doma-navod-pre-pakovy-kavovar' => 'Ako pripraviť espresso doma',
            'ako-pripravit-kavu-doma-navod-na-4-metody' => 'Ako pripraviť kávu doma',
        ],
        'all_articles' => 'Všetky články',
        'information' => 'Informácie',
        'how_it_works' => 'Ako to funguje',
        'about' => 'O nás',
        'contact' => 'Kontakt',
        'write_us' => 'Napíšte nám',
        'rights' => 'Všetky práva vyhradené.',
        'terms' => 'Obchodné podmienky',
        'privacy' => 'Ochrana osobných údajov',
    ],

    'newsletter' => [
        'title' => 'Prihláste sa na odber noviniek',
        'text' => 'Buďte medzi prvými, ktorí sa dozvedia o nových kávach a novinkách zo sveta výberovej kávy',
        'text_dashboard' => 'Získajte zľavu 10% na prvú objednávku a buďte medzi prvými, ktorí sa dozvedia o nových kávach',
        'placeholder' => 'Váš e-mail',
        'submit' => 'Odoberať',
        'sending' => 'Odosielam...',
        'error' => 'Došlo k chybe. Skúste to prosím znova.',
    ],

    // Zákaznícky účet (layouts/dashboard)
    'dashboard' => [
        'viewing_as' => 'Prezeráte si dashboard používateľa',
        'back_to_admin' => 'Späť do admin panelu',
        'nav' => [
            'overview' => 'Nástenka',
            'profile' => 'Môj profil',
            'orders' => 'Objednávky',
            'subscription' => 'Predplatné',
            'affiliate' => 'Affiliate',
        ],
    ],

];
