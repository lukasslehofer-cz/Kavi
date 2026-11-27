<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Homepage Translations
    |--------------------------------------------------------------------------
    */

    // Home page meta tags (home.blade.php)
    'meta' => [
        'title' => 'Coffee Subscription | Specialty Coffee | KAVI',
        'description' => 'Discover the best specialty coffee from all over Europe. Freshly roasted, carefully selected, delivered to your door. Flexible subscription, shipped with care.',
        'og_title' => 'KAVI - Specialty Coffee Subscription from Europe',
        'og_description' => 'Premium specialty coffee delivered monthly. Discover unique flavors from the best European roasteries. Shipped with care, cancel anytime.',
    ],

    // Home page structured data (JSON-LD)
    'schema' => [
        'description' => 'Premium specialty coffee subscription. Freshly roasted coffee from the best European roasteries delivered to your door.',
        'language' => 'English',
        'in_language' => 'en-US',
    ],

    'hero' => [
        'badge' => 'Something new every month',
        'title' => 'Discover the best coffee from across Europe',
        'subtitle' => 'Premium coffee subscription. Freshly roasted, carefully selected, delivered to you.',
        'cta_primary' => 'Build Your Own Box',
        'cta_secondary' => 'Shop Coffee',

        // Video hero (redesign 2026-10)
        'tagline' => 'Specialty coffee subscription from Europe\'s finest roasters.',
        'kicker' => 'Coffee subscription',
        'headline_top' => 'Three European',
        'headline_accent' => 'roasters',
        'headline_bottom' => 'every month',
        'lead' => 'Specialty coffee from micro-roasters you rarely find in local shops. No commitment.',
        'cta_box' => 'Build your box',
        'cta_gift' => 'Give a subscription',
        'cta_shop' => 'Shop coffee',
    ],

    // "What arrives at your door" section
    'box' => [
        'image_alt' => 'An open KAVI box with three bags of coffee',
        'title' => 'What arrives at your door',
        'lead' => 'Whole-bean coffee from European roasters, freshly roasted and delivered to your pickup point.',
        'items' => [
            '2 to 4 bags of 250 g, depending on the box size',
            'Coffees from three European roasters, different every month',
            'A postcard with the coffee and roaster profile for every coffee',
            'Delivered by Packeta, pause or cancel with one click',
        ],
        'cta_box' => 'Build your box',
        'cta_coffee_of_month' => 'Coffee of the Month',
    ],

    // "How it works" section
    'how' => [
        'title' => 'How it works',
        'subtitle' => 'Four simple steps to perfect coffee',
        'steps' => [
            1 => ['title' => 'Choose your plan', 'text' => 'Select coffee quantity and delivery frequency according to your needs'],
            2 => ['title' => 'Personalize', 'text' => 'Choose coffee type, brewing method and delivery address'],
            3 => ['title' => 'Pick up your box', 'text' => 'We deliver freshly roasted coffee to your chosen location'],
            4 => ['title' => 'Enjoy', 'text' => 'Enjoy great specialty coffee and look forward to your next coffee box'],
        ],
    ],

    // "Give coffee" section
    'gifts' => [
        'aria' => 'Gifts',
        'season' => 'Christmas :year',
        'title' => 'Give',
        'title_accent' => 'coffee',
        'text' => 'The certificate arrives by email straight away as a printable PDF:season_note. You pay once, nothing renews by itself.',
        'season_note' => ', in time even on Christmas Eve',
        'from' => 'from :price',
        'or' => 'or',
        'months_3' => '3 months',
        'months_3_desc' => '3 boxes, one every month',
        'months_6' => '6 months',
        'months_6_desc' => '6 boxes, one every month',
        'voucher' => 'Voucher',
        'voucher_desc' => ':amounts for the shop and boxes',
        // Query parameter of the /gifts link: only values GiftController::index understands (typ|type, poukaz|voucher)
        'query_param' => 'type',
        'query_voucher' => 'voucher',
    ],
    
    'features' => [
        'title' => 'Why Choose KAVI?',
        'subtitle' => 'We know what makes coffee exceptional. And we love sharing it with you.',
        
        'fresh_title' => 'Fresh Coffee',
        'fresh_desc' => 'Each roastery delivers freshly roasted coffee for your coffee boxes.',
        
        'flavors_title' => 'Exceptional Flavors',
        'flavors_desc' => 'No boring, monotonous coffees. Discover new flavors from around the world every month.',
        
        'shipping_title' => 'Shipped with Care',
        'shipping_desc' => 'Freshly roasted, lovingly packed by our team, and delivered right to your door.',
    ],
    
    'love_coffee' => [
        'title' => 'Coffee You\'ll Love',
        'subtitle' => 'We carefully select the highest quality coffee from trusted roasteries. Discover new flavors at home every month.',
        
        'feature1_title' => 'Exclusive Selection',
        'feature1_desc' => 'We bring you coffee from roasteries not available locally.',
        
        'feature2_title' => 'Unbeatable Price',
        'feature2_desc' => 'Coffee in our boxes is better value than anywhere else.',
        
        'feature3_title' => 'Flexible Subscription',
        'feature3_desc' => 'Adjust quantity and frequency to suit your needs.',
        
        'cta' => 'Start Subscription',
    ],
    
    'plans' => [
        'title' => 'Choose Your Perfect Coffee Box',
        'subtitle' => 'Flexible subscription tailored to your needs. Cancel anytime, no fees.',
        'popular' => 'Most Popular',
        'select' => 'Select',
        'note' => 'Further customization available in the next step.',
    ],
    
    'testimonials' => [
        'title' => 'What Our Customers Say',
        'subtitle' => 'Join our community of happy coffee lovers',
    ],
    
    'impact' => [
        'title' => 'Coffee with Positive Impact',
        'subtitle' => 'At KAVI, we believe specialty coffee can change the world. From every coffee box, we donate to Water.org, providing access to clean water in developing countries. Each box provides one person with water for six months.',
        'water_stat' => '6 months',
        'water_label' => 'of water per box',
        'partner_label' => 'direct donation',

        // Water.org section on the home page (redesign 2026-10)
        'mission' => 'At KAVI, we believe specialty coffee can change the world. From every coffee box, we donate to Water.org, which provides access to clean water in developing countries.',
        'headline' => 'Every box provides one person with water for half a year',
        'water_months' => '6 months of water',
        'direct' => 'Direct donation',
    ],
    
    'cta_final' => [
        'title' => 'Start Your Coffee Journey Today',
        'subtitle' => 'Get access to the best coffee from across Europe. Flexible subscription, no commitments.',
        'primary' => 'Choose Subscription',
        'secondary' => 'Browse Coffees',
    ],

    // Homepage FAQ (redesign 2026-10). HomeController fills in :cutoff and
    // :shipment from the current ShipmentSchedule cycle.
    'faq' => [
        ['q' => 'When will my first box arrive?', 'a' => 'Orders close :cutoff at midnight and ship on :shipment via Packeta to your pickup point. After that, boxes ship around the 20th of each month.'],
        ['q' => 'Can I pause or cancel my subscription?', 'a' => 'Yes, anytime in your account and without fees. Changes apply to every box whose order deadline has not passed yet.'],
        ['q' => 'Which coffees will I get?', 'a' => 'Every month, coffees from three European roasters. A roaster returns roughly once a year, so coffees rarely repeat.'],
        ['q' => 'Espresso or filter?', 'a' => 'Espresso has a fuller body and a darker roast, filter a lighter body and a lighter roast. Mix sends both.'],
        ['q' => 'Can I give a subscription as a gift?', 'a' => 'Yes, for 3 or 6 months paid upfront, with no automatic renewal.', 'link' => ['label' => 'Gift subscription', 'route' => 'gifts.index']],
    ],

];
