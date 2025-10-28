<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionConfirmation;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-subscription {email} {--subscription-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test subscription confirmation email by sending it to specified email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $subscriptionId = $this->option('subscription-id');

        // Get subscription - either specified or latest
        if ($subscriptionId) {
            $subscription = Subscription::find($subscriptionId);
            if (!$subscription) {
                $this->error("Subscription with ID {$subscriptionId} not found!");
                return 1;
            }
        } else {
            $subscription = Subscription::latest()->first();
            if (!$subscription) {
                $this->error('No subscriptions found in database. Create a subscription first.');
                return 1;
            }
            $this->info("Using latest subscription ID: {$subscription->id}");
        }

        $this->info("Sending test email to: {$email}");
        $this->info("Subscription ID: {$subscription->id}");
        $this->info("Status: {$subscription->status}");
        
        try {
            Mail::to($email)->send(new SubscriptionConfirmation($subscription));
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

