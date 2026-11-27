<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Texty z kódu aplikácie (controllery, modely, služby)
    |--------------------------------------------------------------------------
    */

    'cart' => [
        'free_shipping_digital' => 'Doprava zadarmo (digitálny produkt)',
        'shipping_at_checkout' => 'Cena dopravy sa vypočíta v pokladni po zadaní adresy',
        'product_unavailable' => 'Produkt nie je k dispozícii.',
        'not_enough_stock_sorry' => 'Ospravedlňujeme sa, ale nemáme dostatok zásob.',
        'not_enough_stock' => 'Nemáme dostatok zásob.',
        'cleared' => 'Košík bol vyprázdnený.',
    ],

    'coupon' => [
        'free_shipping' => 'Doprava zadarmo',
        'no_discount' => 'Žiadna zľava',
        'for_months' => ' na :count mesiac| na :count mesiace| na :count mesiacov',
        'unlimited' => ' neobmedzene',
    ],

    'shipping' => [
        'free' => 'Zadarmo',
    ],

    'subscription' => [
        'first_box_shipping' => 'Prvý kávový box bude odoslaný :date',
        'first_box_date_format' => 'd.m.Y',
    ],

    'files' => [
        'invoice_prefix' => 'Faktura-',
        'voucher_prefix' => 'Voucher-',
        'gift_certificate_prefix' => 'kavi-darcek-',
    ],

    // Položky a popisy platieb v Stripe (Checkout, výpis platby)
    'stripe' => [
        'shipping' => 'Doprava',
        'discount' => 'Zľava',
        'one_time_box_description' => 'Jednorazový kávový box bez predplatného',
        'subscription_payment' => 'Platba predplatného - :number',
        'unpaid_subscription_payment' => 'Neuhradená platba za kávové predplatné',
        'subscription_shipment' => 'Kávové predplatné - zásielka :date',
        'subscription' => 'Kávové predplatné - :number',
        'subscription_with_shipment' => 'Kávové predplatné - :number (:date)',
        'box_types' => [
            'espresso' => 'Espresso',
            'filter' => 'Filter',
            'mix' => 'Mix',
        ],
        'default_coffee' => 'Káva',
        'suffix_one_time' => ' (Jednorazovo)',
        'suffix_first_payment' => ' (Prvá platba)',
    ],

    'product' => [
        // Za nadmorskou výškou z atribútov kávy (medzera na začiatku je zámerná)
        'altitude_unit' => ' m n. m.',
    ],

];
