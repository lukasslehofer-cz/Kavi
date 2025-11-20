<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\SubscriptionShipment;
use Illuminate\Console\Command;

class CleanupCancelledSubscriptionShipments extends Command
{
    protected $signature = 'subscriptions:cleanup-cancelled-shipments {--dry-run : Show what would be deleted without deleting}';
    protected $description = 'Delete draft shipments for cancelled subscriptions without paid coverage';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }
        
        $this->info('Looking for draft shipments on cancelled subscriptions...');
        
        // Get all cancelled subscriptions
        $cancelledSubs = Subscription::where('status', 'cancelled')->get();
        
        $deletedCount = 0;
        
        foreach ($cancelledSubs as $sub) {
            // Get draft shipments (not sent)
            $draftShipments = $sub->shipments()
                ->whereNull('packeta_packet_id')
                ->whereNull('packeta_sent_at')
                ->get();
            
            foreach ($draftShipments as $shipment) {
                $shipmentDate = $shipment->shipment_date;
                
                // Check if subscription has paid coverage for this shipment
                $hasCoverage = \App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($sub, $shipmentDate);
                
                if (!$hasCoverage) {
                    $this->line("Subscription #{$sub->id}: Draft shipment #{$shipment->id} for {$shipmentDate->format('Y-m-d')} - No paid coverage");
                    
                    if (!$dryRun) {
                        $shipment->delete();
                        $this->info("  ✓ Deleted");
                        $deletedCount++;
                    } else {
                        $this->comment("  [DRY RUN] Would delete");
                        $deletedCount++;
                    }
                } else {
                    $this->line("Subscription #{$sub->id}: Draft shipment #{$shipment->id} for {$shipmentDate->format('Y-m-d')} - Has paid coverage, keeping");
                }
            }
        }
        
        $this->newLine();
        
        if ($dryRun) {
            $this->info("Would delete {$deletedCount} draft shipment(s)");
            $this->info("\n💡 Run without --dry-run to apply changes");
        } else {
            $this->info("✓ Deleted {$deletedCount} draft shipment(s)");
        }
        
        return 0;
    }
}

