<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Layouty a partials (layouts/app, layouts/dashboard, partials/*)
    |--------------------------------------------------------------------------
    */

    // Výchozí meta značky, stránky je přepisují vlastními sekcemi
    'meta' => [
        'title' => 'Kávové předplatné | Výběrová káva | KAVI.cz',
        'description' => 'Prémiové kávové předplatné s výběrovou kávou. Čerstvě pražená káva z nejlepších evropských pražíren doručená přímo k vám domů.',
        'og_title' => 'KAVI.cz - Kávové předplatné s výběrovou kávou',
        'dashboard_title' => 'Dashboard - KAVI.cz',
    ],

    // Profil webu na Instagramu (patička, JSON-LD na úvodní stránce)
    'social' => [
        'instagram' => 'https://www.instagram.com/kavi.cz',
    ],

    // Hlavní menu (desktop i mobil)
    'nav' => [
        'home' => 'Úvod',
        'home_mobile' => 'Domů',
        'logo_home' => 'KAVI.cz, úvodní stránka',
        'boxes' => 'Kávové boxy',
        'coffee_of_month' => 'Káva měsíce',
        'shop' => 'Obchod',
        'roasteries' => 'Naše pražírny',
        'gifts' => 'Dárky',
        'account' => 'Účet',
        'my_account' => 'Můj účet',
        'sign_in' => 'Přihlásit',
        'sign_in_mobile' => 'Přihlásit se',
        'register' => 'Registrovat',
        'sign_out' => 'Odhlásit se',
        'cart' => 'Košík',
        'build_box' => 'Sestavte si box',
    ],

    'footer' => [
        'tagline' => 'Výběrová káva s pravidelnými dodávkami přímo k vám. Objevte svět chutí z nejlepších pražíren Evropy.',
        'subscription' => 'Předplatné',
        'configurator' => 'Konfigurátor',
        'one_time_box' => 'Jednorázový box',
        'gift_subscription' => 'Dárkové předplatné',
        'coffee_of_month' => 'Káva měsíce',
        'shop' => 'Obchod',
        'all_products' => 'Všechny produkty',
        'espresso' => 'Espresso káva',
        'filter' => 'Filtrovaná káva',
        'decaf' => 'Bezkofeinová káva',
        'guide' => 'Průvodce kávou',
        // Interní odkazy na články, které chceme posílit: slug článku v jazyce webu => text odkazu
        'guide_articles' => [
            'vyberova-kava-vs-klasicka-kava' => 'Výběrová vs. klasická káva',
            'arabica-vs-robusta-rozdil' => 'Arabica vs. robusta',
            'jak-pripravit-espresso' => 'Jak připravit espresso doma',
            'jak-pripravit-kavu-doma' => 'Jak připravit kávu doma',
        ],
        'all_articles' => 'Všechny články',
        'information' => 'Informace',
        'how_it_works' => 'Jak to funguje',
        'about' => 'O nás',
        'contact' => 'Kontakt',
        'write_us' => 'Napište nám',
        'rights' => 'Všechna práva vyhrazena.',
        'terms' => 'Obchodní podmínky',
        'privacy' => 'Ochrana osobních údajů',
    ],

    'newsletter' => [
        'title' => 'Přihlaste se k odběru novinek',
        'text' => 'Buďte první, kdo se dozví o nových kávách a novinkách ze světa výběrové kávy',
        'text_dashboard' => 'Získejte slevu 10% na první objednávku a buďte první, kdo se dozví o nových kávách',
        'placeholder' => 'Váš e-mail',
        'submit' => 'Odebírat',
        'sending' => 'Odesílám...',
        'error' => 'Došlo k chybě. Zkuste to prosím znovu.',
    ],

    // Zákaznický účet (layouts/dashboard)
    'dashboard' => [
        'viewing_as' => 'Prohlížíte dashboard uživatele',
        'back_to_admin' => 'Zpět do admin panelu',
        'nav' => [
            'overview' => 'Nástěnka',
            'profile' => 'Můj profil',
            'orders' => 'Objednávky',
            'subscription' => 'Předplatné',
            'affiliate' => 'Affiliate',
        ],
    ],

];
