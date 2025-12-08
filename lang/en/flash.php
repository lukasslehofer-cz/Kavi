<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flash Messages - English
    |--------------------------------------------------------------------------
    |
    | Flash messages used across the application
    |
    */

    // Authentication & Magic Link
    'auth' => [
        'email_send_failed' => 'Failed to send email. Please try again.',
        'magic_link_sent' => 'If an account with this email exists, a login link has been sent. The link is valid for 15 minutes.',
        'magic_link_invalid' => 'The login link is invalid or has expired. Please request a new link.',
        'user_not_found' => 'User not found.',
        'login_success' => 'You have been successfully logged in!',
        'magic_link_login_success' => 'You have been successfully logged in via magic link!',
        'please_login_subscription' => 'Please log in to activate your subscription.',
        'account_exists' => 'An account with this email already exists. Please log in.',
        'account_exists_or_use_other' => 'An account with this email already exists. Please log in or use a different email.',
    ],

    // Subscription
    'subscription' => [
        'config_validation_error' => 'Configuration validation error. Please try again.',
        'order_processing' => 'Thank you for your order! We are processing your payment and will send you a confirmation email shortly.',
        'config_not_found' => 'Subscription configuration not found. Please configure your subscription again.',
        'config_missing' => 'Configuration not found.',
        'created_pending_payment' => 'Subscription created! It will be activated once payment is received.',
        'created_email_sent' => 'Thank you for your order! We will send payment details to :email.',
        'creation_error' => 'An error occurred while creating the subscription. Please try again.',
        'activated_free' => 'Thank you! Your subscription has been activated with 100% discount.',
        'no_unpaid_invoice' => 'This subscription does not have an unpaid invoice.',
        'payment_session_error' => 'Unable to create payment session. Please contact support.',
        'payment_error' => 'An error occurred while creating the payment. Please try again later.',
        'no_active_subscription' => 'You don\'t have an active subscription.',
        'paused' => 'Subscription has been paused.',
        'resumed' => 'Subscription has been resumed.',
        'cancelled' => 'Subscription has been cancelled.',
    ],

    // One-time box
    'onetime_box' => [
        'created_pending' => 'One-time box order created! It will be processed once payment is received.',
        'created_email_sent' => 'Thank you for your order! We will send payment details to :email.',
        'creation_error' => 'An error occurred while creating the order. Please try again.',
    ],

    // Payment
    'payment' => [
        'session_error' => 'Failed to create payment session: :error Please try again below.',
        'management_error' => 'Could not open payment methods management. Please try again later.',
    ],

    // Coupon
    'coupon' => [
        'activated' => 'Coupon :code has been activated! It will be automatically applied at checkout.',
        'invalid' => 'Coupon ":code" is not valid: :message',
    ],

    // Profile & Account
    'profile' => [
        'updated' => 'Profile has been successfully updated.',
        'password_changed' => 'Password has been successfully changed.',
        'account_deleted' => 'Your account has been successfully deleted. We have sent a confirmation to your email.',
        'account_delete_error' => 'An error occurred while deleting your account. Please try again later or contact us.',
        'account_delete_error_contact' => 'An error occurred while deleting your account. Please contact us at info@kavibox.com',
    ],

    // Checkout & Cart
    'checkout' => [
        'cart_empty' => 'Your cart is empty.',
        'order_created' => 'Order has been successfully created!',
        'order_error' => 'An error occurred while creating the order: :error',
        'addon_limit_exceeded' => 'You have exceeded the add-on limit or the subscription is not available.',
        'order_not_unpaid' => 'This order is not in an unpaid state.',
    ],

    // Admin - Products
    'admin_product' => [
        'created' => 'Product has been successfully created.',
        'updated' => 'Product has been successfully updated.',
        'deleted' => 'Product has been successfully deleted.',
    ],

    // Admin - Roasteries
    'admin_roastery' => [
        'created' => 'Roastery has been successfully created.',
        'updated' => 'Roastery has been successfully updated.',
        'deleted' => 'Roastery has been successfully deleted.',
    ],

    // Admin - Orders
    'admin_order' => [
        'status_updated' => 'Order status has been successfully updated.',
        'address_updated' => 'Delivery address has been successfully updated.',
        'cancel_pending_only' => 'Only orders with "Pending" status can be cancelled.',
        'cancelled' => 'Order has been cancelled.',
    ],

    // Admin - Subscriptions
    'admin_subscription' => [
        'status_updated' => 'Subscription status has been successfully updated.',
        'address_updated' => 'Delivery address has been successfully updated.',
        'cancel_failed' => 'Failed to cancel subscription. Please try again.',
    ],

    // Admin - Config
    'admin_config' => [
        'settings_updated' => 'Settings have been successfully updated.',
        'schedule_updated' => 'Shipment schedule has been successfully updated.',
        'schedule_created' => 'Schedule for year :year has been successfully created.',
        'cannot_edit_past' => 'Cannot edit past shipment.',
        'shipment_updated' => 'Shipment for :month :year has been successfully updated.',
    ],

    // Admin - Coupons
    'admin_coupon' => [
        'created' => 'Coupon has been successfully created!',
        'updated' => 'Coupon has been successfully updated!',
        'deleted' => 'Coupon has been successfully deleted!',
    ],

    // Admin - Newsletter
    'admin_newsletter' => [
        'subscription_removed' => 'Newsletter subscription has been removed.',
        'synced' => 'Synchronized :count customer emails.',
    ],

];

