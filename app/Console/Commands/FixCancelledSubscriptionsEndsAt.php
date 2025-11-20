<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class FixCancelledSubscriptionsEndsAt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:fix-cancelled-ends-at {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix ends_at for cancelled subscriptions based on paid coverage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }
        
        $this->info('Looking for cancelled subscriptions...');
        
        $cancelledSubscriptions = Subscription::where('status', 'cancelled')->get();
        
        $this->info("Found {$cancelledSubscriptions->count()} cancelled subscriptions");
        
        $fixed = 0;
        $alreadyCorrect = 0;
        
        foreach ($cancelledSubscriptions as $subscription) {
            $this->line("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("Subscription #{$subscription->id} ({$subscription->subscription_number})");
            $this->line("Current ends_at: " . ($subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : 'null'));
            
            // Check if there's paid coverage for any future shipments
            $nextShipment = \App\Helpers\SubscriptionHelper::calculateNextShipmentDate($subscription);
            
            if (!$nextShipment) {
                $this->line("  → No next shipment calculated, keeping ends_at as is");
                $alreadyCorrect++;
                continue;
            }
            
            $this->line("Next shipment would be: " . $nextShipment->format('Y-m-d'));
            
            // Check if we have paid coverage for the next shipment
            $hasPaidCoverage = \App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($subscription, $nextShipment);
            
            if ($hasPaidCoverage) {
                // Find the last shipment date that has paid coverage
                $frequencyMonths = max(1, (int)($subscription->frequency_months ?? 1));
                $candidate = $nextShipment->copy();
                $lastPaidShipment = null;
                
                // Look ahead up to 12 months to find the last paid shipment
                $guard = 0;
                while ($guard < 12) {
                    if (\App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($subscription, $candidate)) {
                        $lastPaidShipment = $candidate->copy();
                    } else {
                        // Found the first unpaid shipment, stop
                        break;
                    }
                    $candidate = $candidate->copy()->addMonths($frequencyMonths);
                    $guard++;
                }
                
                if ($lastPaidShipment) {
                    $newEndsAt = $lastPaidShipment->copy()->endOfDay();
                    $this->warn("  ⚠️  Has paid coverage until: " . $lastPaidShipment->format('Y-m-d'));
                    
                    if ($subscription->ends_at && $subscription->ends_at->format('Y-m-d') === $newEndsAt->format('Y-m-d')) {
                        $this->line("  ✓ ends_at is already correct");
                        $alreadyCorrect++;
                    } else {
                        $this->error("  ✗ ends_at should be: " . $newEndsAt->format('Y-m-d'));
                        
                        if (!$dryRun) {
                            $subscription->update(['ends_at' => $newEndsAt]);
                            $this->info("  ✓ Updated ends_at to: " . $newEndsAt->format('Y-m-d'));
                            $fixed++;
                        } else {
                            $this->comment("  [DRY RUN] Would update ends_at to: " . $newEndsAt->format('Y-m-d'));
                            $fixed++;
                        }
                    }
                } else {
                    $this->line("  → No paid coverage found after search");
                    $alreadyCorrect++;
                }
            } else {
                $this->line("  → No paid coverage for next shipment");
                
                // Should be cancelled immediately (ends_at in the past)
                if ($subscription->ends_at && $subscription->ends_at->isPast()) {
                    $this->line("  ✓ ends_at is correctly in the past");
                    $alreadyCorrect++;
                } else {
                    $this->error("  ✗ ends_at should be in the past but is: " . ($subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : 'null'));
                    
                    // Set to when it was actually cancelled (use created_at of last payment or updated_at)
                    $newEndsAt = $subscription->updated_at;
                    
                    if (!$dryRun) {
                        $subscription->update(['ends_at' => $newEndsAt]);
                        $this->info("  ✓ Updated ends_at to: " . $newEndsAt->format('Y-m-d H:i:s'));
                        $fixed++;
                    } else {
                        $this->comment("  [DRY RUN] Would update ends_at to: " . $newEndsAt->format('Y-m-d H:i:s'));
                        $fixed++;
                    }
                }
            }
        }
        
        $this->line("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("\n📊 SUMMARY:");
        $this->line("Total cancelled subscriptions: {$cancelledSubscriptions->count()}");
        $this->line("Already correct: {$alreadyCorrect}");
        
        if ($dryRun) {
            $this->comment("Would fix: {$fixed}");
            $this->info("\n💡 Run without --dry-run to apply changes");
        } else {
            $this->info("Fixed: {$fixed}");
        }
        
        return 0;
    }
}

