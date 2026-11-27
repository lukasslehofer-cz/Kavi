<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Káva mesiaca (/kava-mesiaca)
    |--------------------------------------------------------------------------
    |
    | :month = názov mesiaca s rokom v nominatíve (napr. „December 2026“),
    | preto texty používajú tvar „na :month“ (akuzatív = nominatív), nie „v :month“.
    |
    */

    'title' => 'Káva mesiaca :month',
    'meta_description' => 'Objavte náš výber káv na :month. Výnimočné výberové kávy z najlepších európskych pražiarní, čerstvo pražené a doručené až k vám.',
    'og_title' => 'Káva mesiaca :month | :site',
    'og_description' => 'Každý mesiac pre vás vyberáme výnimočné kávy z najlepších európskych pražiarní. Objavte, čo pre vás chystáme na :month.',

    'heading_line1' => 'Pražiarne a kávy',
    'heading_line2' => 'mesiaca',
    'intro' => 'Každý mesiac pre vás vyberáme výnimočné kávy z najlepších európskych pražiarní. Objavte, čo vás čaká.',

    // Pražiarne mesiaca
    'roasteries' => [
        'heading' => '{0} Pražiarne mesiaca|{1} Pražiareň mesiaca|[2,*] Pražiarne mesiaca',
        'subheading' => '{0} Tentoraz sme vybrali kávy od týchto výnimočných pražiarní|{1} Tentoraz sme vybrali kávy od tejto výnimočnej pražiarne|[2,*] Tentoraz sme vybrali kávy od týchto výnimočných pražiarní',
        'more_about' => 'Viac o pražiarni',
    ],

    // Kávy mesiaca a okno s detailom kávy
    'coffees' => [
        'heading' => 'Kávy mesiaca',
        'subheading' => 'Tieto výberové kávy sú súčasťou nášho aktuálneho predplatného',
    ],

    'modal' => [
        'badge' => 'Káva mesiaca',
        'parameters' => 'Parametre',
        'attributes' => [
            'origin' => 'Pôvod',
            'altitude' => 'Nadmorská výška',
            'processing' => 'Spracovanie',
            'variety' => 'Odroda',
            'flavor_notes' => 'Chuťové tóny',
        ],
        'weight' => 'Hmotnosť',
        'roast_date' => 'Dátum praženia',
        'roast_date_format' => 'd.m.Y',
        'cta_heading' => 'Súčasť aktuálneho predplatného',
        'cta_text' => 'Získajte ju spolu s ďalšími výberovými kávami',
        'cta_link' => 'Zistiť viac',
    ],

    // Výber ešte nie je zverejnený
    'empty' => [
        'heading' => 'Kávu mesiaca pripravujeme',
        'text' => 'Práve vyberáme výnimočné kávy na :month. Čoskoro vás prekvapíme!',
    ],

    'learn_more_subscription' => 'Zistiť viac o predplatnom',

    // Spodná výzva k predplatnému
    'cta' => [
        'heading' => 'Chcete dostávať podobné kávy pravidelne?',
        'text' => 'Prihláste sa na naše predplatné a box s vybranými kávovými špecialitami na vás bude pravidelne čakať na výdajnom mieste.',
    ],

];
