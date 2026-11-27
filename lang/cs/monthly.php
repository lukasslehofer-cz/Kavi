<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Káva měsíce (/kava-mesice)
    |--------------------------------------------------------------------------
    |
    | :month = název měsíce s rokem (např. „Prosinec 2026“).
    |
    */

    'title' => 'Káva měsíce :month',
    'meta_description' => 'Objevte náš kávový výběr na :month. Výjimečné výběrové kávy z nejlepších evropských pražíren, čerstvě pražené a doručené až k vám.',
    'og_title' => 'Káva měsíce :month | :site',
    'og_description' => 'Každý měsíc pro vás vybíráme výjimečné kávy z nejlepších evropských pražíren. Objevte, co vás čeká v :month.',

    'heading_line1' => 'Pražírny a kávy',
    'heading_line2' => 'měsíce',
    'intro' => 'Každý měsíc pro vás vybíráme výjimečné kávy z nejlepších evropských pražíren. Objevte, co vás čeká.',

    // Pražírny měsíce
    'roasteries' => [
        'heading' => '{0} Pražírny měsíce|{1} Pražírna měsíce|[2,*] Pražírny měsíce',
        'subheading' => '{0} Tentokrát jsme vybrali kávy od těchto výjimečných pražíren|{1} Tentokrát jsme vybrali kávy od této výjimečné pražírny|[2,*] Tentokrát jsme vybrali kávy od těchto výjimečných pražíren',
        'more_about' => 'Více o pražírně',
    ],

    // Kávy měsíce a okno s detailem kávy
    'coffees' => [
        'heading' => 'Kávy měsíce',
        'subheading' => 'Tyto výběrové kávy jsou součástí našeho aktuálního předplatného',
    ],

    'modal' => [
        'badge' => 'Káva měsíce',
        'parameters' => 'Parametry',
        'attributes' => [
            'origin' => 'Původ',
            'altitude' => 'Nadmořská výška',
            'processing' => 'Zpracování',
            'variety' => 'Odrůda',
            'flavor_notes' => 'Chuťové tóny',
        ],
        'weight' => 'Hmotnost',
        'roast_date' => 'Datum pražení',
        'roast_date_format' => 'd.m.Y',
        'cta_heading' => 'Součástí aktuálního předplatného',
        'cta_text' => 'Získejte ji společně s dalšími výběrovými kávami',
        'cta_link' => 'Zjistit více',
    ],

    // Výběr ještě není zveřejněný
    'empty' => [
        'heading' => 'Káva měsíce připravujeme',
        'text' => 'Právě vybíráme výjimečné kávy pro měsíc :month. Brzy vás překvapíme!',
    ],

    'learn_more_subscription' => 'Zjistit více o předplatném',

    // Spodní výzva k předplatnému
    'cta' => [
        'heading' => 'Chcete dostávat podobné kávy pravidelně?',
        'text' => 'Přihlaste se k našemu předplatnému a box s vybranými kávovými speciály na vás bude pravidelně čekat ve výdejním místě.',
    ],

];
