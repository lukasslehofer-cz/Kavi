<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChargeSubscriptionPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:charge-payments {--dry-run : Run without actually charging}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge subscription payments that are due today (custom billing cycle)';

    /**
     * Execute the console command.
     */
    public function handle(StripeService $stripeService)
    {
        $this->info('🔍 Looking for subscriptions with payments due today...');
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->warn('🧪 DRY RUN MODE - No actual charges will be made');
        }
        
        // Find active subscriptions where next_billing_date is today or earlier
        $subscriptions = Subscription::where('status', 'active')
            ->whereNotNull('next_billing_date')
            ->whereDate('next_billing_date', '<=', today())
            ->with('user')
            ->get();
        
        if ($subscriptions->isEmpty()) {
            $this->info('✓ No subscriptions due for payment today.');
            
            // Mark cron as run successfully
            \Cache::put('subscription_billing_cron_last_run', now(), now()->addDay());
            
            return 0;
        }
        
        $this->info("📦 Found {$subscriptions->count()} subscription(s) due for payment.");
        $this->newLine();
        
        $successCount = 0;
        $failedCount = 0;
        $skippedCount = 0;
        $results = [];
        
        foreach ($subscriptions as $subscription) {
            $subscriptionNumber = $subscription->subscription_number ?? '#' . $subscription->id;
            
            $this->line("Processing: {$subscriptionNumber}");
            
            // Skip if paused, cancelled, or has no user
            if (!$subscription->user) {
                $this->warn("  ⚠️ Skipped - No user");
                $skippedCount++;
                continue;
            }
            
            if ($isDryRun) {
                // Calculate the ACTUAL amount that would be charged (same logic as StripeService)
                $amount = $subscription->configured_price ?? 0;
                if ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) {
                    $amount -= $subscription->discount_amount;
                }
                
                $this->info("  🧪 Would charge: " . number_format($amount, 2) . " CZK");
                if ($subscription->discount_amount > 0) {
                    $this->line("      (Original: " . number_format($subscription->configured_price, 2) . " CZK, Discount: -" . number_format($subscription->discount_amount, 2) . " CZK)");
                }
                $successCount++;
                continue;
            }
            
            try {
                // Use DB transaction for atomicity
                DB::beginTransaction();
                
                $result = $stripeService->chargeSubscriptionPayment($subscription);
                
                if ($result['success']) {
                    DB::commit();
                    
                    $this->info("  ✓ Success - Payment ID: {$result['payment_intent_id']}");
                    $successCount++;
                    
                    $results[] = [
                        'subscription' => $subscriptionNumber,
                        'status' => 'success',
                        'payment_id' => $result['payment_intent_id'],
                    ];
                } else {
                    DB::rollBack();
                    
                    if ($result['error'] === 'already_charged_today') {
                        $this->warn("  ⚠️ Already charged today - skipping");
                        $skippedCount++;
                    } else {
                        $this->error("  ✗ Failed: {$result['error']}");
                        $failedCount++;
                        
                        $results[] = [
                            'subscription' => $subscriptionNumber,
                            'status' => 'failed',
                            'error' => $result['error'],
                        ];
                    }
                }
                
            } catch (\Exception $e) {
                DB::rollBack();
                
                $this->error("  ✗ Exception: " . $e->getMessage());
                $failedCount++;
                
                \Log::error('Subscription payment exception in cron', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                
                $results[] = [
                    'subscription' => $subscriptionNumber,
                    'status' => 'exception',
                    'error' => $e->getMessage(),
                ];
            }
        }
        
        $this->newLine();
        $this->info('📊 Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Successful', $successCount],
                ['Failed', $failedCount],
                ['Skipped', $skippedCount],
                ['Total', $subscriptions->count()],
            ]
        );
        
        // Mark cron as run successfully
        \Cache::put('subscription_billing_cron_last_run', now(), now()->addDay());
        \Cache::put('subscription_billing_cron_last_summary', [
            'timestamp' => now()->toDateTimeString(),
            'total' => $subscriptions->count(),
            'successful' => $successCount,
            'failed' => $failedCount,
            'skipped' => $skippedCount,
            'results' => $results,
        ], now()->addWeek());
        
        // Send alert if there were failures
        if ($failedCount > 0 && !$isDryRun) {
            $this->sendFailureAlert($failedCount, $results);
        }
        
        // Return non-zero exit code if there were failures
        return $failedCount > 0 ? 1 : 0;
    }
    
    /**
     * Send alert email to admin about payment failures
     */
    private function sendFailureAlert(int $failedCount, array $results): void
    {
        try {
            $adminEmail = config('mail.from.address');
            
            if ($adminEmail) {
                \Mail::raw(
                    "Subscription Billing Alert\n\n" .
                    "Failed payments: {$failedCount}\n" .
                    "Date: " . now()->toDateTimeString() . "\n\n" .
                    "Failed subscriptions:\n" .
                    collect($results)
                        ->where('status', 'failed')
                        ->map(fn($r) => "- {$r['subscription']}: {$r['error']}")
                        ->join("\n"),
                    function ($message) use ($adminEmail) {
                        $message->to($adminEmail)
                            ->subject('⚠️ Subscription Payment Failures - ' . now()->toDateString());
                    }
                );
                
                $this->warn("📧 Alert email sent to {$adminEmail}");
            }
        } catch (\Exception $e) {
            $this->error("Failed to send alert email: " . $e->getMessage());
        }
    }
}

