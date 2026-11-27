<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Coffee of the Month (/coffee-of-the-month)
    |--------------------------------------------------------------------------
    |
    | :month = month name with year (e.g. "December 2026").
    |
    */

    'title' => 'Coffee of the Month :month',
    'meta_description' => 'Discover our coffee selection for :month. Exceptional specialty coffees from the best European roasters, freshly roasted and delivered to your door.',
    'og_title' => 'Coffee of the Month :month | :site',
    'og_description' => 'Every month we select exceptional coffees from the best European roasters. Discover what awaits you in :month.',

    'heading_line1' => 'Coffee of',
    'heading_line2' => 'the month',
    'intro' => 'Every month we select exceptional coffees from the best European roasters. Discover what awaits you.',

    // Roasters of the month
    'roasteries' => [
        'heading' => '{0} Roasters of the Month|{1} Roaster of the Month|[2,*] Roasters of the Month',
        'subheading' => '{0} This time we selected coffees from these exceptional roasters|{1} This time we selected coffees from this exceptional roaster|[2,*] This time we selected coffees from these exceptional roasters',
        'more_about' => 'More about roaster',
    ],

    // Coffees of the month and the coffee detail modal
    'coffees' => [
        'heading' => 'Coffees of the Month',
        'subheading' => 'These specialty coffees are part of our current subscription',
    ],

    'modal' => [
        'badge' => 'Coffee of the Month',
        'parameters' => 'Parameters',
        'attributes' => [
            'origin' => 'Origin',
            'altitude' => 'Altitude',
            'processing' => 'Processing',
            'variety' => 'Variety',
            'flavor_notes' => 'Flavor notes',
        ],
        'weight' => 'Weight',
        'roast_date' => 'Roast date',
        'roast_date_format' => 'm/d/Y',
        'cta_heading' => 'Part of the current subscription',
        'cta_text' => 'Get it together with other specialty coffees',
        'cta_link' => 'Learn more',
    ],

    // Selection not published yet
    'empty' => [
        'heading' => 'Coffee of the Month coming soon',
        'text' => 'We are currently selecting exceptional coffees for :month. We will surprise you soon!',
    ],

    'learn_more_subscription' => 'Learn more about subscription',

    // Bottom subscription call to action
    'cta' => [
        'heading' => 'Want to receive similar coffees regularly?',
        'text' => 'Subscribe to our subscription and a box with selected coffee specials will be waiting for you at the pickup point.',
    ],

];
