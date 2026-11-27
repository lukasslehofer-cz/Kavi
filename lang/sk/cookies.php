<?php

return [
    // Cookie consent banner
    'consent_modal' => [
        'title' => 'Používame cookies',
        'description' => 'Dobrý deň! Tento web používa súbory cookie na zabezpečenie správneho fungovania, analýzu návštevnosti a poskytovanie relevantného obsahu. Niektoré súbory cookie sú nevyhnutné na fungovanie webu, ostatné môžete odmietnuť. <a href=":privacy_url" class="cc-link">Ochrana osobných údajov</a>',
        'accept_all_btn' => 'Prijať všetko',
        'accept_necessary_btn' => 'Odmietnuť všetko',
        'show_settings_btn' => 'Upraviť nastavenia',
        'close_btn_label' => 'Zavrieť',
    ],

    'settings_modal' => [
        'title' => 'Nastavenia cookies',
        'save_settings_btn' => 'Uložiť nastavenia',
        'accept_all_btn' => 'Prijať všetko',
        'reject_all_btn' => 'Odmietnuť všetko',
        'close_btn_label' => 'Zavrieť',
        'blocks' => [
            [
                'title' => 'Používanie súborov cookie',
                'description' => 'Cookies používame na zabezpečenie základných funkcií webu a na zlepšenie vášho online zážitku. Pri každej kategórii si môžete vybrať, či ju chcete povoliť alebo zakázať. Viac informácií nájdete v našich <a href=":privacy_url" class="cc-link">zásadách ochrany osobných údajov</a>.',
            ],
            [
                'title' => 'Nevyhnutné cookies',
                'description' => 'Tieto súbory cookie sú nevyhnutné na správne fungovanie webu. Bez nich by web nefungoval správne. Zahŕňajú napr. bezpečnostné tokeny a nastavenie jazyka.',
                'toggle' => [
                    'value' => 'necessary',
                    'enabled' => true,
                    'readonly' => true,
                ],
            ],
            [
                'title' => 'Analytické cookies',
                'description' => 'Tieto súbory cookie nám pomáhajú pochopiť, ako návštevníci používajú náš web. Údaje zhromažďujeme anonymne a používame ich na zlepšenie našich služieb.',
                'toggle' => [
                    'value' => 'analytics',
                    'enabled' => false,
                    'readonly' => false,
                ],
            ],
            [
                'title' => 'Marketingové cookies',
                'description' => 'Tieto súbory cookie sa používajú na zobrazovanie reklám, ktoré sú pre vás relevantné a prispôsobené vašim záujmom.',
                'toggle' => [
                    'value' => 'marketing',
                    'enabled' => false,
                    'readonly' => false,
                ],
            ],
        ],
    ],
];
