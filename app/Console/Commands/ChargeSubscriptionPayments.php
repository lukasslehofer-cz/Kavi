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
    protected $signature = 'subscriptions:charge-payments 
                            {--dry-run : Run without actually charging}
                            {--subscription= : Process only a specific subscription ID}';

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
        $isDryRun = $this->option('dry-run');
        $specificSubscriptionId = $this->option('subscription');
        
        if ($specificSubscriptionId) {
            $this->info("🎯 Processing specific subscription ID: {$specificSubscriptionId}");
        } else {
            $this->info('🔍 Looking for subscriptions with payments due today...');
        }
        
        if ($isDryRun) {
            $this->warn('🧪 DRY RUN MODE - No actual charges will be made');
        }
        
        // Find subscriptions to process
        // Only 'active' subscriptions require billing, not 'complimentary'
        $query = Subscription::where('status', 'active')
            ->whereNotNull('next_billing_date')
            ->with('user');
        
        if ($specificSubscriptionId) {
            // Process only the specific subscription (regardless of next_billing_date)
            $query->where('id', $specificSubscriptionId);
        } else {
            // Normal mode: only subscriptions due today or earlier
            $query->whereDate('next_billing_date', '<=', today());
        }
        
        $subscriptions = $query->get();
        
        if ($subscriptions->isEmpty()) {
            if ($specificSubscriptionId) {
                $this->error("✗ Subscription ID {$specificSubscriptionId} not found or not active.");
            } else {
                $this->info('✓ No subscriptions due for payment today.');
                // Mark cron as run successfully
                \Cache::put('subscription_billing_cron_last_run', now(), now()->addDay());
            }
            
            return 0;
        }
        
        if ($specificSubscriptionId) {
            $this->info("📦 Processing 1 subscription.");
        } else {
            $this->info("📦 Found {$subscriptions->count()} subscription(s) due for payment.");
        }
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
                // Perform all the same checks as real run
                $this->line("  📅 next_billing_date: " . ($subscription->next_billing_date?->format('Y-m-d') ?? 'NULL'));
                
                // Check 1: Already charged today?
                $alreadyChargedToday = $subscription->payments()->whereDate('paid_at', today())->exists();
                if ($alreadyChargedToday) {
                    $this->warn("  ⚠️ Would skip - Already charged today");
                    $skippedCount++;
                    continue;
                }
                
                // Check 2: Safety check against subscription_shipments (single source of truth)
                // This respects pauses - skipped shipments are in the table, so they're correctly skipped
                $nextPendingShipment = $subscription->shipments()
                    ->where('status', 'pending')
                    ->whereNull('subscription_payment_id')
                    ->orderBy('shipment_date', 'asc')
                    ->first();

                if ($nextPendingShipment) {
                    $schedule = \App\Models\ShipmentSchedule::getForMonth(
                        $nextPendingShipment->shipment_date->year,
                        $nextPendingShipment->shipment_date->month
                    );

                    $billingDateForShipment = $schedule?->billing_date
                        ?? $nextPendingShipment->shipment_date->copy()->day(15);

                    if (today()->lt($billingDateForShipment)) {
                        $this->warn("  ⚠️ Would skip - Shipment schedule says too soon (billing date: {$billingDateForShipment->format('d.m.Y')})");
                        $skippedCount++;
                        continue;
                    }
                }
                
                // Check 3: User has Stripe customer ID?
                $customerId = $subscription->user->stripe_customer_id ?? null;
                if (!$customerId) {
                    $this->error("  ✗ Would fail - User has no Stripe customer ID");
                    $failedCount++;
                    continue;
                }
                
                // Check 3: Customer has payment method?
                try {
                    $paymentMethodId = $stripeService->getCustomerDefaultPaymentMethod($customerId);
                    if (!$paymentMethodId) {
                        $this->error("  ✗ Would fail - No payment method found for customer");
                        $failedCount++;
                        continue;
                    }
                    $this->line("  💳 Payment method: " . substr($paymentMethodId, 0, 20) . '...');
                } catch (\Exception $e) {
                    $this->error("  ✗ Would fail - Cannot check payment method: " . $e->getMessage());
                    $failedCount++;
                    continue;
                }
                
                // Calculate the amount that would be charged
                $amount = $subscription->configured_price ?? 0;
                if ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) {
                    $amount -= $subscription->discount_amount;
                }
                
                $currency = $subscription->currency ?? 'CZK';
                $this->info("  ✓ Would charge: " . number_format($amount, 2) . " {$currency}");
                if ($subscription->discount_amount > 0) {
                    $this->line("      (Original: " . number_format($subscription->configured_price, 2) . " {$currency}, Discount: -" . number_format($subscription->discount_amount, 2) . " {$currency})");
                }
                $successCount++;
                continue;
            }
            
            // Safety check against subscription_shipments (single source of truth)
            // This respects pauses - skipped shipments are in the table, so they're correctly skipped
            $nextPendingShipment = $subscription->shipments()
                ->where('status', 'pending')
                ->whereNull('subscription_payment_id')
                ->orderBy('shipment_date', 'asc')
                ->first();

            if ($nextPendingShipment) {
                $schedule = \App\Models\ShipmentSchedule::getForMonth(
                    $nextPendingShipment->shipment_date->year,
                    $nextPendingShipment->shipment_date->month
                );

                $billingDateForShipment = $schedule?->billing_date
                    ?? $nextPendingShipment->shipment_date->copy()->day(15);

                if (today()->lt($billingDateForShipment)) {
                    \Log::warning('Billing safety check failed - too early based on shipment schedule', [
                        'subscription_id' => $subscription->id,
                        'subscription_number' => $subscription->subscription_number,
                        'next_billing_date' => $subscription->next_billing_date?->toDateString(),
                        'shipment_billing_date' => $billingDateForShipment->toDateString(),
                        'next_pending_shipment' => $nextPendingShipment->shipment_date->toDateString(),
                    ]);
                    $this->warn("  ⚠️ Safety check failed - shipment schedule says too soon");
                    $skippedCount++;
                    continue;
                }
            }
            
            $maxAttempts = 3;
            $retryDelay = 30; // seconds

            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
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
                        break;
                    } else {
                        DB::rollBack();

                        if ($result['error'] === 'already_charged_today') {
                            $this->warn("  ⚠️ Already charged today - skipping");
                            $skippedCount++;
                            break;
                        }

                        // Network error — retry after delay
                        if (($result['network_error'] ?? false) && $attempt < $maxAttempts) {
                            $this->warn("  ⚠️ Network error (attempt {$attempt}/{$maxAttempts}), retrying in {$retryDelay}s...");
                            sleep($retryDelay);
                            continue;
                        }

                        $this->error("  ✗ Failed: {$result['error']}");
                        $failedCount++;

                        $results[] = [
                            'subscription' => $subscriptionNumber,
                            'status' => 'failed',
                            'error' => $result['error'],
                        ];
                        break;
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
                    break;
                }
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
        
        // Mark cron as run successfully (only for full runs, not single subscription)
        if (!$specificSubscriptionId) {
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

