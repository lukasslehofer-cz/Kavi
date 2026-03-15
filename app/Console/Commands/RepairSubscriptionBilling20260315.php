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
                            {--dry-run : Show what would be done without making changes}';

    protected $description = 'One-time repair for subscription billing failures from 2026-03-15 network outage';

    public function handle(FakturoidService $fakturoidService, StripeService $stripeService): int
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - no changes will be made');
        }

        $results = [];

        // ── Category A: Payments succeeded, missing Fakturoid invoices ──
        $this->info('');
        $this->info('=== Kategorie A: Doplnění Fakturoid faktur ===');

        $invoiceRepairs = [
            ['subscription_id' => 35, 'payment_id' => 75, 'send_email' => true],
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

            // Create invoice if missing
            if (!$payment->hasFaktura()) {
                if ($isDryRun) {
                    $this->comment("  [DRY RUN] Would create Fakturoid invoice");
                    $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', 'DRY RUN'];
                } else {
                    try {
                        $fakturoidService->processInvoiceForSubscriptionPayment($payment);
                        $payment->refresh();
                        $this->info("  Fakturoid invoice created: {$payment->fakturoid_invoice_id} ({$payment->invoice_number})");
                        $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', "OK - {$payment->invoice_number}"];
                    } catch (\Exception $e) {
                        $this->error("  Fakturoid error: {$e->getMessage()}");
                        $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', "FAILED - {$e->getMessage()}"];
                    }
                }
            } else {
                $results[] = ['Sub ' . $repair['subscription_id'], 'Faktura', "SKIPPED - already exists ({$payment->invoice_number})"];
            }

            // Send email if needed
            if ($repair['send_email']) {
                $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;
                $this->info("  Email to: {$email}");

                if ($isDryRun) {
                    $this->comment("  [DRY RUN] Would send confirmation email to {$email}");
                    $results[] = ['Sub ' . $repair['subscription_id'], 'Email', 'DRY RUN'];
                } else {
                    try {
                        if ($email) {
                            // Refresh payment to include invoice PDF
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
        $this->info('');
        $this->info('=== Kategorie B: Opětovné stržení plateb ===');

        $rechargeSubscriptionIds = [43, 45, 48];

        foreach ($rechargeSubscriptionIds as $subId) {
            $subscription = Subscription::find($subId);

            if (!$subscription) {
                $this->error("Subscription #{$subId} not found!");
                $results[] = ["Sub {$subId}", 'Charge', 'FAILED - not found'];
                continue;
            }

            $this->info("Sub #{$subId} ({$subscription->subscription_number}): status={$subscription->status}, failures={$subscription->payment_failure_count}");

            if ($isDryRun) {
                $this->comment("  [DRY RUN] Would reset status to active and charge");
                $results[] = ["Sub {$subId}", 'Charge', 'DRY RUN'];
                continue;
            }

            // Reset subscription status
            $subscription->update([
                'status' => 'active',
                'payment_failure_count' => 0,
                'last_payment_failure_at' => null,
                'last_payment_failure_reason' => null,
            ]);
            $this->info("  Status reset to active");

            // Charge
            try {
                $result = $stripeService->chargeSubscriptionPayment($subscription);

                if ($result['success']) {
                    $this->info("  Payment successful: {$result['payment_intent_id']}");
                    $results[] = ["Sub {$subId}", 'Charge', "OK - {$result['payment_intent_id']}"];
                } else {
                    $this->error("  Payment failed: {$result['error']}");
                    $results[] = ["Sub {$subId}", 'Charge', "FAILED - {$result['error']}"];
                }
            } catch (\Exception $e) {
                $this->error("  Exception: {$e->getMessage()}");
                $results[] = ["Sub {$subId}", 'Charge', "FAILED - {$e->getMessage()}"];
            }
        }

        // ── Results summary ──
        $this->info('');
        $this->info('=== Výsledky ===');
        $this->table(['Subscription', 'Action', 'Result'], $results);

        return 0;
    }
}
