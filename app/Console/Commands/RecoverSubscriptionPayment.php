<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StripeService;

class RecoverSubscriptionPayment extends Command
{
    protected $signature = 'subscriptions:recover-payment
                            {--session-id= : Stripe Checkout Session ID (cs_live_...)}
                            {--dry-run : Show what would be done without making changes}';

    protected $description = 'Recover a subscription payment that was not processed due to a network error (replays the Stripe webhook)';

    public function handle(StripeService $stripeService): int
    {
        $sessionId = $this->option('session-id');
        $dryRun = $this->option('dry-run');

        if (!$sessionId) {
            $this->error('Missing --session-id option.');
            return 1;
        }

        $this->info("Fetching Stripe checkout session: {$sessionId}");

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
        } catch (\Exception $e) {
            $this->error("Failed to retrieve session from Stripe: {$e->getMessage()}");
            return 1;
        }

        $sessionArray = json_decode(json_encode($session), true);

        // Show key info
        $metadata = $sessionArray['metadata'] ?? [];
        $this->table(['Field', 'Value'], [
            ['Session ID', $sessionArray['id']],
            ['Payment Intent', $sessionArray['payment_intent'] ?? 'N/A'],
            ['Customer', $sessionArray['customer'] ?? 'N/A'],
            ['Amount', ($sessionArray['amount_total'] ?? 0) / 100 . ' ' . strtoupper($sessionArray['currency'] ?? '')],
            ['Type', $metadata['type'] ?? 'N/A'],
            ['Guest Email', $metadata['guest_email'] ?? ($metadata['user_id'] ? "user_id={$metadata['user_id']}" : 'N/A')],
            ['Frequency', ($metadata['frequency_months'] ?? 'N/A') . ' month(s)'],
            ['Next Billing', $metadata['next_billing_date'] ?? 'N/A'],
            ['Configuration', $metadata['configuration'] ?? 'N/A'],
        ]);

        if ($metadata['type'] !== 'custom_billing_subscription') {
            $this->error("Session type is '{$metadata['type']}', expected 'custom_billing_subscription'. Aborting.");
            return 1;
        }

        // Check for idempotency
        $paymentIntentId = $sessionArray['payment_intent'];
        $existing = \App\Models\Subscription::where('stripe_payment_intent_id', $paymentIntentId)->first();
        if ($existing) {
            $this->warn("Subscription already exists for this payment intent: #{$existing->id} ({$existing->subscription_number})");
            return 0;
        }

        if ($dryRun) {
            $this->info('[DRY RUN] Would call handlePaymentSuccess() - no changes made.');
            return 0;
        }

        if (!$this->confirm('Proceed with subscription recovery?')) {
            $this->info('Aborted.');
            return 0;
        }

        $this->info('Processing payment...');

        try {
            $stripeService->handlePaymentSuccess($sessionArray);
        } catch (\Exception $e) {
            $this->error("Recovery failed: {$e->getMessage()}");
            return 1;
        }

        // Verify result
        $subscription = \App\Models\Subscription::where('stripe_payment_intent_id', $paymentIntentId)
            ->orWhere('stripe_session_id', $sessionId)
            ->first();

        if (!$subscription) {
            $this->error('Subscription was not created. Check the logs for details.');
            return 1;
        }

        $payment = $subscription->payments()->first();

        $this->info('Recovery successful!');
        $this->table(['Field', 'Value'], [
            ['Subscription ID', $subscription->id],
            ['Subscription Number', $subscription->subscription_number],
            ['Status', $subscription->status],
            ['User ID', $subscription->user_id ?? 'N/A'],
            ['Next Billing', $subscription->next_billing_date?->toDateString() ?? 'N/A'],
            ['Payment ID', $payment?->id ?? 'N/A'],
            ['Fakturoid Invoice', $payment?->fakturoid_invoice_id ?? 'NOT CREATED'],
            ['Invoice Number', $payment?->invoice_number ?? 'N/A'],
        ]);

        return 0;
    }
}
