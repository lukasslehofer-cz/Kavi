<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;

class RetrySubscriptionInvoiceAndEmail extends Command
{
    protected $signature = 'subscriptions:retry-invoice-email
                            {--subscription-id= : Subscription ID}';

    protected $description = 'Retry creating Fakturoid invoice and sending confirmation emails for a subscription (use after network outage)';

    public function handle(): int
    {
        $subscriptionId = $this->option('subscription-id');

        if (!$subscriptionId) {
            $this->error('Missing --subscription-id option.');
            return 1;
        }

        $subscription = Subscription::find($subscriptionId);
        if (!$subscription) {
            $this->error("Subscription #{$subscriptionId} not found.");
            return 1;
        }

        $payment = $subscription->payments()->first();
        if (!$payment) {
            $this->error("No payment found for subscription #{$subscriptionId}.");
            return 1;
        }

        $this->info("Subscription: #{$subscription->id} {$subscription->subscription_number}");
        $this->info("Customer: " . ($subscription->user?->email ?? $subscription->shipping_address['email'] ?? 'N/A'));
        $this->info("Payment: #{$payment->id}, amount={$payment->amount} {$payment->currency}");
        $this->info("Invoice exists: " . ($payment->hasFaktura() ? "YES ({$payment->fakturoid_invoice_id})" : 'NO'));

        // 1. Fakturoid invoice
        if ($payment->hasFaktura()) {
            $this->info('Fakturoid invoice already exists, skipping.');
        } else {
            $this->info('Creating Fakturoid invoice...');
            try {
                $fakturoidService = app(\App\Services\FakturoidService::class);
                $fakturoidService->processInvoiceForSubscriptionPayment($payment);
                $payment->refresh();
                $this->info("Fakturoid invoice created: ID={$payment->fakturoid_invoice_id}, number={$payment->invoice_number}");
            } catch (\Exception $e) {
                $this->error("Fakturoid error: {$e->getMessage()}");
                return 1;
            }
        }

        // 2. Confirmation email to customer
        $email = $subscription->user?->email
            ?? $subscription->shipping_address['email']
            ?? null;

        if ($email) {
            $this->info("Sending confirmation email to {$email}...");
            try {
                \Mail::to($email)->send(new \App\Mail\SubscriptionConfirmation($subscription));
                $this->info('Confirmation email sent.');
            } catch (\Exception $e) {
                $this->error("Email error: {$e->getMessage()}");
            }
        } else {
            $this->warn('No customer email found, skipping confirmation email.');
        }

        // 3. Admin notification
        $this->info('Sending admin notification...');
        try {
            \App\Mail\AdminOrderNotification::notifyAdmins($subscription);
            $this->info('Admin notification sent.');
        } catch (\Exception $e) {
            $this->error("Admin notification error: {$e->getMessage()}");
        }

        $this->info('Done!');
        $this->table(['Field', 'Value'], [
            ['Subscription', $subscription->subscription_number],
            ['Payment ID', $payment->id],
            ['Fakturoid Invoice ID', $payment->fresh()->fakturoid_invoice_id ?? 'N/A'],
            ['Invoice Number', $payment->fresh()->invoice_number ?? 'N/A'],
            ['Email sent to', $email ?? 'N/A'],
        ]);

        return 0;
    }
}
