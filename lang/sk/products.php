<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Obchod: výpis produktov a detail produktu
    |--------------------------------------------------------------------------
    |
    | Názvy, popisy a parametre produktov sú v databáze (gettery modelu Product).
    |
    */

    'shop' => 'Obchod',

    'categories' => [
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'decaf' => 'Bezkofeínová',
        'accessories' => 'Príslušenstvo',
    ],

    'pagination' => [
        'page' => 'Strana',
        'previous' => 'Predchádzajúca',
        'next' => 'Ďalšia',
    ],

    // Spodná výzva k predplatnému (výpis aj detail)
    'cta' => [
        'heading' => 'Chcete pravidelnú dodávku kávy?',
        'text' => 'S naším predplatným ušetríte čas aj peniaze. Čerstvá káva priamo k vám domov, kedykoľvek zrušiteľné.',
        'link' => 'Zistiť viac o predplatnom',
    ],

    // Výpis produktov
    'index' => [
        'meta_description' => 'Nakupujte výberovú kávu z najlepších európskych pražiarní. Espresso, filter, bezkofeínová káva a príslušenstvo. Čerstvá, kvalitná, výnimočná. Doručenie na Slovensko aj do celej EÚ.',
        'og_title' => 'Obchod s kávou | :site',
        'og_description' => 'Starostlivo vybraná výberová káva z najlepších európskych pražiarní. Čerstvá, kvalitná, výnimočná.',
        'heading_line1' => 'Objavte našu',
        'heading_line2' => 'kávovú kolekciu',
        'intro' => 'Starostlivo vybraná výberová káva z najlepších európskych pražiarní. Čerstvá, kvalitná, výnimočná.',
        'filter_all' => 'Všetko',
        'empty' => 'Nenašli sa žiadne produkty.',
    ],

    // Detail produktu
    'show' => [
        'availability' => [
            'discontinued' => 'UŽ NIE JE V PONUKE',
            'in_stock' => 'DOSTUPNOSŤ: SKLADOM',
            'out_of_stock' => 'DOSTUPNOSŤ: VYPREDANÉ',
        ],
        'quantity' => 'Množstvo',
        'add_to_cart' => 'Pridať do košíka',
        'discontinued_text' => 'Tento produkt sa už nepredáva. Môžete preskúmať podobné produkty v odporúčanej sekcii nižšie alebo si prezrieť našu aktuálnu ponuku.',
        'unavailable_text' => 'Tento produkt momentálne nie je dostupný.',
        'category' => 'Kategória',
        'attributes' => [
            'origin' => 'Pôvod',
            'processing' => 'Spracovanie',
            'flavor_notes' => 'Chuť',
            'altitude' => 'Nadm. výška',
            'variety' => 'Odroda',
        ],
        'weight' => 'Hmotnosť',
        'roast_date' => 'Dátum praženia',
        'roast_date_format' => 'd.m.Y',
        'related_heading' => 'Mohlo by vás zaujímať aj',
        'blog_tip' => 'Tip z blogu:',
    ],

];
