<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Layouts and partials (layouts/app, layouts/dashboard, partials/*)
    |--------------------------------------------------------------------------
    */

    // Default meta tags, pages override them with their own sections
    'meta' => [
        'title' => 'Coffee Subscription | Specialty Coffee | KAVI',
        'description' => 'Premium specialty coffee subscription. Freshly roasted, carefully selected coffee from the best European roasteries delivered to your door.',
        'og_title' => 'KAVI - Specialty Coffee Subscription',
        'dashboard_title' => 'Dashboard - KAVI',
    ],

    // The site's Instagram profile (footer, JSON-LD on the home page)
    'social' => [
        'instagram' => 'https://www.instagram.com/kavibox_com',
    ],

    // Main menu (desktop and mobile)
    'nav' => [
        'home' => 'Home',
        'home_mobile' => 'Home',
        'logo_home' => 'KAVI, home page',
        'boxes' => 'Coffee Boxes',
        'coffee_of_month' => 'Coffee of the Month',
        'shop' => 'Shop',
        'roasteries' => 'Our Roasteries',
        'gifts' => 'Gifts',
        'account' => 'Account',
        'my_account' => 'My Account',
        'sign_in' => 'Sign In',
        'sign_in_mobile' => 'Sign In',
        'register' => 'Register',
        'sign_out' => 'Sign Out',
        'cart' => 'Cart',
        'build_box' => 'Build Your Box',
    ],

    'footer' => [
        'tagline' => 'Specialty coffee with regular deliveries straight to you. Discover a world of flavors from the best European roasteries.',
        'subscription' => 'Subscription',
        'configurator' => 'Configurator',
        'one_time_box' => 'One-time Box',
        'gift_subscription' => 'Gift Subscription',
        'coffee_of_month' => 'Coffee of the Month',
        'shop' => 'Shop',
        'all_products' => 'All Products',
        'espresso' => 'Espresso Coffee',
        'filter' => 'Filter Coffee',
        'decaf' => 'Decaf Coffee',
        'guide' => 'Coffee guide',
        // Internal links to articles we want to strengthen: article slug in the site's language => link text
        'guide_articles' => [
            'specialty-coffee-vs-regular-coffee' => 'Specialty vs. regular coffee',
            'arabica-vs-robusta-difference' => 'Arabica vs. robusta',
            'how-to-make-espresso' => 'How to make espresso at home',
            'how-to-make-coffee-at-home' => 'How to make coffee at home',
        ],
        'all_articles' => 'All articles',
        'information' => 'Information',
        'how_it_works' => 'How It Works',
        'about' => 'About Us',
        'contact' => 'Contact',
        'write_us' => 'Write Us',
        'rights' => 'All rights reserved.',
        'terms' => 'Terms of Service',
        'privacy' => 'Privacy Policy',
    ],

    'newsletter' => [
        'title' => 'Subscribe to Our Newsletter',
        'text' => 'Be the first to learn about new coffees and news from the world of specialty coffee',
        'text_dashboard' => 'Get 10% off your first order and be the first to know about new coffees',
        'placeholder' => 'Your email',
        'submit' => 'Subscribe',
        'sending' => 'Sending...',
        'error' => 'An error occurred. Please try again.',
    ],

    // Customer account (layouts/dashboard)
    'dashboard' => [
        'viewing_as' => 'Viewing user dashboard',
        'back_to_admin' => 'Back to Admin',
        'nav' => [
            'overview' => 'Dashboard',
            'profile' => 'My Profile',
            'orders' => 'Orders',
            'subscription' => 'Subscription',
            'affiliate' => 'Affiliate',
        ],
    ],

];
