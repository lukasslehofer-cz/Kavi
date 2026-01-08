<?php

return [
    // Cookie consent banner
    'consent_modal' => [
        'title' => 'Používáme cookies',
        'description' => 'Dobrý den! Tento web používá soubory cookie pro zajištění správné funkčnosti, analýzu návštěvnosti a poskytování relevantního obsahu. Některé soubory cookie jsou nezbytné pro fungování webu, ostatní můžete odmítnout. <a href=":privacy_url" class="cc-link">Ochrana osobních údajů</a>',
        'accept_all_btn' => 'Přijmout vše',
        'accept_necessary_btn' => 'Odmítnout vše',
        'show_settings_btn' => 'Upravit nastavení',
        'close_btn_label' => 'Zavřít',
    ],

    'settings_modal' => [
        'title' => 'Nastavení cookies',
        'save_settings_btn' => 'Uložit nastavení',
        'accept_all_btn' => 'Přijmout vše',
        'reject_all_btn' => 'Odmítnout vše',
        'close_btn_label' => 'Zavřít',
        'blocks' => [
            [
                'title' => 'Použití souborů cookie',
                'description' => 'Cookies používáme k zajištění základních funkcí webu a ke zlepšení vašeho online zážitku. Pro každou kategorii si můžete vybrat, zda ji chcete povolit nebo zakázat. Více informací najdete v našich <a href=":privacy_url" class="cc-link">zásadách ochrany osobních údajů</a>.',
            ],
            [
                'title' => 'Nezbytné cookies',
                'description' => 'Tyto soubory cookie jsou nezbytné pro správné fungování webu. Bez nich by web nefungoval správně. Zahrnují např. bezpečnostní tokeny a nastavení jazyka.',
                'toggle' => [
                    'value' => 'necessary',
                    'enabled' => true,
                    'readonly' => true,
                ],
            ],
            [
                'title' => 'Analytické cookies',
                'description' => 'Tyto soubory cookie nám pomáhají porozumět tomu, jak návštěvníci používají náš web. Údaje shromažďujeme anonymně a používáme je ke zlepšení našich služeb.',
                'toggle' => [
                    'value' => 'analytics',
                    'enabled' => false,
                    'readonly' => false,
                ],
            ],
            [
                'title' => 'Marketingové cookies',
                'description' => 'Tyto soubory cookie se používají k zobrazení reklam, které jsou pro vás relevantní a přizpůsobené vašim zájmům.',
                'toggle' => [
                    'value' => 'marketing',
                    'enabled' => false,
                    'readonly' => false,
                ],
            ],
        ],
    ],
];
