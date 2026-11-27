<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Blog: výpis článkov a článok
    |--------------------------------------------------------------------------
    |
    | Titulky, perexy a obsah článkov sú v databáze (gettery modelu Post).
    |
    */

    'title' => 'Blog',
    'author' => 'Autor',

    'pagination' => [
        'page' => 'Strana',
        'previous' => 'Predchádzajúca',
        'next' => 'Ďalšia',
    ],

    // Výpis článkov
    'index' => [
        'meta_description' => 'Kávový blog s tipmi, receptami a príbehmi zo sveta výberovej kávy. Objavte umenie prípravy kávy a učte sa od najlepších pražiarní.',
        'og_title' => 'Kávový blog | :site',
        'og_description' => 'Objavte umenie výberovej kávy. Tipy, recepty a príbehy od najlepších pražiarní.',
        'heading_line1' => 'Kávový',
        'heading_line2' => 'blog',
        'intro' => 'Tipy, recepty a príbehy zo sveta výberovej kávy. Objavte umenie prípravy kávy.',
        'filter_all' => 'Všetko',
        'showing_tag' => 'Zobrazené články so štítkom',
        'empty' => 'Zatiaľ žiadne články.',
        'empty_text' => 'Čoskoro pridáme nový obsah.',
        'cta_heading' => 'Chcete ochutnať našu kávu?',
        'cta_text' => 'Objavte našu kolekciu výberovej kávy z najlepších európskych pražiarní.',
        'cta_link' => 'Prezrieť obchod',
    ],

    // Článok
    'show' => [
        'tags' => 'Štítky',
        'back' => 'Späť na blog',
        'related' => 'Súvisiace články',
        'promo_heading' => 'Začnite svoju kávovú cestu ešte dnes',
        'promo_text' => 'Získajte prístup k najlepšej káve z celej Európy. Flexibilné predplatné, bez záväzkov.',
        'promo_subscription' => 'Zostaviť kávový box',
        'promo_gift' => 'Darčekové predplatné',
        'promo_shop' => 'Prezrieť kávy',
    ],

];
