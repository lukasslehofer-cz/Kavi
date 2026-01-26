<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Checkout Page Translations
    |--------------------------------------------------------------------------
    */

    // Page titles
    'page_title' => 'Checkout - KAVI',
    'page_title_subscription' => 'Checkout - Coffee Subscription',
    'title' => 'Complete your order',
    'title_subscription' => 'Complete your subscription order',
    'subtitle' => 'Just a few more details and your coffee will be on its way to you',
    'subtitle_subscription' => 'Just a few more details and your coffee will be regularly on its way to you',
    
    // Order summary
    'order_summary' => 'Order summary',
    'subscription_summary' => 'Subscription summary',
    'subtotal' => 'Subtotal',
    'subtotal_without_vat' => 'Subtotal (excl. VAT)',
    'shipping' => 'Shipping',
    'shipping_free' => 'Free',
    'shipping_free_subscription' => 'Free (subscription)',
    'shipping_free_coupon' => 'Free (coupon)',
    'shipping_free_digital' => 'Free (digital product)',
    'shipping_calculating' => 'Calculating...',
    'shipping_at_checkout' => 'Will be calculated at checkout',
    'shipping_unavailable' => 'Unavailable',
    'shipping_error' => 'Error',
    'discount' => 'Discount',
    'vat' => 'VAT (21%)',
    'total' => 'Total',
    'total_per_month' => 'Total / month',
    'incl_vat' => '(incl. VAT)',
    'plus_shipping' => '+ shipping',
    
    // Contact information
    'contact_info' => 'Contact information',
    'have_account' => 'Already have an account?',
    'login_faster' => 'Log in for faster checkout or we\'ll send you a login link.',
    'login_faster_subscription' => 'Log in for faster checkout.',
    'login' => 'Log in',
    'send_magic_link' => 'Send login link',
    'magic_link_title' => 'Send login link',
    'magic_link_description' => 'Enter your email and we\'ll send you a login link.',
    'email_confirmation_note' => 'We\'ll send confirmation and registration link to this email.',
    'phone_helps' => 'Phone helps delivery service resolve any issues.',
    'phone_format_hint' => 'For CZ: +420 and 9 digits, for SK: +421 and 9 digits',
    
    // Billing address
    'billing_address' => 'Billing address',
    
    // Form fields
    'fields' => [
        'name' => 'Full name',
        'email' => 'Email',
        'phone' => 'Phone',
        'phone_optional' => 'Phone (optional)',
        'street' => 'Street and house number',
        'street_placeholder' => 'e.g. Main Street 123',
        'city' => 'City',
        'city_placeholder' => 'e.g. London',
        'postal_code' => 'Postal code',
        'postal_code_placeholder' => '12345',
        'country' => 'Country',
        'select_country' => 'Select country',
        'notes' => 'Note',
        'notes_optional' => '/ OPTIONAL',
        'notes_placeholder' => 'E.G. PLEASE RING 2ND FLOOR OR LEAVE AT RECEPTION',
    ],
    
    // Pickup point
    'pickup_point' => [
        'title' => 'Select pickup point',
        'selected' => 'Selected pickup point:',
        'change' => 'Change',
        'select' => 'Select pickup point',
        'info' => 'Coffee will be delivered to the selected pickup point',
    ],
    
    // Digital product
    'digital_product' => [
        'title' => 'Digital product',
        'description' => 'Your product will be delivered electronically to the provided email. No pickup point needed.',
    ],
    
    // Subscription addon
    'subscription_addon' => [
        'title' => 'Ship with subscription option',
        'checkbox_label' => 'Include in next subscription shipment',
        'free_shipping' => 'FREE SHIPPING',
        'capacity_full' => 'CAPACITY FULL',
        'select_subscription' => 'Select subscription:',
        'capacity_label' => 'Add-on capacity:',
        'slots_available' => 'available slots',
        'used_slot' => 'Used slot',
        'cart_slot' => 'Cart',
        'free_slot' => 'Free slot',
        'planned_delivery' => '📦 Planned shipment:',
        'capacity_warning' => '❌ Add-on capacity is full for this subscription.',
        'try_another' => 'Try selecting another subscription.',
        'cart_has' => 'Cart contains',
        'items' => 'items',
        'but_only' => 'but only',
        'slot_available' => 'slot available',
        'slots_available_plural' => 'slots available',
        'info' => 'If you check this option, items will be shipped with your subscription and you don\'t pay shipping. You can add up to 3 items per shipment. <strong>Items will be delivered to the pickup point set for your subscription.</strong>',
    ],
    
    // Payment
    'payment' => [
        'title' => 'Payment method',
        'card' => 'Credit/Debit card',
        'card_description' => 'After submitting your order, you\'ll be redirected to a secure payment gateway',
        'we_accept' => 'We accept:',
        'card_info' => 'CARD PAYMENT. AFTER SUBMITTING YOUR ORDER, YOU WILL BE REDIRECTED TO A SECURE PAYMENT GATEWAY. WE ACCEPT VISA, MASTERCARD, APPLE PAY AND GOOGLE PAY.',
        'card_description_full' => 'AFTER SUBMITTING YOUR ORDER, YOU WILL BE REDIRECTED TO A SECURE PAYMENT GATEWAY. WE ACCEPT VISA, MASTERCARD, APPLE PAY AND GOOGLE PAY.',
    ],
    
    // Coupon
    'coupon' => [
        'title' => 'I have a discount code',
        'placeholder' => 'DISCOUNTCODE',
        'apply' => 'Apply',
        'remove' => 'Remove',
        'applied' => 'Coupon applied',
        'discount_label' => 'Discount',
    ],
    
    // 100% discount notice
    'full_discount' => [
        'title' => '🎉 100% discount!',
        'description' => 'Your subscription is <strong>completely free</strong> thanks to coupon :code. After completing your order, the subscription will be activated immediately.',
    ],
    
    // Subscription details
    'subscription' => [
        'quantity' => 'Quantity:',
        'bags' => 'bags',
        'coffee_type' => 'Coffee type:',
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'mix' => 'Mix',
        'incl_decaf' => '(incl. 1× decaf)',
        'frequency' => 'Frequency:',
        'every_month' => 'Every month',
        'every_2_months' => 'Every 2 months',
        'every_3_months' => 'Every 3 months',
        'bags_without_vat' => 'bags of coffee (excl. VAT):',
    ],
    
    // Delivery info
    'delivery_info' => [
        'title' => 'Delivery information',
        'shipping_note' => 'Shipping happens on the <strong>20th of each month</strong>. Orders by the 15th are included in the current month\'s shipment, orders from the 16th are included in the next shipment.',
    ],
    
    // Buttons
    'buttons' => [
        'complete_order' => 'Complete order',
        'back_to_cart' => 'Back to cart',
        'back_to_configurator' => 'Back to configurator',
    ],
    
    // Terms
    'terms' => [
        'agree' => 'I agree to the',
        'terms_of_service' => 'terms of service',
        'and' => 'and',
        'privacy_policy' => 'privacy policy',
    ],
    
    // Trust badges
    'trust' => [
        'secure_payment' => 'Secure payment',
        'eco_packaging' => 'Eco-friendly packaging',
        'coffee_from_europe' => 'Coffee from all over Europe',
        'no_commitment' => 'No commitment - cancel anytime',
        'fresh_coffee' => 'Freshly roasted coffee',
        'free_shipping_always' => 'Freshly roasted & shipped with care',
    ],
    
    // Errors
    'errors' => [
        'country_unavailable' => 'We don\'t deliver to the selected country at the moment.',
        'subscription_country_unavailable' => 'We don\'t deliver subscriptions to the selected country at the moment.',
    ],
    
    // Messages
    'messages' => [
        'cart_empty' => 'Your cart is empty.',
        'payment_cancelled' => 'Payment was cancelled. You can continue with your order or modify the cart.',
    ],

];
