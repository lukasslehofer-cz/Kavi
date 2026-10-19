<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Landing page z reklamy "Evropa v jednom boxu"
    |--------------------------------------------------------------------------
    |
    | Data (:cutoff, :shipment, ceny) doplňuje LandingController z ShipmentSchedule
    | a SubscriptionConfig, nic tu není napsané natvrdo.
    |
    */

    'europe' => [
        'title' => 'Evropa v jednom boxu | KAVI.cz',
        'meta_description' => 'Každý měsíc výběrová káva ze tří evropských pražíren, které v Česku běžně nekoupíte. Doprava zdarma, bez závazku, zrušení jedním klikem.',

        'hero' => [
            'title' => ['Evropa', 'v jednom', 'boxu'],
            'lead' => 'Každý měsíc výběrová káva ze tří evropských pražíren, které v Česku běžně nekoupíte. Bez závazku, zrušení jedním klikem.',
            'price' => 'od :price',
            'price_label' => 'za box, doprava zdarma',
            'cutoff_label' => 'uzávěrka dalšího boxu',
            'cta' => 'Sestavit box',
        ],

        'why' => [
            'title' => 'Proč si vybrat KAVI?',
            'lead' => 'Víme, co dělá kávu výjimečnou. A rádi se s vámi podělíme.',
            'cards' => [
                ['n' => '3', 't' => 'Pražírny každý měsíc', 'd' => 'Z různých zemí Evropy. Vracejí se zhruba jednou za rok.'],
                ['n' => '0 Kč', 't' => 'Za dopravu', 'd' => 'Zásilkovnou na vámi vybrané výdejní místo, u každého boxu.'],
                ['n' => ':unit_l', 't' => 'Za gram v L Boxu', 'd' => 'Včetně dopravy. V XL Boxu vychází gram na :unit_xl.'],
                ['n' => '1 klik', 't' => 'Pauza nebo zrušení', 'd' => 'Bez závazků a bez poplatků, kdykoli v účtu.'],
            ],
        ],

        'curated' => [
            'title' => 'Vybíráme za vás',
            'lead' => 'Každý měsíc pro vás najdeme tři evropské pražírny a jejich kávu pošleme až domů. Vy už jen ochutnáváte.',
            'image_alt' => 'Ochutnávka kávy, cupping',
            'arrows' => [
                'Tři pražírny z různých zemí Evropy, každý měsíc jiné',
                'Kávy, které v Česku běžně nekoupíte',
                'Pohlednice s profilem kávy a pražírny ke každé kávě',
                'Káva přijde sama :day. dne, doprava vždy zdarma',
            ],
            'cta' => 'Sestavit box',
            'prices' => 'Ceny',
        ],

        'tiers_title' => 'Vyberte si velikost boxu',

        'how' => [
            'title' => 'Jak to funguje',
            'lead' => 'Tři kroky k první kávě',
            'steps' => [
                ['t' => 'Vyberte si box', 'd' => 'Velikost, espresso nebo filtr a jak často.'],
                ['t' => 'Rozesílka :shipment', 'd' => 'Objednávky do :cutoff posíláme Zásilkovnou na vámi vybrané výdejní místo, zdarma.'],
                ['t' => 'Každý měsíc jinak', 'd' => 'Další měsíc nové pražírny. Nebo pauza či zrušení jedním klikem v účtu.'],
            ],
        ],

        'faq' => [
            'title' => 'Než se rozhodnete',
            'items' => [
                ['q' => 'Můžu kdykoli skončit?', 'a' => 'Ano. Pauza, změna nebo zrušení jedním klikem v účtu, bez poplatků.'],
                ['q' => 'Vyplatí se to?', 'a' => 'L Box vychází na :unit_l za gram včetně dopravy, M Box na :unit_m a XL Box na :unit_xl.'],
                ['q' => 'Kdy dostanu první box?', 'a' => 'Objednávky do :cutoff odcházejí :shipment Další boxy pak podle zvolené frekvence, vždy kolem 20. dne v měsíci.'],
                ['q' => 'Můžu objednat jen jeden box?', 'a' => 'Ano. Jednorázový box bez předplatného stojí o :surcharge víc.'],
            ],
        ],

        'final' => [
            'title' => 'Uzávěrka :cutoff o půlnoci',
            'lead' => 'Další box odchází :shipment Doprava zdarma, bez závazků, zrušení jedním klikem.',
            'cta' => 'Sestavit box',
            'compare' => 'Porovnat boxy',
        ],
    ],

];
