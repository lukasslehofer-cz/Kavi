<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\FakturoidService;
use App\Services\StripeService;
use Illuminate\Console\Command;

class RepairSubscriptionBilling20260315 extends Command
{
    protected $signature = 'subscriptions:repair-2026-03-15
                            {--dry-run : Show what would be done without making changes}
                            {--only= : Run only "invoices" or "charges"}
                            {--retries=3 : Number of retry attempts per operation}
                            {--delay=30 : Delay in seconds between retries}';

    protected $description = 'One-time repair for subscription billing failures from 2026-03-15 network outage';

    public function handle(FakturoidService $fakturoidService, StripeService $stripeService): int
    {
        $isDryRun = $this->option('dry-run');
        $only = $this->option('only');
        $maxRetries = (int) $this->option('retries');
        $retryDelay = (int) $this->option('delay');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - no changes will be made');
        }

        $results = [];

        // ── Category A: Payments succeeded, missing Fakturoid invoices ──
        if (!$only || $only === 'invoices') {
            $this->info('');
            $this->info('=== Kategorie A: Doplnění Fakturoid faktur ===');

            $invoiceRepairs = [
                ['subscription_id' => 37, 'payment_id' => 76, 'send_email' => false],
                ['subscription_id' => 53, 'payment_id' => 79, 'send_email' => false],
                ['subscription_id' => 54, 'payment_id' => 80, 'send_email' => false],
            ];

            foreach ($invoiceRepairs as $repair) {
                $payment = SubscriptionPayment::find($repair['payment_id']);

                if (!$payment) {
                    $this->error("Payment #{$repair['payment_id']} not found!");
                    $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', 'FAILED - payment not found'];
                    continue;
                }

                $subscription = $payment->subscription;
                $this->info("Sub #{$repair['subscription_id']} (payment #{$repair['payment_id']}): " .
                    ($payment->hasFaktura() ? "faktura already exists ({$payment->fakturoid_invoice_id})" : 'faktura missing'));

                if ($payment->hasFaktura()) {
                    $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', "SKIPPED - already exists ({$payment->invoice_number})"];
                    continue;
                }

                if ($isDryRun) {
                    $this->comment("  [DRY RUN] Would create Fakturoid invoice");
                    $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', 'DRY RUN'];
                    continue;
                }

                // Retry loop for Fakturoid
                $success = false;
                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    try {
                        if ($attempt > 1) {
                            $this->warn("  Retry #{$attempt} after {$retryDelay}s delay...");
                            sleep($retryDelay);
                        }
                        $fakturoidService->processInvoiceForSubscriptionPayment($payment);
                        $payment->refresh();

                        if ($payment->hasFaktura()) {
                            $this->info("  Fakturoid invoice created: {$payment->fakturoid_invoice_id} ({$payment->invoice_number})");
                            $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', "OK - {$payment->invoice_number}"];
                            $success = true;
                            break;
                        }

                        $this->warn("  Attempt #{$attempt}: processInvoiceForSubscriptionPayment returned but invoice not saved");
                    } catch (\Exception $e) {
                        $this->warn("  Attempt #{$attempt} failed: {$e->getMessage()}");
                    }
                }

                if (!$success) {
                    $this->error("  All {$maxRetries} attempts failed for Sub #{$repair['subscription_id']}");
                    $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', "FAILED after {$maxRetries} attempts"];
                }

                // Send email if needed and invoice was created
                if ($success && $repair['send_email']) {
                    $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;
                    try {
                        if ($email) {
                            $payment->refresh();
                            \Mail::to($email)->send(new \App\Mail\SubscriptionPaymentSuccess($subscription, $payment));
                            $this->info("  Email sent to {$email}");
                            $results[] = ['Sub ' . $repair['subscription_id'], 'Email', "OK - {$email}"];
                        }
                    } catch (\Exception $e) {
                        $this->error("  Email error: {$e->getMessage()}");
                        $results[] = ['Sub ' . $repair['subscription_id'], 'Email', "FAILED - {$e->getMessage()}"];
                    }
                }
            }
        }

        // ── Category B: Payments failed completely, need re-charge ──
        if (!$only || $only === 'charges') {
            $this->info('');
            $this->info('=== Kategorie B: Opětovné stržení plateb ===');

            $rechargeSubscriptionIds = [43];

            foreach ($rechargeSubscriptionIds as $subId) {
                $subscription = Subscription::find($subId);

                if (!$subscription) {
                    $this->error("Subscription #{$subId} not found!");
                    $results[] = ["Sub {$subId}", 'Charge', 'FAILED - not found'];
                    continue;
                }

                // Skip if already charged today
                if ($subscription->payments()->whereDate('paid_at', today())->exists()) {
                    $this->info("Sub #{$subId}: already charged today, skipping");
                    $results[] = ["Sub {$subId}", 'Charge', 'SKIPPED - already charged today'];
                    continue;
                }

                $this->info("Sub #{$subId} ({$subscription->subscription_number}): status={$subscription->status}, failures={$subscription->payment_failure_count}");

                if ($isDryRun) {
                    $this->comment("  [DRY RUN] Would reset status to active and charge");
                    $results[] = ["Sub {$subId}", 'Charge', 'DRY RUN'];
                    continue;
                }

                // Retry loop for Stripe charge
                $success = false;
                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    if ($attempt > 1) {
                        $this->warn("  Retry #{$attempt} after {$retryDelay}s delay...");
                        sleep($retryDelay);
                    }

                    // Reset subscription status before each attempt
                    $subscription->update([
                        'status' => 'active',
                        'payment_failure_count' => 0,
                        'last_payment_failure_at' => null,
                        'last_payment_failure_reason' => null,
                    ]);
                    $this->info("  Status reset to active" . ($attempt > 1 ? " (attempt #{$attempt})" : ''));

                    try {
                        $result = $stripeService->chargeSubscriptionPayment($subscription);

                        if ($result['success']) {
                            $this->info("  Payment successful: {$result['payment_intent_id']}");
                            $results[] = ["Sub {$subId}", 'Charge', "OK - {$result['payment_intent_id']}"];
                            $success = true;
                            break;
                        }

                        $this->warn("  Attempt #{$attempt} failed: {$result['error']}");
                    } catch (\Exception $e) {
                        $this->warn("  Attempt #{$attempt} exception: {$e->getMessage()}");
                    }
                }

                if (!$success) {
                    // Ensure subscription is left in active state for manual retry
                    $subscription->update([
                        'status' => 'active',
                        'payment_failure_count' => 0,
                        'last_payment_failure_at' => null,
                        'last_payment_failure_reason' => null,
                    ]);
                    $this->error("  All {$maxRetries} attempts failed. Status reset to active for manual retry.");
                    $results[] = ["Sub {$subId}", 'Charge', "FAILED after {$maxRetries} attempts (status=active)"];
                }
            }
        }

        // ── Results summary ──
        $this->info('');
        $this->info('=== Výsledky ===');
        $this->table(['Subscription', 'Action', 'Result'], $results);

        return 0;
    }
}
