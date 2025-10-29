<?php

namespace App\Console\Commands;

use App\Mail\UpcomingPaymentReminder;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestPaymentReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-payment-reminder {email} {--subscription-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test payment reminder email by sending it to specified email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $subscriptionId = $this->option('subscription-id');

        // Get subscription - either specified or latest active
        if ($subscriptionId) {
            $subscription = Subscription::find($subscriptionId);
            if (!$subscription) {
                $this->error("Subscription with ID {$subscriptionId} not found!");
                return 1;
            }
        } else {
            $subscription = Subscription::where('status', 'active')->latest()->first();
            if (!$subscription) {
                $this->error('No active subscriptions found in database.');
                return 1;
            }
            $this->info("Using latest active subscription ID: {$subscription->id}");
        }

        $this->info("Sending test email to: {$email}");
        $this->info("Subscription ID: {$subscription->id}");
        $this->info("Next billing date: " . ($subscription->next_billing_date ? $subscription->next_billing_date->format('j. n. Y') : 'N/A'));
        
        try {
            Mail::to($email)->send(new UpcomingPaymentReminder($subscription));
            $this->info('✓ Email sent successfully!');
            $this->line('');
            $this->line('Check your inbox at: ' . $email);
            $this->line('If you don\'t see it, check your spam folder.');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->line('');
            $this->line('Troubleshooting:');
            $this->line('1. Check your .env configuration');
            $this->line('2. Verify Mailgun credentials');
            $this->line('3. Check storage/logs/laravel.log for details');
            return 1;
        }
    }
}

