<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dárky (dárkové předplatné a poukaz na e-shop)
    |--------------------------------------------------------------------------
    |
    | enabled: zapíná stránku nákupu dárků a všechny vstupy na ni (menu, patička,
    | sekce "Darujte kávu" na homepage, odkazy v hero a FAQ). Zapnout až ve chvíli,
    | kdy na produkci běží nákup i uplatnění dárku.
    |
    */

    'enabled' => (bool) env('GIFTS_ENABLED', false),

    // Délky dárkového předplatného v měsících (= počet boxů)
    'months' => [3, 6],

    // Hodnoty poukazu podle měny. Poukaz platí jen v měně, ve které byl koupen.
    'voucher_amounts' => [
        'CZK' => [500, 1000, 2000],
        'EUR' => [25, 50, 100],
    ],

    // Sazby DPH při prodeji dárku. Dárkové předplatné je jednoúčelový poukaz (DPH hned),
    // poukaz na e-shop víceúčelový (DPH až při uplatnění, viz Coupon.is_gift_voucher).
    'vat_rates' => [
        'subscription' => (float) env('GIFT_VAT_SUBSCRIPTION', 12),
        'voucher' => (float) env('GIFT_VAT_VOUCHER', 0),
    ],

    // Platnost certifikátu i poukazu od zaplacení
    'validity_months' => 12,

    'name_max_length' => 60,
    'note_max_length' => 200,

    // Odložené odeslání obdarovanému: výchozí datum (m-d) a hodina odeslání
    'default_send_date' => '12-24',
    'recipient_send_hour' => 8,

];
