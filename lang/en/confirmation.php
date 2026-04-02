<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Confirmation Page Translations (English)
    |--------------------------------------------------------------------------
    |
    | Shared by checkout/confirmation.blade.php and subscriptions/confirmation.blade.php
    |
    */

    // Page titles
    'page_title' => 'Order Confirmation - KAVI',
    'page_title_subscription' => 'Subscription Confirmation - KAVI',

    // Date format
    'date_format' => 'M j, Y',

    // Cancelled payment
    'cancelled' => [
        'heading_1' => 'Payment',
        'heading_2' => 'was not completed',
        'order_number' => 'Order number',
        'pay_again' => 'Pay again',
        'back_home' => 'Back to homepage',
    ],

    // Success header
    'success' => [
        'badge' => 'Order successfully created',
        'heading_1' => 'Thank you for',
        'heading_2' => 'your order',
        'order_number' => 'Order number',
        'badge_order' => 'Order confirmed',
        'badge_subscription' => 'Subscription activated',
        'desc_onetime' => 'Your one-time box order has been successfully confirmed. We will ship it on the nearest shipping date.',
        'desc_subscription' => 'Your subscription has been successfully created and is now active. We will ship your first box on the nearest shipping date.',
    ],

    // Subscription addon
    'addon' => [
        'badge' => 'Shipped with subscription',
        'text' => 'Your items will be added to the next subscription shipment',
        'shipped_on' => 'and shipped together on',
        'per_schedule' => 'per shipping schedule',
        'free_shipping' => 'Free shipping',
    ],

    // Products section
    'products' => [
        'section_title' => 'Ordered products',
    ],

    // Pricing
    'pricing' => [
        'discount_label' => 'Discount :code',
        'vat' => 'VAT (:rate%)',
        'vat_generic' => 'VAT',
    ],

    // Contact section
    'contact' => [
        'section_title' => 'Contact information',
    ],

    // Delivery
    'delivery' => [
        'pickup_point' => 'Pickup point',
        'delivery_address' => 'Delivery address',
        'packeta' => 'Packeta',
    ],

    // Subscription details
    'subscription_details' => [
        'section_title_order' => 'Your order',
        'section_title_subscription' => 'Your subscription',
        'quantity' => 'Quantity',
        'bags_format' => ':count bags (:grams g)',
        'coffee_type' => 'Coffee type',
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'mix' => 'Mix',
        'incl_decaf' => '(incl. 1× decaf)',
        'frequency' => 'Frequency',
        'frequency_0' => 'One-time box',
        'frequency_1' => 'Every month',
        'frequency_2' => 'Every 2 months',
        'frequency_3' => 'Every 3 months',
        'price' => 'Price',
        'first_shipment' => 'First shipment',
        'shipment' => 'Shipment',
        'shipping_day_note' => '20th of each month',
        'next_payment' => 'Next payment',
        'payment_day_note' => '15th of each month',
    ],

    // Coupon
    'coupon' => [
        'activated' => 'Discount :code activated',
        'discount' => 'Discount',
        'discounted_price' => 'Discounted price',
        'valid_until' => 'Discount valid until',
        'full_price_from' => 'Full price from',
    ],

    // Next steps
    'next_steps' => [
        'title' => 'What\'s next?',
        'email_title' => 'Email confirmation',
        'email_desc' => 'We\'ve sent a confirmation with order details to your email',
        'email_desc_order' => 'We\'ve sent a confirmation with order details to your email',
        'email_desc_subscription' => 'We\'ve sent a confirmation with subscription details to your email',
        'processing_title' => 'Order processing',
        'processing_desc' => 'We\'re preparing your order for shipment',
        'tracking_title' => 'Shipment tracking',
        'tracking_desc' => 'Once shipped, we\'ll send you a tracking number',
        'shipment_title' => 'Shipment',
        'first_shipment_title' => 'First shipment',
        'shipment_desc' => 'We\'ll ship your coffee on the next shipping date (20th of each month)',
        'no_commitment_title' => 'No commitment',
        'no_commitment_desc' => 'One-time purchase with no subscription. No further payments will be made.',
        'manage_title' => 'Manage subscription',
        'manage_desc_auth' => 'You can modify or cancel your subscription anytime from your dashboard',
        'manage_desc_guest' => 'Create an account to manage your subscription - we\'ve sent you a link via email',
    ],

    // Action buttons
    'actions' => [
        'view_orders' => 'View orders',
        'continue_shopping' => 'Continue shopping',
        'view_order' => 'View order',
        'view_subscription' => 'View subscription',
        'login' => 'Log in',
        'back_home' => 'Back to homepage',
    ],

    // Payment status
    'payment_status' => [
        'paid' => 'Payment successful',
        'pending' => 'Awaiting payment',
        'pay_now' => 'Pay now',
    ],

    // Help
    'help' => [
        'need_help' => 'Need help?',
    ],

];
