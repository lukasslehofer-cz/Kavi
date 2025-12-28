<?php

/**
 * Create Stripe customer and attach payment method for user 1908
 * 
 * Run with: php fix_stripe_customer.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

\Stripe\Stripe::setApiKey(config('services.stripe.secret'));

$userId = 1908;
$paymentMethodId = 'pm_1SjLZnGXfFJdyREXbh5RaNvq';

echo "=== Creating Stripe customer ===\n\n";

$user = User::find($userId);

if (!$user) {
    echo "❌ User not found!\n";
    exit(1);
}

echo "User: {$user->name} ({$user->email})\n";

if ($user->stripe_customer_id) {
    echo "✅ User already has Stripe customer ID: {$user->stripe_customer_id}\n";
} else {
    // Create Stripe customer
    $customer = \Stripe\Customer::create([
        'email' => $user->email,
        'name' => $user->name,
        'metadata' => [
            'user_id' => $user->id,
        ],
    ]);
    
    $user->update(['stripe_customer_id' => $customer->id]);
    echo "✅ Created Stripe customer: {$customer->id}\n";
}

// Attach payment method to customer
try {
    $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
    
    if ($paymentMethod->customer === $user->stripe_customer_id) {
        echo "✅ Payment method already attached to customer\n";
    } elseif ($paymentMethod->customer) {
        echo "⚠️  Payment method is attached to different customer: {$paymentMethod->customer}\n";
    } else {
        $paymentMethod->attach(['customer' => $user->stripe_customer_id]);
        echo "✅ Payment method attached to customer\n";
    }
    
    // Set as default payment method
    \Stripe\Customer::update($user->stripe_customer_id, [
        'invoice_settings' => [
            'default_payment_method' => $paymentMethodId,
        ],
    ]);
    echo "✅ Set as default payment method\n";
    
} catch (\Exception $e) {
    echo "❌ Error with payment method: {$e->getMessage()}\n";
    exit(1);
}

echo "\n=== Done ===\n";
echo "User {$user->email} is now ready for automatic billing.\n";

