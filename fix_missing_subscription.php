<?php

/**
 * Fix missing subscription for payment pi_3SjLZnGXfFJdyREX2vgBKnEM
 * Customer: Rozálie Bártová (rozalie.bartova@gmail.com)
 * 
 * Run with: php fix_missing_subscription.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Subscription;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Data from Stripe
$email = 'rozalie.bartova@gmail.com';
$name = 'Rozálie Bártová';
$phone = '+420733381838';
$paymentIntentId = 'pi_3SjLZnGXfFJdyREX2vgBKnEM';
$paymentMethodId = 'pm_1SjLZnGXfFJdyREXbh5RaNvq';
$sessionId = 'cs_live_a18vm95syArnQy7NZr9ahTS9wkM80iVvZzimSPcQb2Z2m8CQjuC5Ux9Yog';

$configuration = [
    'amount' => '3',
    'type' => 'filter',
    'mix' => ['espresso' => '0', 'filter' => '0'],
    'frequency' => '1',
    'isDecaf' => false,
];

$shippingAddress = [
    'name' => 'Rozálie Bártová',
    'email' => 'rozalie.bartova@gmail.com',
    'phone' => '+420733381838',
    'billing_address' => 'Karla IV., 6',
    'billing_city' => 'Litoměřice',
    'billing_postal_code' => '41194',
    'country' => 'CZ',
    'packeta_point_id' => '20477',
    'packeta_point_name' => 'Litoměřice, Velká Dominikánská 165',
    'packeta_point_address' => 'Velká Dominikánská 165, 412 01 Litoměřice',
    'carrier_id' => null,
    'carrier_pickup_point' => null,
];

$configuredPrice = 1100;
$discountAmount = 275;
$couponCode = 'VANOCE25';
$nextBillingDate = '2026-02-15';
$frequencyMonths = 1;

echo "=== Fixing missing subscription ===\n\n";

// Check for existing subscription with this payment intent (idempotency)
$existingSubscription = Subscription::where('stripe_payment_intent_id', $paymentIntentId)->first();
if ($existingSubscription) {
    echo "❌ Subscription already exists for this payment intent!\n";
    echo "   Subscription ID: {$existingSubscription->id}\n";
    echo "   Subscription Number: {$existingSubscription->subscription_number}\n";
    exit(1);
}

DB::beginTransaction();

try {
    // Find or create user
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        echo "Creating new user for: {$email}\n";
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make(Str::random(32)), // Random password, user can reset
            'email_verified_at' => now(),
        ]);
        echo "✅ User created with ID: {$user->id}\n";
    } else {
        echo "✅ Found existing user with ID: {$user->id}\n";
    }

    // Find coupon
    $coupon = Coupon::where('code', $couponCode)->first();
    
    // Create subscription
    $subscription = Subscription::create([
        'subscription_number' => Subscription::generateSubscriptionNumber(),
        'user_id' => $user->id,
        'stripe_payment_intent_id' => $paymentIntentId,
        'stripe_session_id' => $sessionId,
        'stripe_subscription_id' => null, // Custom billing - no Stripe subscription
        'status' => 'active',
        'starts_at' => now(),
        'next_billing_date' => \Carbon\Carbon::parse($nextBillingDate),
        'frequency_months' => $frequencyMonths,
        'configuration' => $configuration,
        'configured_price' => $configuredPrice,
        'shipping_address' => $shippingAddress,
        'packeta_point_id' => '20477',
        'packeta_point_name' => 'Litoměřice, Velká Dominikánská 165',
        'packeta_point_address' => 'Velká Dominikánská 165, 412 01 Litoměřice',
        'shipping_cost' => 0,
        'shipping_country' => 'CZ',
        'shipping_rate_id' => 1,
        // Coupon data
        'coupon_id' => $coupon?->id,
        'coupon_code' => $couponCode,
        'discount_amount' => $discountAmount,
        'discount_months_total' => 1,
        'discount_months_remaining' => 0, // Already applied to first payment
    ]);

    echo "\n✅ Subscription created successfully!\n";
    echo "   Subscription ID: {$subscription->id}\n";
    echo "   Subscription Number: {$subscription->subscription_number}\n";
    echo "   User: {$user->name} ({$user->email})\n";
    echo "   Status: {$subscription->status}\n";
    echo "   Next Billing: {$subscription->next_billing_date->format('Y-m-d')}\n";
    echo "   Configuration: L Box (3×250g), Filtr, měsíční\n";
    echo "   Price: {$configuredPrice} Kč\n";
    echo "   Coupon: {$couponCode} (-{$discountAmount} Kč)\n";

    // Store payment method for future billing
    if (!$user->stripe_customer_id) {
        echo "\n⚠️  User doesn't have Stripe customer ID. You may need to:\n";
        echo "   1. Create Stripe customer manually\n";
        echo "   2. Attach payment method {$paymentMethodId}\n";
        echo "   3. Update user's stripe_customer_id\n";
    }

    DB::commit();
    echo "\n✅ Transaction committed successfully!\n";

    // Send welcome/confirmation email
    echo "\nSending confirmation email...\n";
    try {
        \Mail::to($user->email)->send(new \App\Mail\SubscriptionConfirmation($subscription));
        echo "✅ Confirmation email sent!\n";
    } catch (\Exception $e) {
        echo "⚠️  Failed to send email: {$e->getMessage()}\n";
    }

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: {$e->getMessage()}\n";
    echo "   Stack trace:\n{$e->getTraceAsString()}\n";
    exit(1);
}

echo "\n=== Done ===\n";

