<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Obchod: výpis produktů a detail produktu
    |--------------------------------------------------------------------------
    |
    | Názvy, popisy a parametry produktů jsou v databázi (gettery modelu Product).
    |
    */

    'shop' => 'Obchod',

    'categories' => [
        'espresso' => 'Espresso',
        'filter' => 'Filtr',
        'decaf' => 'Bezkofeinová',
        'accessories' => 'Příslušenství',
    ],

    'pagination' => [
        'page' => 'Strana',
        'previous' => 'Předchozí',
        'next' => 'Další',
    ],

    // Spodní výzva k předplatnému (výpis i detail)
    'cta' => [
        'heading' => 'Chcete pravidelnou dodávku kávy?',
        'text' => 'S naším předplatným ušetříte čas i peníze. Čerstvá káva přímo k vám domů, kdykoliv zrušitelné.',
        'link' => 'Zjistit více o předplatném',
    ],

    // Výpis produktů
    'index' => [
        'meta_description' => 'Nakupujte výběrovou kávu z nejlepších evropských pražíren. Espresso, filtr, bezkofeinová káva a příslušenství. Čerstvá, kvalitní, výjimečná. Doprava zdarma.',
        'og_title' => 'Kávový obchod | :site',
        'og_description' => 'Pečlivě vybraná výběrová káva z nejlepších evropských pražíren. Čerstvá, kvalitní, výjimečná.',
        'heading_line1' => 'Objevte naši',
        'heading_line2' => 'kávovou kolekci',
        'intro' => 'Pečlivě vybraná výběrová káva z nejlepších evropských pražíren. Čerstvá, kvalitní, výjimečná.',
        'filter_all' => 'Vše',
        'empty' => 'Žádné produkty nebyly nalezeny.',
    ],

    // Detail produktu
    'show' => [
        'availability' => [
            'discontinued' => 'UŽ NENÍ V NABÍDCE',
            'in_stock' => 'DOSTUPNOST: SKLADEM',
            'out_of_stock' => 'DOSTUPNOST: VYPRODÁNO',
        ],
        'quantity' => 'Množství',
        'add_to_cart' => 'Přidat do košíku',
        'discontinued_text' => 'Tento produkt již není v prodeji. Můžete prozkoumat podobné produkty v doporučené sekci níže nebo procházet naši aktuální nabídku.',
        'unavailable_text' => 'Tento produkt je momentálně nedostupný.',
        'category' => 'Kategorie',
        'attributes' => [
            'origin' => 'Původ',
            'processing' => 'Zpracování',
            'flavor_notes' => 'Chuť',
            'altitude' => 'Nadm. výška',
            'variety' => 'Odrůda',
        ],
        'weight' => 'Hmotnost',
        'roast_date' => 'Datum pražení',
        'roast_date_format' => 'd.m.Y',
        'related_heading' => 'Mohlo by vás také zajímat',
        'blog_tip' => 'Tip z blogu:',
    ],

];
