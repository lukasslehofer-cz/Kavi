<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionPaymentSuccess;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionPaymentSuccessEmail extends Command
{
    protected $signature = 'email:test-subscription-payment-success {email} {--subscription-id=}';
    protected $description = 'Send a test subscription payment success email';

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
        
        // Get or create a fake payment
        $payment = SubscriptionPayment::where('subscription_id', $subscription->id)->latest()->first();
        if (!$payment) {
            $payment = new SubscriptionPayment([
                'subscription_id' => $subscription->id,
                'amount' => $subscription->configured_price,
                'currency' => $subscription->currency ?? 'CZK',
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }
        
        try {
            Mail::to($email)->send(new SubscriptionPaymentSuccess($subscription, $payment));
            $this->info("Subscription payment success email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
