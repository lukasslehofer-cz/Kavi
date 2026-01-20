<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionPaymentFailed;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionPaymentFailedEmail extends Command
{
    protected $signature = 'email:test-subscription-payment-failed {email} {--subscription-id=}';
    protected $description = 'Send a test subscription payment failed email';

    public function handle()
    {
        $email = $this->argument('email');
        $subscriptionId = $this->option('subscription-id');
        
        if ($subscriptionId) {
            $subscription = Subscription::find($subscriptionId);
            if (!$subscription) {
                $this->error("Subscription with ID {$subscriptionId} not found!");
                return 1;
            }
        } else {
            $subscription = Subscription::latest()->first();
            if (!$subscription) {
                $this->error('No subscriptions found in database');
                return 1;
            }
            $this->info("Using latest subscription: {$subscription->subscription_number}");
        }
        
        $failureReason = 'Nedostatek prostředků na kartě';
        
        try {
            Mail::to($email)->send(new SubscriptionPaymentFailed($subscription, $failureReason));
            $this->info("Subscription payment failed email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
