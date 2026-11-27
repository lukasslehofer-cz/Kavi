<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Texts from application code (controllers, models, services)
    |--------------------------------------------------------------------------
    */

    'cart' => [
        'free_shipping_digital' => 'Free shipping (digital product)',
        'shipping_at_checkout' => 'Shipping cost will be calculated at checkout',
        'product_unavailable' => 'Product is not available.',
        'not_enough_stock_sorry' => 'Sorry, we don\'t have enough stock.',
        'not_enough_stock' => 'We don\'t have enough stock.',
        'cleared' => 'Cart cleared.',
    ],

    'coupon' => [
        'free_shipping' => 'Free shipping',
        'no_discount' => 'No discount',
        'for_months' => ' for :count month| for :count months',
        'unlimited' => ' unlimited',
    ],

    'shipping' => [
        'free' => 'Free',
    ],

    'subscription' => [
        'first_box_shipping' => 'Your first coffee box will be shipped on :date',
        'first_box_date_format' => 'M d, Y',
    ],

    'files' => [
        'invoice_prefix' => 'Invoice-',
        'voucher_prefix' => 'Voucher-',
        'gift_certificate_prefix' => 'kavi-gift-',
    ],

    // Line items and payment descriptions in Stripe (Checkout, payment details)
    'stripe' => [
        'shipping' => 'Shipping',
        'discount' => 'Discount',
        'one_time_box_description' => 'One-time coffee box without subscription',
        'subscription_payment' => 'Subscription payment - :number',
        'unpaid_subscription_payment' => 'Unpaid subscription payment',
        'subscription_shipment' => 'Coffee subscription - shipment :date',
        'subscription' => 'Coffee subscription - :number',
        'subscription_with_shipment' => 'Coffee subscription - :number (:date)',
        'box_types' => [
            'espresso' => 'Espresso',
            'filter' => 'Filter',
            'mix' => 'Mix',
        ],
        'default_coffee' => 'Coffee',
        'suffix_one_time' => ' (One-time)',
        'suffix_first_payment' => ' (First payment)',
    ],

    'product' => [
        // Za nadmořskou výškou z atributů kávy (mezera na začátku je záměrná)
        'altitude_unit' => ' masl',
    ],

];
