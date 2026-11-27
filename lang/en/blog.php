<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Blog: article listing and article
    |--------------------------------------------------------------------------
    |
    | Article titles, perexes and content live in the database (Post getters).
    |
    */

    'title' => 'Blog',
    'author' => 'By',

    'pagination' => [
        'page' => 'Page',
        'previous' => 'Previous',
        'next' => 'Next',
    ],

    // Article listing
    'index' => [
        'meta_description' => 'Coffee blog with tips, recipes, and stories from the world of specialty coffee. Discover the art of brewing and learn from the best roasters.',
        'og_title' => 'Coffee Blog | :site',
        'og_description' => 'Discover the art of specialty coffee. Tips, recipes, and stories from the best roasters.',
        'heading_line1' => 'Coffee',
        'heading_line2' => 'blog',
        'intro' => 'Tips, recipes, and stories from the world of specialty coffee. Discover the art of brewing.',
        'filter_all' => 'All',
        'showing_tag' => 'Showing articles tagged',
        'empty' => 'No articles found.',
        'empty_text' => 'Check back soon for new content.',
        'cta_heading' => 'Want to try our coffee?',
        'cta_text' => 'Discover our selection of specialty coffee from the best European roasteries.',
        'cta_link' => 'Browse coffee shop',
    ],

    // Article
    'show' => [
        'tags' => 'Tags',
        'back' => 'Back to blog',
        'related' => 'Related articles',
        'promo_heading' => 'Start your coffee journey today',
        'promo_text' => 'Get access to the best coffee from all over Europe. Flexible subscription, no commitment.',
        'promo_subscription' => 'Build your coffee box',
        'promo_gift' => 'Gift subscription',
        'promo_shop' => 'Browse coffees',
    ],

];
