<?php

/**
 * Create SubscriptionPayment record and Fakturoid invoice for subscription 45
 * 
 * Run with: php fix_fakturoid_invoice.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\FakturoidService;

$subscriptionId = 45;
$paymentIntentId = 'pi_3SjLZnGXfFJdyREX2vgBKnEM';
$paidAmount = 825; // 1100 - 275 (sleva VANOCE25)

echo "=== Creating Fakturoid invoice for subscription 45 ===\n\n";

$subscription = Subscription::find($subscriptionId);

if (!$subscription) {
    echo "❌ Subscription not found!\n";
    exit(1);
}

echo "Subscription: {$subscription->subscription_number}\n";
echo "User: {$subscription->user->name} ({$subscription->user->email})\n\n";

// Step 1: Create SubscriptionPayment record
echo "Step 1: Creating SubscriptionPayment record...\n";

$existingPayment = SubscriptionPayment::where('stripe_payment_intent_id', $paymentIntentId)->first();

if ($existingPayment) {
    echo "✅ Payment record already exists (ID: {$existingPayment->id})\n";
    $payment = $existingPayment;
} else {
    $payment = SubscriptionPayment::create([
        'subscription_id' => $subscriptionId,
        'stripe_payment_intent_id' => $paymentIntentId,
        'amount' => $paidAmount,
        'currency' => 'CZK',
        'status' => 'paid',
        'paid_at' => \Carbon\Carbon::parse('2025-12-28 16:08:34'),
        'period_start' => '2025-12-28',
        'period_end' => '2026-02-15',
    ]);
    echo "✅ Created payment record (ID: {$payment->id})\n";
}

// Step 2: Create Fakturoid invoice
echo "\nStep 2: Creating Fakturoid invoice...\n";

if ($payment->fakturoid_invoice_id) {
    echo "✅ Fakturoid invoice already exists (ID: {$payment->fakturoid_invoice_id})\n";
} else {
    try {
        $fakturoidService = new FakturoidService();
        $result = $fakturoidService->processInvoiceForSubscriptionPayment($payment);
        
        if ($result) {
            $payment->refresh();
            echo "✅ Fakturoid invoice created!\n";
            echo "   Invoice ID: {$payment->fakturoid_invoice_id}\n";
            echo "   Invoice Number: {$payment->invoice_number}\n";
            echo "   PDF Path: {$payment->invoice_pdf_path}\n";
        } else {
            echo "❌ Failed to create Fakturoid invoice - check logs for details\n";
        }
    } catch (\Exception $e) {
        echo "❌ Error: {$e->getMessage()}\n";
    }
}

echo "\n=== Done ===\n";

