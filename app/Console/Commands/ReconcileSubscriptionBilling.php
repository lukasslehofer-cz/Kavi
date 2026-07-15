<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\SubscriptionShipmentService;
use Illuminate\Console\Command;

class ReconcileSubscriptionBilling extends Command
{
    /**
     * Backfill durable failure state for subscriptions whose payments failed under
     * the old logic (which pushed them to 'paused' and marked the month 'skipped',
     * leaving no per-period record and hiding the unpaid shipment from admin).
     *
     * Pass an id to reconcile a single subscription, otherwise every subscription
     * with an unresolved payment problem is processed.
     */
    protected $signature = 'subscriptions:reconcile-billing {id? : Subscription id to reconcile} {--dry-run : Report changes without writing}';

    protected $description = 'Backfill failed payment records and unpaid shipments for subscriptions with hidden payment problems';

    public function handle(SubscriptionShipmentService $shipmentService): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $id = $this->argument('id');

        $query = Subscription::query();
        if ($id) {
            $query->where('id', $id);
        } else {
            // Subscriptions that still owe money: explicit unpaid, an outstanding
            // pending invoice, a running reminder count, or a payment-failure pause.
            $query->where(function ($q) {
                $q->where('status', 'unpaid')
                    ->orWhereNotNull('pending_invoice_id')
                    ->orWhere('payment_failure_count', '>', 0)
                    ->orWhere('consecutive_unpaid_shipments', '>', 0)
                    ->orWhere(function ($qq) {
                        $qq->where('status', 'paused')
                            ->where('pause_reason', 'payment_failure_skip');
                    });
            });
        }

        $subscriptions = $query->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions to reconcile.');

            return self::SUCCESS;
        }

        $this->info(($isDryRun ? '[DRY RUN] ' : '')."Reconciling {$subscriptions->count()} subscription(s)...");

        $changed = 0;

        foreach ($subscriptions as $subscription) {
            $frequencyMonths = max(1, (int) ($subscription->frequency_months ?? 1));

            // Period currently being billed (catch-up to the present, same as the
            // charge/failure paths).
            $periodEnd = $subscription->next_billing_date?->copy();
            if ($periodEnd) {
                $safety = 24;
                while ($periodEnd->lt(today()) && $safety-- > 0) {
                    $periodEnd->addMonths($frequencyMonths);
                }
            } else {
                $periodEnd = today()->copy();
            }
            $periodStart = $periodEnd->copy()->subMonths($frequencyMonths);

            // Already covered by a paid or failed record for this period? Nothing to do.
            $existing = SubscriptionPayment::where('subscription_id', $subscription->id)
                ->whereDate('period_end', $periodEnd->toDateString())
                ->whereIn('status', ['paid', 'failed'])
                ->first();

            if ($existing && $existing->status === 'paid') {
                continue;
            }

            $reason = $subscription->last_payment_failure_reason ?? 'Reconciled unpaid period';

            $this->line("  {$subscription->subscription_number}: period {$periodEnd->toDateString()} — ".
                ($existing ? 'failed record exists' : 'creating failed record')." (status={$subscription->status})");

            if ($isDryRun) {
                $changed++;
                continue;
            }

            $payment = SubscriptionPayment::firstOrNew([
                'subscription_id' => $subscription->id,
                'status' => 'failed',
                'period_end' => $periodEnd->toDateString(),
            ]);
            $payment->fill([
                'amount' => $payment->amount ?? ($subscription->configured_price ?? $subscription->plan?->price ?? 0),
                'currency' => strtolower($subscription->currency ?? 'CZK'),
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'failure_reason' => $reason,
                'attempts' => max(1, (int) ($payment->attempts ?? 0)),
                'last_attempt_at' => $subscription->last_payment_failure_at ?? now(),
            ]);
            $payment->save();

            // Convert the hidden 'skipped' shipment for this period into a visible
            // 'unpaid' one, or create it if missing.
            $shipmentDate = $payment->expected_shipment_date;
            if ($shipmentDate) {
                $skipped = $subscription->shipments()
                    ->whereDate('shipment_date', $shipmentDate->toDateString())
                    ->where('status', 'skipped')
                    ->whereNull('subscription_payment_id')
                    ->first();

                if ($skipped) {
                    $skipped->update([
                        'status' => 'unpaid',
                        'subscription_payment_id' => $payment->id,
                        'notes' => 'Neuhrazená platba předplatného (reconcile)',
                    ]);
                } else {
                    $shipmentService->markShipmentUnpaid($payment, $subscription);
                }
            }

            // Ensure the consecutive-unpaid counter reflects at least this period.
            if ((int) $subscription->consecutive_unpaid_shipments < 1) {
                $subscription->update(['consecutive_unpaid_shipments' => 1]);
            }

            $changed++;
        }

        $this->info(($isDryRun ? '[DRY RUN] ' : '')."Done. {$changed} subscription(s) ".($isDryRun ? 'would be' : '')." reconciled.");

        return self::SUCCESS;
    }
}
