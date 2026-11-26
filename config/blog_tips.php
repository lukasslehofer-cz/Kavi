<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tip z blogu na detailu produktu
    |--------------------------------------------------------------------------
    |
    | Pod popisem produktu je jeden řádek „Tip z blogu: {článek} →“.
    | Pravidla se procházejí shora dolů, použije se první, které sedí.
    | Produkt, na který nesedí žádné, řádek nedostane.
    |
    | match:
    |   category   – produkt má danou kategorii (espresso, filter, decaf, accessories)
    |   processing – káva, jejíž metoda zpracování (attributes.processing / processing_en)
    |                obsahuje některé z klíčových slov. Běžné promyté a naturální kávy
    |                záměrně propadnou na pravidla espresso/filtr.
    |   name       – název produktu (cs i en) obsahuje některé z klíčových slov,
    |                jen v uvedené kategorii
    |
    | post: slug_cs článku (na kavibox.com se odkaz přeloží na slug_en)
    | Klíčová slova se porovnávají bez ohledu na velikost písmen.
    |
    */

    'rules' => [
        [
            'match' => 'category',
            'category' => 'decaf',
            'post' => 'bezkofeinova-kava-myty-a-fakta-pro-rok-2026',
        ],
        [
            'match' => 'processing',
            'keywords' => [
                'anaerob', 'honey', 'ferment', 'carbonic', 'macera', 'termáln', 'thermal',
                'semi-washed', 'semi washed', 'hybrid', 'advanced', 'inokul', 'kvasink',
                'black diamond',
            ],
            'post' => 'metody-zpracovani-kavy',
        ],
        [
            'match' => 'category',
            'category' => 'espresso',
            'post' => 'jak-pripravit-espresso',
        ],
        [
            'match' => 'category',
            'category' => 'filter',
            'post' => 'jak-pripravit-kavu-doma',
        ],
        [
            'match' => 'name',
            'category' => 'accessories',
            'keywords' => [
                'puck screen', 'distributor', 'tamper', 'tamping mat', 'dosing ring',
                'dávkovací misk', 'rozprašovač', 'zrcátko',
            ],
            'post' => 'jak-pripravit-espresso',
        ],
        [
            'match' => 'name',
            'category' => 'accessories',
            'keywords' => ['v60', 'chemex', 'kalita', 'origami', 'aeropress'],
            'post' => 'jak-pripravit-kavu-doma',
        ],
    ],

];
