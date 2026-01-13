<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Email Translations - English
    |--------------------------------------------------------------------------
    */

    'common' => [
        'hello' => 'Hello',
        'thank_you' => 'Thank you',
        'regards' => 'Best regards',
        'team' => 'The KAVIbox Team',
        'questions' => 'If you have any questions, please don\'t hesitate to contact us.',
        'contact_email' => 'info@kavibox.com',
        'site_name' => 'KAVIbox.com',
        'tagline' => 'Premium Coffee Subscription',
        'copyright' => '© :year KAVIbox.com. All rights reserved.',
        'home' => 'Home',
        'shop' => 'Shop',
        'subscription' => 'Subscription',
        'my_account' => 'My Account',
        'my_subscription' => 'My Subscription',
        'edit_profile' => 'Edit Profile',
        'freshly_roasted' => 'Freshly Roasted Coffee',
        'delivery_time' => 'Delivery within 3 days',
        'support_24_7' => '24/7 Customer Support',
        'free' => 'Free',
    ],

    // Welcome Email
    'welcome' => [
        'subject' => 'Welcome to KAVI! ☕ Start Your Coffee Journey',
        'title' => 'Welcome to KAVI! ☕',
        'subtitle' => 'We\'re glad you joined us. Here are a few tips to get you started.',
        'greeting' => 'Hi, :name!',
        'intro' => 'Welcome to our community of coffee lovers! At KAVI, we believe that good coffee can brighten any day. We look forward to discovering the best flavors from around the world together.',
        'how_to_start' => 'How to get started?',
        'step1_title' => 'Explore our selection',
        'step1_text' => 'We offer freshly roasted coffee from the best roasteries across Europe.',
        'step2_title' => 'Try a subscription',
        'step2_text' => 'Every month, we\'ll deliver selected coffee samples straight to your door.',
        'step3_title' => 'Discover new flavors',
        'step3_text' => 'Every coffee has its own story. Get ready to explore!',
        'explore_subscription' => 'Explore Subscription',
        'what_we_offer' => 'What we offer',
        'offer_premium' => 'Premium Coffee - Only the best quality',
        'offer_europe' => 'From all over Europe - Small roasteries, big flavors',
        'offer_subscription' => 'Coffee Subscription - Discover new varieties',
        'offer_gift' => 'Gift Boxes - The perfect gift for coffee lovers',
        'offer_advice' => 'Expert Advice - How to brew the perfect cup',
        'your_account' => 'Your Account',
        'email_label' => 'Email',
        'name_label' => 'Name',
        'account_info' => 'You can manage all your information in your account.',
        'need_help' => 'Need help?',
        'help_intro' => 'We\'re here for you! If you have any questions, don\'t hesitate to contact us:',
        'email' => 'Email',
        'chat' => 'Chat: On our website',
        'faq' => 'FAQ: Frequently Asked Questions',
        'looking_forward' => 'We look forward to your coffee journey with us!',
        'with_love' => 'With love for good coffee',
    ],

    // Magic Login Link
    'magic_login' => [
        'subject' => 'Login Link - KAVI',
        'title' => 'Login Link',
        'subtitle' => 'You received this email because a login link was requested for your KAVI account.',
        'login_button' => 'Log In to Your Account',
        'expires_title' => 'Link Validity',
        'expires_text' => 'This link is valid for :minutes minutes. After it expires, you\'ll need to request a new link.',
        'security_title' => 'For security reasons:',
        'security_once' => 'The link works only once',
        'security_ignore' => 'If you didn\'t request to log in, please ignore this email',
        'security_share' => 'Never share this link with anyone else',
        'button_not_working' => 'Button not working? Copy this link:',
        'help_text' => 'If you have any problems logging in, please contact us at',
        'footer_text' => 'This email was sent because you requested a login link for your KAVI account.',
    ],

    // Order Confirmation
    'order_confirmation' => [
        'subject' => 'Order Confirmation :order_number - KAVI',
        'title' => 'Thank you for your order!',
        'subtitle' => 'Your order has been successfully received and will be shipped soon.',
        'order_number' => 'Order Number',
        'order_contents' => 'Order Contents',
        'subtotal_without_vat' => 'Subtotal (excl. VAT)',
        'vat' => 'VAT (21%)',
        'shipping' => 'Shipping',
        'discount' => 'Discount',
        'total' => 'Total',
        'delivery' => 'Delivery',
        'pickup_point' => 'Pickup Point',
        'billing_info' => 'Billing Information',
        'email_label' => 'Email',
        'phone_label' => 'Phone',
        'payment' => 'Payment',
        'payment_received' => 'Payment was successfully received.',
        'payment_pending' => 'Payment has not been completed yet. Please complete the payment to process your order.',
        'view_order' => 'View Order Details',
        'help_text' => 'If you have any questions about your order, please contact us at',
        'footer_text' => 'This email was sent to :email because you placed an order on our website.',
    ],

    // Subscription Confirmation
    'subscription_confirmation' => [
        'subject' => 'Subscription Confirmation - KAVI',
        'title' => 'Welcome to KAVI! ☕',
        'subtitle' => 'Thank you for subscribing to our coffee service.',
        'greeting' => 'Hello :name,',
        'intro' => 'Thank you for subscribing to our coffee service. Here are your subscription details:',
        'subscription_number' => 'Subscription Number',
        'plan' => 'Plan',
        'price' => 'Price per box',
        'frequency' => 'Delivery frequency',
        'next_delivery' => 'Next delivery',
        'shipping_address' => 'Shipping Address',
        'your_config' => 'Your Configuration',
        'bags_count' => 'Number of bags',
        'grind_label' => 'Grind',
        'manage_subscription' => 'You can manage your subscription in your account dashboard.',
        'manage_button' => 'Manage Subscription',
        'what_next' => 'What happens next?',
        'step1' => 'We\'ll prepare your coffee box with carefully selected coffees',
        'step2' => 'We\'ll ship the box before your next delivery date',
        'step3' => 'You\'ll receive an email with tracking information',
        'help_text' => 'If you have any questions, please contact us at',
    ],

    // One-time Box Confirmation
    'onetime_box' => [
        'subject' => 'Order Confirmation :subscription_number - KAVI',
        'title' => 'Thank you for your order! ☕',
        'subtitle' => 'Your one-time coffee box is on its way.',
        'box_number' => 'Order Number',
        'price' => 'Price',
        'your_config' => 'Your Configuration',
        'bags_count' => 'Number of bags',
        'grind_label' => 'Grind',
        'delivery' => 'Delivery',
        'what_next' => 'What happens next?',
        'step1' => 'We\'ll prepare your coffee box with carefully selected coffees',
        'step2' => 'We\'ll ship the box as soon as possible',
        'step3' => 'You\'ll receive an email with tracking information',
        'view_order' => 'View Order',
    ],

    // Subscription Payment Success
    'subscription_payment_success' => [
        'subject' => 'Payment Successful - KAVI',
        'title' => 'Payment Received! ✓',
        'subtitle' => 'Thank you for your payment.',
        'greeting' => 'Hello :name,',
        'intro' => 'We have received your subscription payment.',
        'subscription_number' => 'Subscription Number',
        'amount' => 'Amount Paid',
        'payment_date' => 'Payment Date',
        'next_payment' => 'Next Payment Date',
        'shipping_soon' => 'Your coffee box will be shipped soon.',
        'view_subscription' => 'View Subscription',
    ],

    // Subscription Payment Failed
    'subscription_payment_failed' => [
        'subject' => 'Payment Failed - KAVI',
        'title' => 'Payment Could Not Be Processed',
        'subtitle' => 'Unfortunately, we were unable to process your payment.',
        'greeting' => 'Hello :name,',
        'intro' => 'Unfortunately, we were unable to process your subscription payment.',
        'subscription_number' => 'Subscription Number',
        'amount' => 'Amount',
        'reason' => 'Reason',
        'action' => 'Please update your payment method to continue your subscription.',
        'update_payment' => 'Update Payment Method',
        'help_text' => 'If you need help, contact us at',
    ],

    // Order Payment Failed
    'order_payment_failed' => [
        'subject' => 'Payment Issue with Order - :order_number',
        'title' => 'Payment Could Not Be Processed',
        'subtitle' => 'Unfortunately, we were unable to process the payment for your order.',
        'order_number' => 'Order Number',
        'amount' => 'Amount',
        'reason' => 'Reason',
        'action' => 'Please try the payment again or use a different payment method.',
        'retry_payment' => 'Retry Payment',
    ],

    // Order Shipped
    'order_shipped' => [
        'subject' => 'Your Order :order_number Has Been Shipped - KAVI',
        'title' => 'Your Order Is On Its Way! 📦',
        'subtitle' => 'Great news! Your order has been shipped.',
        'order_number' => 'Order Number',
        'tracking_number' => 'Tracking Number',
        'track_package' => 'Track Package',
        'delivery_info' => 'Delivery Information',
        'pickup_point' => 'Pickup Point',
        'estimated_delivery' => 'Estimated Delivery',
        'order_contents' => 'Order Contents',
    ],

    // Subscription Box Shipped
    'subscription_box_shipped' => [
        'subject' => 'Your Coffee Box Has Been Shipped 📦 - :subscription_number',
        'title' => 'Your Coffee Box Is On Its Way! ☕',
        'subtitle' => 'Great news! We\'ve prepared a new coffee box for you.',
        'subscription_number' => 'Subscription Number',
        'tracking_number' => 'Tracking Number',
        'track_package' => 'Track Package',
        'delivery_info' => 'Delivery Information',
        'pickup_point' => 'Pickup Point',
        'whats_inside' => 'What\'s in the box?',
        'inside_text' => 'This box contains carefully selected coffees from various roasteries. Each coffee has its unique story and flavor profile.',
        'enjoy' => 'Enjoy your coffee!',
    ],

    // Subscription Box Preparing
    'subscription_box_preparing' => [
        'subject' => 'Preparing Your Coffee Box ☕ - :subscription_number',
        'title' => 'We\'re Preparing Your Coffee Box! ☕',
        'subtitle' => 'Your new coffee box is currently being prepared.',
        'subscription_number' => 'Subscription Number',
        'preparing_text' => 'We\'re carefully selecting the best coffees for your box. We\'ll send you shipping information soon.',
        'delivery_info' => 'Delivery Information',
        'pickup_point' => 'Pickup Point',
    ],

    // Order Delivered
    'order_delivered' => [
        'subject' => 'Your Order :order_number Has Been Delivered - KAVI',
        'title' => 'Your Order Has Been Delivered! 📦',
        'subtitle' => 'Your order has been successfully delivered.',
        'order_number' => 'Order Number',
        'pickup_point' => 'Pickup Point',
        'pickup_code' => 'Pickup Code',
        'pickup_deadline' => 'Pickup By',
    ],

    // Subscription Cancelled
    'subscription_cancelled' => [
        'subject' => 'Subscription Cancelled - We Hope to See You Again! - KAVI',
        'title' => 'Subscription Has Been Cancelled',
        'subtitle' => 'We\'re sorry to see you go.',
        'subscription_number' => 'Subscription Number',
        'cancelled_at' => 'Cancellation Date',
        'farewell' => 'Thank you for the time spent with us. If you ever want to come back, we\'ll be here for you.',
        'reactivate' => 'Want to reactivate your subscription?',
        'reactivate_button' => 'Reactivate Subscription',
    ],

    // Subscription Paused
    'subscription_paused' => [
        'subject' => 'Subscription Paused - :subscription_number',
        'title' => 'Subscription Has Been Paused',
        'subtitle' => 'Your coffee subscription has been temporarily paused.',
        'subscription_number' => 'Subscription Number',
        'paused_until' => 'Paused Until',
        'resume_text' => 'You can resume your subscription at any time in your account.',
        'resume_button' => 'Resume Subscription',
        'manage_subscription' => 'Manage Subscription',
    ],

    // Pause Ending Reminder
    'pause_ending_reminder' => [
        'subject' => 'Your Subscription Pause Ends in 3 Days - :subscription_number',
        'title' => 'Your Pause Is Ending Soon',
        'subtitle' => 'Your subscription will automatically resume in 3 days.',
        'pause_ends' => 'Pause Ends',
        'what_happens' => 'What happens after resuming?',
        'options_title' => 'Need more time?',
        'manage_button' => 'Manage Subscription',
    ],

    // Email Change Confirmation
    'email_change' => [
        'subject' => 'Confirm Your Email Change - KAVI',
        'title' => 'Confirm Email Change',
        'subtitle' => 'We received a request to change the email address for your account.',
        'new_email' => 'New Email Address',
        'confirm_button' => 'Confirm Email Change',
        'expires_text' => 'This link will expire in :hours hours.',
        'ignore_text' => 'If you didn\'t request this change, please ignore this email.',
    ],

    // Account Deleted
    'account_deleted' => [
        'subject' => 'Your Account Has Been Successfully Deleted - KAVI',
        'title' => 'Account Deleted',
        'subtitle' => 'Your KAVI account has been successfully deleted.',
        'farewell' => 'Thank you for being part of our community. If you ever want to come back, we\'ll welcome you with open arms.',
        'data_deleted' => 'All your personal data has been deleted in accordance with our privacy policy.',
    ],

    // Payment Method Changed
    'payment_method_changed' => [
        'subject' => 'Payment Method Changed - KAVI',
        'title' => 'Payment Method Updated',
        'subtitle' => 'Your payment method has been successfully updated.',
        'new_method' => 'New Payment Method',
        'card_ending' => 'Card ending in',
        'security_note' => 'If you didn\'t make this change, please contact us immediately.',
    ],

    // Upcoming Payment Reminder
    'upcoming_payment' => [
        'subject' => 'Subscription Payment Reminder - KAVI',
        'title' => 'Upcoming Subscription Payment',
        'subtitle' => 'This is a reminder about your upcoming subscription payment.',
        'subscription_number' => 'Subscription Number',
        'amount' => 'Amount',
        'payment_date' => 'Payment Date',
        'card_info' => 'Payment will be charged to the card ending in',
        'update_payment' => 'Change Payment Method',
    ],

    // Review Request (shared)
    'review_request' => [
        'subject' => 'How Did You Like Your Coffee? ⭐',
        'title' => 'How Did You Like Your Coffee? ⭐',
        'subtitle' => 'Your feedback helps us improve our selection.',
    ],

    // Order Review Request
    'order_review' => [
        'subject' => 'How Did You Like Your Coffee? ⭐',
        'title' => 'How Did You Like Your Coffee?',
        'subtitle' => 'We\'d love to hear your opinion.',
        'order_number' => 'Order Number',
        'review_text' => 'Help us improve our services and share your experience.',
        'review_button' => 'Write a Review',
    ],

    // Subscription Review Request
    'subscription_review' => [
        'subject' => 'How Are You Enjoying Our Service? ⭐',
        'title' => 'How Are You Enjoying Our Service?',
        'subtitle' => 'We\'d love to hear your opinion about our subscription.',
        'subscription_number' => 'Subscription Number',
        'review_text' => 'Help us improve our services and share your experience with our coffee subscription.',
        'review_button' => 'Write a Review',
    ],

    // Reset Password
    'reset_password' => [
        'subject' => 'Reset Password - KAVIbox.com',
        'title' => 'Reset Password',
        'subtitle' => 'We received a password reset request for your account.',
    ],

    // Welcome After Migration
    'welcome_migration' => [
        'subject' => '☕ Introducing the New KAVIbox!',
        'title' => 'Welcome to the New KAVIbox! ☕',
        'subtitle' => 'Your account has been successfully migrated to our new platform.',
        'intro' => 'We\'re excited to announce that we\'ve launched a new version of KAVIbox with an improved design and new features.',
        'whats_new' => 'What\'s new?',
        'feature1' => 'New modern design',
        'feature2' => 'Better subscription management',
        'feature3' => 'Faster checkout process',
        'subscription_info' => 'Your subscription information',
        'login_button' => 'Log In to Your New Account',
    ],

    // Grind options
    'grind' => [
        'whole_beans' => 'Whole Beans',
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'french_press' => 'French Press',
        'moka' => 'Moka Pot',
        'turkish' => 'Turkish Coffee',
    ],

    // Frequency options
    'frequency' => [
        'monthly' => 'Monthly',
        'bimonthly' => 'Every 2 months',
        'quarterly' => 'Quarterly',
        'one_time' => 'One-time',
    ],

];
