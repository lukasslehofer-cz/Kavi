<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionPaused;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionPausedEmail extends Command
{
    protected $signature = 'email:test-subscription-paused {email} {--subscription-id=} {--reason=user_request}';
    protected $description = 'Send a test subscription paused email (--reason=user_request|payment_failed)';

    public function handle()
    {
        $email = $this->argument('email');
        $subscriptionId = $this->option('subscription-id');
        $reason = $this->option('reason');
        
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
        
        // Set test paused_until_date
        $subscription->paused_until_date = Carbon::now()->addMonth();
        
        try {
            Mail::to($email)->send(new SubscriptionPaused($subscription, $reason));
            $this->info("Subscription paused email (reason: {$reason}) sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
