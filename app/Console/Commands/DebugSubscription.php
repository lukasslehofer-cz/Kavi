<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class DebugSubscription extends Command
{
    protected $signature = 'subscriptions:debug {id : The subscription ID to debug}';
    protected $description = 'Debug a specific subscription to see why it appears in shipments';

    public function handle()
    {
        $id = $this->argument('id');
        $sub = Subscription::find($id);
        
        if (!$sub) {
            $this->error("Subscription #{$id} not found!");
            return 1;
        }

        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("SUBSCRIPTION #{$sub->id} DEBUG");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n");

        $this->line("ID: {$sub->id}");
        $this->line("Number: {$sub->subscription_number}");
        $this->line("Status: {$sub->status}");
        $this->line("Ends at: " . ($sub->ends_at ? $sub->ends_at->format('Y-m-d H:i:s') : 'null'));
        $this->line("Last shipment: " . ($sub->last_shipment_date ? $sub->last_shipment_date->format('Y-m-d') : 'null'));
        $this->line("Frequency months: {$sub->frequency_months}");

        $this->line("\n--- Testing shouldShipOn for 2025-12-19 ---");
        $targetDate = \Carbon\Carbon::parse('2025-12-19');
        $result = $sub->shouldShipOn($targetDate);
        $this->line("shouldShipOn(2025-12-19): " . ($result ? 'TRUE ❌' : 'FALSE ✓'));

        $this->line("\n--- Testing hasPaidCoverageForDate ---");
        $coverage = \App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($sub, $targetDate);
        $this->line("hasPaidCoverageForDate(2025-12-19): " . ($coverage ? 'TRUE ❌' : 'FALSE ✓'));
        
        $this->line("\n--- isInitialShipmentCovered ---");
        $initialCovered = \App\Helpers\SubscriptionHelper::isInitialShipmentCovered($sub, $targetDate);
        $this->line("isInitialShipmentCovered(2025-12-19): " . ($initialCovered ? 'TRUE ⚠️' : 'FALSE'));

        $this->line("\n--- calculateNextShipmentDate ---");
        $nextShipment = \App\Helpers\SubscriptionHelper::calculateNextShipmentDate($sub);
        $this->line("Next shipment: " . ($nextShipment ? $nextShipment->format('Y-m-d') : 'null'));

        $this->line("\n--- Payments ---");
        $payments = $sub->payments()->where('status', 'paid')->get();
        $this->line("Total paid payments: {$payments->count()}");
        
        if ($payments->isEmpty()) {
            $this->warn("  No paid payments found!");
        } else {
            foreach($payments as $p) {
                $this->line("  Payment #{$p->id}: {$p->amount} Kč");
                $this->line("    Period: " . ($p->period_start ? $p->period_start->format('Y-m-d') : 'null') . " → " . ($p->period_end ? $p->period_end->format('Y-m-d') : 'null'));
                $this->line("    Paid at: " . ($p->paid_at ? $p->paid_at->format('Y-m-d H:i') : 'null'));
                
                // Check if this payment covers the target date
                if ($p->period_start && $p->period_end) {
                    $covers = $targetDate->between($p->period_start, $p->period_end);
                    $this->line("    Covers 2025-12-19? " . ($covers ? 'YES ✓' : 'NO'));
                }
            }
        }

        $this->line("\n--- Shipments ---");
        $shipments = $sub->shipments()->orderBy('shipment_date', 'desc')->get();
        $this->line("Total shipments: {$shipments->count()}");
        foreach($shipments as $s) {
            $this->line("  Shipment #{$s->id}: " . $s->shipment_date->format('Y-m-d') . " - Status: " . ($s->packeta_packet_id ? 'Sent' : 'Draft'));
        }

        $this->line("\n--- Testing filter logic from SubscriptionController ---");
        
        // Test shouldShipOn
        $shouldShip = $sub->shouldShipOn($targetDate);
        $this->line("1. shouldShipOn(2025-12-19): " . ($shouldShip ? 'TRUE' : 'FALSE'));
        
        // Test last_shipment_date
        $alreadySent = $sub->last_shipment_date && $sub->last_shipment_date->format('Y-m-d') === $targetDate->format('Y-m-d');
        $this->line("2. Already sent on 2025-12-19: " . ($alreadySent ? 'TRUE' : 'FALSE'));
        
        $willBeInList = $shouldShip || $alreadySent;
        if ($willBeInList) {
            $this->error("\n❌ PROBLEM: This subscription WILL appear in /admin/subscriptions/shipments?date=2025-12-19");
        } else {
            $this->info("\n✓ OK: This subscription will NOT appear in shipments list");
        }

        $this->line("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        return 0;
    }
}

