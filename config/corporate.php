<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firemní dárky (stránka Pro firmy, jen kavi.cz)
    |--------------------------------------------------------------------------
    |
    | Stránka nemá ceny. Firma pošle poptávku, nabídku s cenou dostane e-mailem.
    | Procenta a hranice slev se zobrazují v hero, v bloku množstevních cen
    | i ve formuláři, takže se mění jen tady.
    |
    */

    // Kam chodí poptávky (replyTo je zákazník)
    'email' => env('CORPORATE_EMAIL', 'info@kavi.cz'),

    'min_quantity' => 10,

    // Množstevní sleva: od kusů => %
    'discounts' => [
        10 => 5,
        25 => 8,
        50 => 10,
    ],

    // Vánoční štítek v hero a věta v FAQ. Zobrazí se jen od října do data poptávky
    // (včetně, do 23:59:59 Europe/Prague). Na příští Vánoce změnit data a zapnout.
    'christmas' => [
        // Vypnuto 26. 11. 2026: na vánoční rozvoz už nezbývá káva.
        'enabled' => false,
        'inquiry_by' => env('CORPORATE_XMAS_INQUIRY_BY', '2026-12-01'),
        'deliver_by' => env('CORPORATE_XMAS_DELIVER_BY', '2026-12-20'),
    ],

];
