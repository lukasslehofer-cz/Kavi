<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Texty z kódu aplikace (controllery, modely, služby)
    |--------------------------------------------------------------------------
    */

    'cart' => [
        'free_shipping_digital' => 'Doprava zdarma (digitální produkt)',
        'shipping_at_checkout' => 'Cena dopravy bude vypočítána v pokladně po zadání adresy',
        'product_unavailable' => 'Produkt není k dispozici.',
        'not_enough_stock_sorry' => 'Omlouváme se, ale nemáme dostatek zásob.',
        'not_enough_stock' => 'Nemáme dostatek zásob.',
        'cleared' => 'Košík byl vyprázdněn.',
    ],

    'coupon' => [
        'free_shipping' => 'Doprava zdarma',
        'no_discount' => 'Žádná sleva',
        'for_months' => ' po dobu :count měsíc| po dobu :count měsíce| po dobu :count měsíců',
        'unlimited' => ' neomezeně',
    ],

    'shipping' => [
        'free' => 'Zdarma',
    ],

    'subscription' => [
        'first_box_shipping' => 'První kávový box bude odeslán :date',
        'first_box_date_format' => 'd.m.Y',
    ],

    'files' => [
        'invoice_prefix' => 'Faktura-',
        'voucher_prefix' => 'Voucher-',
        'gift_certificate_prefix' => 'kavi-darek-',
    ],

    // Položky a popisy plateb ve Stripe (Checkout, výpis platby)
    'stripe' => [
        'shipping' => 'Doprava',
        'discount' => 'Sleva',
        'one_time_box_description' => 'Jednorázový kávový box bez předplatného',
        'subscription_payment' => 'Platba předplatného - :number',
        'unpaid_subscription_payment' => 'Neuhrazená platba za kávové předplatné',
        'subscription_shipment' => 'Kávové předplatné - rozesílka :date',
        'subscription' => 'Kávové předplatné - :number',
        'subscription_with_shipment' => 'Kávové předplatné - :number (:date)',
        'box_types' => [
            'espresso' => 'Espresso',
            'filter' => 'Filtr',
            'mix' => 'Mix',
        ],
        'default_coffee' => 'Káva',
        'suffix_one_time' => ' (Jednorázově)',
        'suffix_first_payment' => ' (První platba)',
    ],

    'product' => [
        // Za nadmořskou výškou z atributů kávy (mezera na začátku je záměrná)
        'altitude_unit' => ' m n.m.',
    ],

];
