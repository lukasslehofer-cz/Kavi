<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shop: product listing and product detail
    |--------------------------------------------------------------------------
    |
    | Product names, descriptions and attributes live in the database (Product getters).
    |
    */

    'shop' => 'Shop',

    'categories' => [
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'decaf' => 'Decaf',
        'accessories' => 'Accessories',
    ],

    'pagination' => [
        'page' => 'Page',
        'previous' => 'Previous',
        'next' => 'Next',
    ],

    // Bottom subscription call to action (listing and detail)
    'cta' => [
        'heading' => 'Want regular coffee delivery?',
        'text' => 'With our subscription, you save time and money. Fresh coffee delivered to your door, cancel anytime.',
        'link' => 'Learn more about subscription',
    ],

    // Product listing
    'index' => [
        'meta_description' => 'Shop specialty coffee from the best European roasteries. Espresso, filter, decaf and accessories. Fresh, quality, exceptional. Free shipping available.',
        'og_title' => 'Coffee Shop | :site',
        'og_description' => 'Carefully selected specialty coffee from the best European roasteries. Fresh, quality, exceptional.',
        'heading_line1' => 'Coffee',
        'heading_line2' => 'shop',
        'intro' => 'Carefully selected specialty coffee from the best European roasteries. Fresh, quality, exceptional.',
        'filter_all' => 'All',
        'empty' => 'No products found.',
    ],

    // Product detail
    'show' => [
        'availability' => [
            'discontinued' => 'NO LONGER AVAILABLE',
            'in_stock' => 'AVAILABILITY: IN STOCK',
            'out_of_stock' => 'AVAILABILITY: OUT OF STOCK',
        ],
        'quantity' => 'Quantity',
        'add_to_cart' => 'Add to Cart',
        'discontinued_text' => 'This product is no longer available for purchase. You can explore similar products in the recommended section below or browse our current selection.',
        'unavailable_text' => 'This product is currently unavailable.',
        'category' => 'Category',
        'attributes' => [
            'origin' => 'Origin',
            'processing' => 'Processing',
            'flavor_notes' => 'Flavor',
            'altitude' => 'Altitude',
            'variety' => 'Variety',
        ],
        'weight' => 'Weight',
        'roast_date' => 'Roast date',
        'roast_date_format' => 'm/d/Y',
        'related_heading' => 'You might also like',
        'blog_tip' => 'Blog tip:',
    ],

];
