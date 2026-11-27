<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Landing page z reklamy "Európa v jednom boxe"
    |--------------------------------------------------------------------------
    |
    | Dáta (:cutoff, :shipment, ceny) dopĺňa LandingController z ShipmentSchedule
    | a SubscriptionConfig, nič tu nie je napísané natvrdo. Doprava predplatného
    | nie je zadarmo do všetkých krajín, preto texty dopravu zadarmo nesľubujú
    | (rovnako ako na kavibox.com).
    |
    */

    'europe' => [
        'title' => 'Európa v jednom boxe | KAVIbox.sk',
        'meta_description' => 'Každý mesiac výberová káva z troch európskych pražiarní, ktoré v bežných obchodoch len tak nenájdete. Bez záväzku, zrušenie jedným klikom.',

        'hero' => [
            'title' => ['Európa', 'v jednom', 'boxe'],
            'lead' => 'Každý mesiac výberová káva z troch európskych pražiarní, ktoré v bežných obchodoch len tak nenájdete. Bez záväzku, zrušenie jedným klikom.',
            'price' => 'od :price',
            'price_label' => 'za box',
            'cutoff_label' => 'uzávierka ďalšieho boxu',
            'cta' => 'Zostaviť box',
        ],

        'why' => [
            'title' => 'Prečo si vybrať KAVI?',
            'lead' => 'Vieme, čo robí kávu výnimočnou. A radi sa s vami podelíme.',
            'cards' => [
                ['n' => '3', 't' => 'Pražiarne každý mesiac', 'd' => 'Z rôznych krajín Európy. Vracajú sa zhruba raz za rok.'],
                ['n' => 'Packeta', 't' => 'Na výdajné miesto', 'd' => 'Každý box doručíme na výdajné miesto Packety podľa vášho výberu.'],
                ['n' => ':unit_l', 't' => 'Za 100 g v L Boxe', 'd' => 'V XL Boxe vychádza 100 g na :unit_xl.'],
                ['n' => '1 klik', 't' => 'Pauza alebo zrušenie', 'd' => 'Bez záväzkov a bez poplatkov, kedykoľvek v účte.'],
            ],
        ],

        'curated' => [
            'title' => 'Vyberáme za vás',
            'lead' => 'Každý mesiac pre vás nájdeme tri európske pražiarne a ich kávu pošleme až k vám. Vy už len ochutnávate.',
            'image_alt' => 'Ochutnávka kávy, cupping',
            'arrows' => [
                'Tri pražiarne z rôznych krajín Európy, každý mesiac iné',
                'Kávy, ktoré v bežných obchodoch len tak nenájdete',
                'Pohľadnica s profilom kávy a pražiarne ku každej káve',
                'Káva príde sama, odosielame ju vždy :day. dňa v mesiaci',
            ],
            'cta' => 'Zostaviť box',
            'prices' => 'Ceny',
        ],

        'tiers_title' => 'Vyberte si veľkosť boxu',

        'how' => [
            'title' => 'Ako to funguje',
            'lead' => 'Tri kroky k prvej káve',
            'steps' => [
                ['t' => 'Vyberte si box', 'd' => 'Veľkosť, espresso alebo filter a ako často.'],
                ['t' => 'Odosielame :shipment', 'd' => 'Objednávky do :cutoff posielame cez Packetu na vami vybrané výdajné miesto.'],
                ['t' => 'Každý mesiac inak', 'd' => 'Ďalší mesiac nové pražiarne. Alebo pauza či zrušenie jedným klikom v účte.'],
            ],
        ],

        'faq' => [
            'title' => 'Kým sa rozhodnete',
            'items' => [
                ['q' => 'Môžem kedykoľvek skončiť?', 'a' => 'Áno. Pauza, zmena alebo zrušenie jedným klikom v účte, bez poplatkov.'],
                ['q' => 'Oplatí sa to?', 'a' => 'L Box vychádza na :unit_l za 100 g, M Box na :unit_m a XL Box na :unit_xl.'],
                ['q' => 'Kedy dostanem prvý box?', 'a' => 'Objednávky do :cutoff odchádzajú :shipment Ďalšie boxy potom podľa zvolenej frekvencie, vždy okolo 20. dňa v mesiaci.'],
                ['q' => 'Môžem si objednať len jeden box?', 'a' => 'Áno. Jednorazový box bez predplatného stojí o :surcharge viac.'],
            ],
        ],

        'final' => [
            'title' => 'Uzávierka :cutoff o polnoci',
            'lead' => 'Ďalší box odchádza :shipment Bez záväzkov, zrušenie jedným klikom.',
            'cta' => 'Zostaviť box',
            'compare' => 'Porovnať boxy',
        ],
    ],

];
