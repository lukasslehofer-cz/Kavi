<?php

/**
 * Script to fix missing package dimensions on pending shipments
 * 
 * Problem: SubscriptionShipmentService was creating shipments without
 * package dimensions (length, width, height, weight).
 * 
 * This script finds pending shipments without dimensions and fills them
 * based on the subscription configuration.
 * 
 * Usage: php scripts/fix_shipment_dimensions.php
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SubscriptionShipment;
use App\Models\SubscriptionConfig;

echo "🔧 OPRAVA: Doplnění rozměrů balíků u pending shipmentů\n";
echo str_repeat('=', 70) . "\n\n";

// Find all pending shipments without dimensions
$shipments = SubscriptionShipment::with('subscription')
    ->where('status', 'pending')
    ->where(function($q) {
        $q->whereNull('package_length')
          ->orWhere('package_length', 0);
    })
    ->get();

echo "Nalezeno " . $shipments->count() . " pending shipmentů bez rozměrů\n\n";

$fixed = 0;
$skipped = 0;

foreach ($shipments as $shipment) {
    $subscription = $shipment->subscription;
    
    if (!$subscription) {
        echo "⚠️  Shipment #{$shipment->id}: chybí subscription\n";
        $skipped++;
        continue;
    }
    
    // Get configuration
    $config = is_string($subscription->configuration) 
        ? json_decode($subscription->configuration, true) 
        : $subscription->configuration;
    
    $amount = $config['amount'] ?? 2;
    
    // Get dimensions from SubscriptionConfig (dimensions must be integers)
    $dimensions = [
        'package_weight' => SubscriptionConfig::get("package_{$amount}_weight", $amount * 0.25),
        'package_length' => (int) SubscriptionConfig::get("package_{$amount}_length", 30),
        'package_width' => (int) SubscriptionConfig::get("package_{$amount}_width", 20),
        'package_height' => (int) SubscriptionConfig::get("package_{$amount}_height", 10),
        'carrier_id' => $subscription->carrier_id,
        'carrier_pickup_point' => $subscription->carrier_pickup_point,
    ];
    
    // Update shipment
    $shipment->update($dimensions);
    
    echo "✅ Shipment #{$shipment->id} ({$subscription->subscription_number}): ";
    echo "{$dimensions['package_length']}×{$dimensions['package_width']}×{$dimensions['package_height']} cm, ";
    echo "{$dimensions['package_weight']} kg\n";
    
    $fixed++;
}

echo "\n" . str_repeat('=', 70) . "\n";
echo "✅ Hotovo\n";
echo "   Opraveno: $fixed\n";
echo "   Přeskočeno: $skipped\n";

