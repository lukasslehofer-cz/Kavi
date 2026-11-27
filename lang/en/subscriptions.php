<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription Page Translations
    |--------------------------------------------------------------------------
    */

    'title' => 'Coffee Subscription',
    'subtitle' => 'Discover exceptional specialty coffee delivered to your door',
    
    'configurator' => [
        'title' => 'Build Your Own Box',
        'step1' => 'Choose your box size',
        'step2' => 'Select coffee type',
        'step3' => 'Choose delivery frequency',
        'step4' => 'Enter shipping details',
    ],
    
    'box_sizes' => [
        'm_box' => 'M Box',
        'l_box' => 'L Box',
        'xl_box' => 'XL Box',
        'bags' => ':count bags × 250g',
        'coffees' => ':count specialty coffees',
    ],
    
    'coffee_types' => [
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'mix' => 'Mix',
        'omni' => 'Omni-roast',
        'decaf' => 'Decaf',
    ],
    
    'frequency' => [
        'monthly' => 'Monthly',
        'bimonthly' => 'Every 2 months',
        'quarterly' => 'Every 3 months',
    ],
    
    'features' => [
        'free_shipping' => 'Lovingly packed',
        'cancel_anytime' => 'Cancel or pause anytime',
        'fresh_roasted' => 'Freshly roasted coffee',
        'curated' => 'Hand-picked selection',
    ],
    
    'pricing' => [
        'per_box' => '/box',
        'per_month' => '/month',
        'starting_from' => 'Starting from',
    ],
    
    'cta' => [
        'subscribe' => 'Subscribe Now',
        'configure' => 'Build Your Box',
        'one_time' => 'Try One-Time Box',
    ],
    
    'coffee_of_month' => [
        'title' => 'Coffee of the Month',
        'subtitle' => 'Discover this month\'s selection',
        'explore' => 'Explore coffees for :month',
    ],
    
    'how_it_works' => [
        'title' => 'How It Works',
        'step1_title' => 'Choose Your Plan',
        'step1_desc' => 'Select the coffee quantity and delivery frequency to suit your needs',
        'step2_title' => 'Personalize',
        'step2_desc' => 'Choose your coffee type, roast preference, and delivery address',
        'step3_title' => 'Receive Your Box',
        'step3_desc' => 'Freshly roasted coffee delivered to your chosen pickup point',
        'step4_title' => 'Enjoy',
        'step4_desc' => 'Savor exceptional specialty coffee and look forward to your next box',
    ],

    // Paying for a shipment skipped because of a failed payment (link from e-mail)
    'pay_skipped' => [
        'page_title' => 'Shipment payment',
        'success_badge' => 'Paid',
        'success_heading' => 'Thank you, your box is on its way',
        'success_text' => 'Your payment went through. We will send the box with the next shipment and we saved your card for future subscription payments. A confirmation is on its way to your inbox.',
        'processing_text' => 'We are still confirming your payment. We will e-mail you as soon as Stripe confirms it.',
        'details_title' => 'Your subscription',
        'subscription_number' => 'Subscription number',
        'shipment' => 'Box ships on',
        'paid' => 'Paid',
        'card' => 'Card for future payments',
        'next_payment' => 'Next payment',
        'cancelled_badge' => 'Payment cancelled',
        'cancelled_heading' => 'The payment did not go through',
        'cancelled_text' => 'You cancelled the payment. If you would like the box, go back to the link in the e-mail, it is valid until midnight today.',
        'expired_badge' => 'Link expired',
        'expired_heading' => 'This link is no longer valid',
        'expired_text' => 'The payment link was valid only on the day the e-mail was sent. Your subscription continues with the next shipment. If you would still like the box, write to us at :email.',
        'done_badge' => 'Done',
        'done_heading' => 'This shipment is settled',
        'done_text' => 'This shipment is already paid or can no longer be paid. You can check your subscription in your account.',
        'error_badge' => 'Error',
        'error_heading' => 'We could not open the payment',
        'error_text' => 'Please try again in a moment or write to us at :email.',
        'account_link' => 'Go to my account',
    ],

    // FAQ below the configurator (redesign 2026-10)
    'faq' => [
        ['q' => 'What if I don\'t like the coffee?', 'a' => 'Write to us at info@kavibox.com. You can change your coffee type in your account at any time, for example from filter to espresso.'],
        ['q' => 'How do I cancel my subscription?', 'a' => 'With one click in your account, no fees. You can also pause it or switch to a smaller box.'],
        ['q' => 'Do you send ground coffee?', 'a' => 'No, we only send whole beans. Coffee tastes best freshly ground, so we recommend grinding it just before brewing.'],
    ],

    // Box configurator (/subscription, subscriptions/index.blade.php)
    'builder' => [
        'seo' => [
            'title' => 'Build your coffee box – size, coffee, frequency | KAVI',
            'description' => 'Choose your box size, coffee type and how often you want it delivered. No commitment, cancel anytime.',
            'og_title' => 'Build Your Coffee Subscription Box | KAVI',
            'og_description' => 'Flexible coffee subscription. Choose your box size, coffee type, and delivery frequency. Cancel anytime.',
            // inLanguage in JSON-LD
            'in_language' => 'en-US',
        ],
        'hero' => [
            'title_1' => 'Build your',
            'title_2' => 'coffee box',
            'intro' => 'Every month we send you coffees from three European roasteries you rarely find in local shops. Here you set the size and frequency. New to KAVI?',
            'intro_link' => 'See how KAVI works',
            'lead' => 'Choose the quantity, coffee type and delivery frequency. Flexible subscription tailored to your needs. Cancel anytime without fee.',
        ],
        'preparing' => [
            'title' => 'We\'re preparing new coffees for you',
            'text' => 'We\'re working on selecting the best coffees for the upcoming month. New coffees will be available in the coming days.',
        ],
        'sold_out' => [
            'title' => 'Sorry, our coffees are sold out this month',
            'text' => 'Thank you for your interest! All our coffees for this month are unfortunately already taken.',
            'next_month' => 'Subscription for next month available from',
            'date_format' => 'm/d/Y',
        ],
        'browse_shop' => 'Browse shop',
        'amount' => [
            'title' => 'Coffee quantity',
            'subtitle' => 'Choose the package that suits you',
            // "500g · 2 bags" (2–4 bags)
            'bags' => 'bags',
            'popular' => 'Popular',
            'm_desc' => 'Ideal for individuals or couples',
            'l_desc' => 'Most popular choice',
            'xl_desc' => 'For coffee enthusiasts',
        ],
        'type' => [
            'title' => 'Preferred coffee type',
            'subtitle' => 'Choose your favorite brewing method',
            'espresso' => 'Espresso',
            'espresso_desc' => 'Full body, darker roast',
            'filter' => 'Filter',
            'filter_desc' => 'Lighter body, lighter roast',
            'mix' => 'Mix',
            'mix_desc' => 'Espresso and filter',
            'decaf' => '1x decaf',
            'distribution' => 'Coffee distribution',
        ],
        'frequency' => [
            'title' => 'Delivery frequency',
            'subtitle' => 'How often do you want to receive coffee?',
            'monthly' => 'Every month',
            'monthly_desc' => 'For regular consumption',
            'bimonthly' => 'Every 2 months',
            'bimonthly_desc' => 'For medium consumption',
            'quarterly' => 'Every 3 months',
            'quarterly_desc' => 'For occasional drinking',
            'one_time' => 'One-time',
            'one_time_badge' => 'Try it',
            'one_time_desc' => 'No subscription',
            // In the summary once selected
            'one_time_summary' => 'One-time (no subscription)',
            // :amount = one-time box surcharge (100 Kč / €5)
            'upsell' => 'With a subscription you save :amount on every box. You can cancel it anytime with one click.',
            'switch' => 'Switch to subscription',
        ],
        'summary' => [
            'order_title' => 'Order summary',
            'subscription_title' => 'Subscription summary',
            'quantity' => 'Quantity:',
            'type' => 'Coffee type:',
            'frequency' => 'Frequency:',
            'one_time_surcharge' => 'One-time surcharge:',
            'price_per_box' => 'Price per box:',
            'total_price' => 'Total price:',
            'per_delivery' => 'per delivery',
            // Below the price once a frequency is selected (0 = one-time). Free shipping is promised on kavi.cz only.
            'frequency_notes' => [
                0 => 'one-time order',
                1 => 'every month',
                2 => 'every 2 months',
                3 => 'every 3 months',
            ],
            // "750g (3 bags)"
            'bags' => 'bags',
            'sold_out' => 'Sold out this month',
            'submit' => 'Continue to checkout',
            'no_commitment' => 'No commitment',
            'cancel_anytime' => 'Cancel anytime',
            // Number format of the price in JS (toLocaleString)
            'number_locale' => 'en-US',
        ],
        'shipping' => [
            'title' => 'Next shipping date',
            'text' => 'Orders for this box close <strong class="text-dark-800">:cutoff at midnight</strong>. The coffee ships on <strong class="text-dark-800">:shipment</strong>.',
        ],
        // Coffees of the month below the configurator; :month = months.adjective_locative
        'lineup' => [
            'title' => 'In the :month box',
            'lead' => 'Your box will contain two to four of these coffees, depending on its size.',
            'link' => 'More about the coffee of the month',
        ],
    ],

];
