<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shared components (resources/views/components)
    |--------------------------------------------------------------------------
    */

    'unit_price' => [
        'per_gram' => ':price per gram',
        'per_100g' => ':price per 100 g',
    ],

    'countdown' => [
        'cutoff' => 'Orders close :date at midnight',
        'units' => [
            'days' => 'days',
            'hours' => 'hrs',
            'minutes' => 'min',
            'seconds' => 'sec',
        ],
        'aria' => 'Time left until orders close: :days, :hours and :minutes',
        'plural' => [
            'days' => ['day', 'days', 'days'],
            'hours' => ['hour', 'hours', 'hours'],
            'minutes' => ['minute', 'minutes', 'minutes'],
        ],
        'passed' => 'Orders for this box have just closed',
    ],

    'tiers' => [
        'bags' => ':grams g · :count bags',
        'kinds' => [
            2 => '2 specialty coffees',
            3 => '3 specialty coffees',
            4 => '3 specialty coffees',
        ],
        // Subscription shipping is not free to every country on kavibox.com
        'shipping_unit' => 'Lovingly packed · :unit',
        'cancel' => 'Cancel or pause anytime',
        'select' => 'Select :box Box',
        'popular' => 'Most Popular',
        'per_box' => '/box',
        'lead' => 'Flexible subscription tailored to your needs. Cancel anytime without fee.',
        'lead_shipping' => '',
        'note' => 'A one-off box without a subscription costs :surcharge more.',
    ],

    'rating' => [
        'count' => '{1} :count Google review|[0,*] :count Google reviews',
        'aria' => 'Rated :rating out of 5 from :count Google reviews',
        'out_of' => ':rating out of 5',
    ],

    'reviews' => [
        'title' => 'What our customers say',
        'title_empty' => 'Share your experience',
        'source' => 'Google review',
        'anonymous' => 'Anonymous',
        'ask_label' => 'Your review',
        'ask_text' => 'Have you ordered from us? Tell us how the coffee tasted. You will help others choose, and show us what to improve.',
        'ask_cta' => 'Review on Google',
        'disclosure' => 'Reviews come from our Google Business Profile. We display them unedited and unfiltered, including negative ones. We do not verify who wrote them, Google does.',
        'bar' => 'Join our community of coffee lovers',
        'bar_empty' => 'Already ordered from us? Let others know',
        'bar_link' => 'Google Reviews',
    ],

    'coffee_of_month' => [
        'title' => 'Coffee of the Month',
        'eyebrow' => 'Three European roasters every month. Each one returns roughly once a year, so almost every box is a first taste.',
        'explore' => 'Explore the :month coffees',
        'decaf' => 'decaf',
    ],

    'promo_card' => [
        'alt' => 'Coffees from the :month box',
        'description' => 'Premium specialty coffees from European micro-roasters, freshly roasted and delivered to your door.',
        'more' => 'View More',
    ],

    'faq' => [
        'title' => 'FAQ',
        'contact' => 'Didn\'t find your answer? Write to :email.',
    ],

];
