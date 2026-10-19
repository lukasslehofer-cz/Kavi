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

];
