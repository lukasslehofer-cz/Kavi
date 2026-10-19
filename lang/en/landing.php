<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ad landing page "Europe in one box"
    |--------------------------------------------------------------------------
    |
    | LandingController fills in the data (:cutoff, :shipment, prices) from
    | ShipmentSchedule and SubscriptionConfig, nothing is hard-coded here.
    | Subscription shipping is not free to every country on kavibox.com, so the
    | English copy does not promise free shipping.
    |
    */

    'europe' => [
        'title' => 'Europe in One Box | KAVI',
        'meta_description' => 'Every month, specialty coffee from three European roasters you rarely find in local shops. No commitment, cancel with one click.',

        'hero' => [
            'title' => ['Europe', 'in one', 'box'],
            'lead' => 'Every month, specialty coffee from three European roasters you rarely find in local shops. No commitment, cancel with one click.',
            'price' => 'from :price',
            'price_label' => 'per box',
            'cutoff_label' => 'next box order deadline',
            'cta' => 'Build your box',
        ],

        'why' => [
            'title' => 'Why choose KAVI?',
            'lead' => 'We know what makes coffee exceptional. And we love to share it.',
            'cards' => [
                ['n' => '3', 't' => 'Roasters every month', 'd' => 'From different European countries. Each one returns roughly once a year.'],
                ['n' => 'Packeta', 't' => 'To your pickup point', 'd' => 'Every box goes to a Packeta pickup point of your choice.'],
                ['n' => ':unit_l', 't' => 'Per 100 g in the L Box', 'd' => 'In the XL Box, 100 g comes to :unit_xl.'],
                ['n' => '1 click', 't' => 'Pause or cancel', 'd' => 'No commitment and no fees, anytime in your account.'],
            ],
        ],

        'curated' => [
            'title' => 'We choose for you',
            'lead' => 'Every month we find three European roasters for you and send their coffee to your door. All you do is taste.',
            'image_alt' => 'Coffee tasting, cupping',
            'arrows' => [
                'Three roasters from different European countries, new every month',
                'Coffees you rarely find in local shops',
                'A postcard with the coffee and roaster profile for every coffee',
                'Your coffee arrives by itself every month',
            ],
            'cta' => 'Build your box',
            'prices' => 'Prices',
        ],

        'tiers_title' => 'Choose your box size',

        'how' => [
            'title' => 'How it works',
            'lead' => 'Three steps to your first coffee',
            'steps' => [
                ['t' => 'Choose your box', 'd' => 'Size, espresso or filter, and how often.'],
                ['t' => 'Ships :shipment', 'd' => 'Orders placed by :cutoff ship via Packeta to the pickup point you choose.'],
                ['t' => 'Different every month', 'd' => 'New roasters next month. Or pause or cancel with one click in your account.'],
            ],
        ],

        'faq' => [
            'title' => 'Before you decide',
            'items' => [
                ['q' => 'Can I stop anytime?', 'a' => 'Yes. Pause, change or cancel with one click in your account, no fees.'],
                ['q' => 'Is it worth it?', 'a' => 'The L Box comes to :unit_l per 100 g, the M Box to :unit_m and the XL Box to :unit_xl.'],
                ['q' => 'When will I get my first box?', 'a' => 'Orders placed by :cutoff ship on :shipment. After that, boxes follow your chosen frequency, always around the 20th of the month.'],
                ['q' => 'Can I order just one box?', 'a' => 'Yes. A one-off box without a subscription costs :surcharge more.'],
            ],
        ],

        'final' => [
            'title' => 'Orders close :cutoff at midnight',
            'lead' => 'The next box ships on :shipment. No commitment, cancel with one click.',
            'cta' => 'Build your box',
            'compare' => 'Compare boxes',
        ],
    ],

];
