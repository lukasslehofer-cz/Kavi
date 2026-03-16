<?php

namespace App\Console\Commands;

use App\Models\SubscriptionPayment;
use Illuminate\Console\Command;

class ResendPaymentEmails20260315 extends Command
{
    protected $signature = 'subscriptions:resend-emails-2026-03-15
                            {--dry-run : Show what would be done without sending emails}';

    protected $description = 'Resend payment confirmation emails that failed during 2026-03-15 network outage';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - no emails will be sent');
        }

        $emailResends = [
            ['subscription_id' => 21, 'payment_id' => 74],
            ['subscription_id' => 35, 'payment_id' => 75],
            ['subscription_id' => 43, 'payment_id' => 84],
        ];

        $results = [];

        foreach ($emailResends as $item) {
            $payment = SubscriptionPayment::find($item['payment_id']);

            if (!$payment) {
                $this->error("Payment #{$item['payment_id']} not found!");
                $results[] = ['Sub ' . $item['subscription_id'], 'FAILED - payment not found', ''];
                continue;
            }

            $subscription = $payment->subscription;
            $email = $subscription->shipping_address['email'] ?? $subscription->user?->email;

            $this->info("Sub #{$item['subscription_id']} (payment #{$item['payment_id']}): {$email}");

            if (!$email) {
                $this->error("  No email address found!");
                $results[] = ['Sub ' . $item['subscription_id'], 'FAILED - no email', ''];
                continue;
            }

            if ($isDryRun) {
                $this->comment("  [DRY RUN] Would send email to {$email}");
                $results[] = ['Sub ' . $item['subscription_id'], 'DRY RUN', $email];
                continue;
            }

            try {
                \Mail::to($email)->send(new \App\Mail\SubscriptionPaymentSuccess($subscription, $payment));
                $this->info("  Email sent to {$email}");
                \Log::info('Subscription payment confirmation email resent', [
                    'subscription_id' => $item['subscription_id'],
                    'payment_id' => $item['payment_id'],
                    'email' => $email,
                ]);
                $results[] = ['Sub ' . $item['subscription_id'], 'OK', $email];
            } catch (\Throwable $e) {
                $this->error("  Email error: {$e->getMessage()}");
                $results[] = ['Sub ' . $item['subscription_id'], 'FAILED - ' . $e->getMessage(), $email];
            }
        }

        $this->info('');
        $this->info('=== Výsledky ===');
        $this->table(['Subscription', 'Result', 'Email'], $results);

        return 0;
    }
}
