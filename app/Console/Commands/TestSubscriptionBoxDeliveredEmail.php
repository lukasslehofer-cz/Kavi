<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionBoxDelivered;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionBoxDeliveredEmail extends Command
{
    protected $signature = 'email:test-subscription-box-delivered {email} {--subscription-id=}';
    protected $description = 'Send a test subscription box delivered email';

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
        
        try {
            Mail::to($email)->send(new SubscriptionBoxDelivered($subscription));
            $this->info("Subscription box delivered email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
