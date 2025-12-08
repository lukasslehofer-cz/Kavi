<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Email Translations
    |--------------------------------------------------------------------------
    */

    'common' => [
        'hello' => 'Hello',
        'thank_you' => 'Thank you',
        'regards' => 'Best regards',
        'team' => 'The KAVI Team',
        'questions' => 'If you have any questions, please don\'t hesitate to contact us.',
        'contact_email' => 'info@kavibox.com',
    ],

    'order_confirmation' => [
        'subject' => 'Order Confirmation :order_number - KAVI',
        'title' => 'Thank you for your order!',
        'greeting' => 'Hello :name,',
        'intro' => 'Thank you for your order. Here are the details:',
        'order_number' => 'Order Number',
        'order_date' => 'Order Date',
        'items' => 'Ordered Items',
        'subtotal' => 'Subtotal',
        'shipping' => 'Shipping',
        'discount' => 'Discount',
        'total' => 'Total',
        'shipping_address' => 'Shipping Address',
        'pickup_point' => 'Pickup Point',
        'invoice_attached' => 'Invoice attached',
        'track_order' => 'You will receive a tracking number once your order is shipped.',
    ],

    'subscription_confirmation' => [
        'subject' => 'Subscription Confirmation - KAVI',
        'title' => 'Welcome to KAVI!',
        'greeting' => 'Hello :name,',
        'intro' => 'Thank you for subscribing to our coffee service. Here are your subscription details:',
        'subscription_number' => 'Subscription Number',
        'plan' => 'Plan',
        'price' => 'Price per box',
        'frequency' => 'Delivery frequency',
        'next_delivery' => 'Next delivery',
        'shipping_address' => 'Shipping Address',
        'manage_subscription' => 'You can manage your subscription in your account dashboard.',
    ],

    'subscription_payment_success' => [
        'subject' => 'Payment Successful - KAVI',
        'title' => 'Payment received!',
        'greeting' => 'Hello :name,',
        'intro' => 'We have received your subscription payment.',
        'amount' => 'Amount paid',
        'next_payment' => 'Next payment date',
        'shipping_soon' => 'Your coffee box will be shipped soon.',
    ],

    'subscription_payment_failed' => [
        'subject' => 'Payment Failed - KAVI',
        'title' => 'Payment could not be processed',
        'greeting' => 'Hello :name,',
        'intro' => 'Unfortunately, we were unable to process your subscription payment.',
        'reason' => 'Reason',
        'action' => 'Please update your payment method to continue your subscription.',
        'update_payment' => 'Update Payment Method',
    ],

    'order_shipped' => [
        'subject' => 'Your Order Has Been Shipped - KAVI',
        'title' => 'Your order is on its way!',
        'greeting' => 'Hello :name,',
        'intro' => 'Good news! Your order has been shipped.',
        'tracking_number' => 'Tracking number',
        'track_package' => 'Track your package',
    ],

];

