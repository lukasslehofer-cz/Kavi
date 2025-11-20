<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$sub = App\Models\Subscription::find(36);

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "SUBSCRIPTION #36 DEBUG\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "ID: {$sub->id}\n";
echo "Status: {$sub->status}\n";
echo "Ends at: " . ($sub->ends_at ? $sub->ends_at->format('Y-m-d H:i:s') : 'null') . "\n";
echo "Last shipment: " . ($sub->last_shipment_date ? $sub->last_shipment_date->format('Y-m-d') : 'null') . "\n";
echo "Frequency months: {$sub->frequency_months}\n";

echo "\n--- Testing shouldShipOn for 2025-12-19 ---\n";
$targetDate = \Carbon\Carbon::parse('2025-12-19');
$result = $sub->shouldShipOn($targetDate);
echo "shouldShipOn(2025-12-19): " . ($result ? 'TRUE ❌' : 'FALSE ✓') . "\n";

echo "\n--- Testing hasPaidCoverageForDate ---\n";
$coverage = \App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($sub, $targetDate);
echo "hasPaidCoverageForDate(2025-12-19): " . ($coverage ? 'TRUE ❌' : 'FALSE ✓') . "\n";

echo "\n--- calculateNextShipmentDate ---\n";
$nextShipment = \App\Helpers\SubscriptionHelper::calculateNextShipmentDate($sub);
echo "Next shipment: " . ($nextShipment ? $nextShipment->format('Y-m-d') : 'null') . "\n";

echo "\n--- Payments ---\n";
$payments = $sub->payments()->where('status', 'paid')->get();
echo "Total paid payments: {$payments->count()}\n";
foreach($payments as $p) {
    echo "  Payment #{$p->id}: {$p->amount} Kč\n";
    echo "    Period: " . ($p->period_start ? $p->period_start->format('Y-m-d') : 'null') . " → " . ($p->period_end ? $p->period_end->format('Y-m-d') : 'null') . "\n";
    echo "    Paid at: " . ($p->paid_at ? $p->paid_at->format('Y-m-d H:i') : 'null') . "\n";
}

echo "\n--- Checking why it shows in admin ---\n";
$allSubscriptions = App\Models\Subscription::with(['user', 'plan'])
    ->whereIn('status', ['active', 'paused', 'pending', 'cancelled'])
    ->get();

$targetDate = \Carbon\Carbon::parse('2025-12-19');
$filtered = $allSubscriptions->filter(function($subscription) use ($targetDate) {
    // Include if should ship on this date
    if ($subscription->shouldShipOn($targetDate)) {
        return true;
    }
    
    // Also include if already sent on this exact date
    if ($subscription->last_shipment_date && 
        $subscription->last_shipment_date->format('Y-m-d') === $targetDate->format('Y-m-d')) {
        return true;
    }
    
    return false;
});

$sub36InFiltered = $filtered->contains('id', 36);
echo "Is subscription #36 in filtered results for 2025-12-19? " . ($sub36InFiltered ? 'YES ❌' : 'NO ✓') . "\n";

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

