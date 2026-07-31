<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fakturační hranice
    |--------------------------------------------------------------------------
    |
    | Výchozí částka, od které si partner může odměny vyfakturovat. Použije se
    | u partnerů, kteří nemají vlastní hodnotu ve sloupci
    | users.affiliate_payout_threshold. Klíč je měna odměn partnera.
    |
    */

    'payout_threshold' => [
        'CZK' => (float) env('AFFILIATE_PAYOUT_THRESHOLD_CZK', 1000),
        'EUR' => (float) env('AFFILIATE_PAYOUT_THRESHOLD_EUR', 40),
    ],

    /*
    |--------------------------------------------------------------------------
    | Automatické e-maily
    |--------------------------------------------------------------------------
    |
    | Přepínače pro jednotlivé automatické maily partnerům. Slouží hlavně
    | k rychlému vypnutí bez deploye, pokud by něco odcházelo špatně.
    |
    */

    'emails' => [
        'code_used' => (bool) env('AFFILIATE_EMAIL_CODE_USED', true),
        'monthly_summary' => (bool) env('AFFILIATE_EMAIL_MONTHLY_SUMMARY', true),
        'payout_threshold' => (bool) env('AFFILIATE_EMAIL_PAYOUT_THRESHOLD', true),
    ],

];
