<?php

/**
 * Script to fix incorrectly linked payments to shipments
 * 
 * Problem: Payments with period_end = billing_date were incorrectly linked
 * to shipments, when they should NOT be (period_end is exclusive).
 * 
 * This script finds and unlinks such payments from shipments.
 * 
 * Usage: php artisan tinker scripts/fix_payment_shipment_links.php
 */

use App\Models\SubscriptionShipment;
use App\Models\SubscriptionPayment;
use App\Models\ShipmentSchedule;
use Carbon\Carbon;

echo "🔧 OPRAVA: Odpojení chybně propojených plateb od rozesílek\n";
echo str_repeat('=', 70) . "\n\n";

// Find all pending shipments with a linked payment
$shipmentsWithPayments = SubscriptionShipment::with(['payment', 'subscription'])
    ->whereNotNull('subscription_payment_id')
    ->whereIn('status', ['pending'])
    ->get();

echo "Nalezeno " . $shipmentsWithPayments->count() . " pending rozesílek s propojenou platbou\n\n";

$fixed = 0;
$skipped = 0;

foreach ($shipmentsWithPayments as $shipment) {
    $payment = $shipment->payment;
    
    if (!$payment || !$payment->period_end) {
        $skipped++;
        continue;
    }
    
    // Get billing date for this shipment's month
    $schedule = ShipmentSchedule::getForMonth($shipment->shipment_date->year, $shipment->shipment_date->month);
    $billingDate = $schedule?->billing_date ?? $shipment->shipment_date->copy()->day(15);
    
    // Check if payment period_end is <= billing_date (should be EXCLUSIVE, so > is correct)
    // If period_end <= billing_date, the payment does NOT cover this billing cycle
    if ($payment->period_end->lte($billingDate)) {
        echo "❌ Rozesílka #{$shipment->id} ({$shipment->subscription->subscription_number}):\n";
        echo "   shipment_date: " . $shipment->shipment_date->format('d.m.Y') . "\n";
        echo "   billing_date: " . $billingDate->format('d.m.Y') . "\n";
        echo "   Platba #{$payment->id}: period_end=" . $payment->period_end->format('d.m.Y') . "\n";
        echo "   → period_end ({$payment->period_end->format('d.m.')}) <= billing_date ({$billingDate->format('d.m.')}) = CHYBNÉ PROPOJENÍ\n";
        
        // Unlink
        $shipment->update(['subscription_payment_id' => null]);
        echo "   ✅ Odpojeno\n\n";
        $fixed++;
    } else {
        $skipped++;
    }
}

echo str_repeat('=', 70) . "\n";
echo "✅ Hotovo\n";
echo "   Opraveno: $fixed\n";
echo "   Přeskočeno (správně): $skipped\n";

