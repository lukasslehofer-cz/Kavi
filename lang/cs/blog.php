<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Blog: výpis článků a článek
    |--------------------------------------------------------------------------
    |
    | Titulky, perexy a obsah článků jsou v databázi (gettery modelu Post).
    |
    */

    'title' => 'Blog',
    'author' => 'Autor',

    'pagination' => [
        'page' => 'Strana',
        'previous' => 'Předchozí',
        'next' => 'Další',
    ],

    // Výpis článků
    'index' => [
        'meta_description' => 'Kávový blog s tipy, recepty a příběhy ze světa výběrové kávy. Objevte umění přípravy kávy a učte se od nejlepších pražíren.',
        'og_title' => 'Kávový blog | :site',
        'og_description' => 'Objevte umění výběrové kávy. Tipy, recepty a příběhy od nejlepších pražíren.',
        'heading_line1' => 'Kávový',
        'heading_line2' => 'blog',
        'intro' => 'Tipy, recepty a příběhy ze světa výběrové kávy. Objevte umění přípravy kávy.',
        'filter_all' => 'Vše',
        'showing_tag' => 'Zobrazeny články s tagem',
        'empty' => 'Zatím žádné články.',
        'empty_text' => 'Brzy přidáme nový obsah.',
        'cta_heading' => 'Chcete ochutnat naši kávu?',
        'cta_text' => 'Objevte naši kolekci výběrové kávy z nejlepších evropských pražíren.',
        'cta_link' => 'Prohlédnout obchod',
    ],

    // Článek
    'show' => [
        'tags' => 'Tagy',
        'back' => 'Zpět na blog',
        'related' => 'Související články',
        'promo_heading' => 'Začněte svou kávovou cestu ještě dnes',
        'promo_text' => 'Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků.',
        'promo_subscription' => 'Sestavit kávový box',
        'promo_gift' => 'Dárkové předplatné',
        'promo_shop' => 'Procházet kávy',
    ],

];
