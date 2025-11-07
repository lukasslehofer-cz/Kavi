<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionPaymentSuccess;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestPaymentSuccessEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:payment-success-email {email} {--subscription_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test subscription payment success email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $subscriptionId = $this->option('subscription_id');
        
        // Find a subscription to use as test data
        if ($subscriptionId) {
            $subscription = Subscription::find($subscriptionId);
        } else {
            $subscription = Subscription::whereNotNull('next_billing_date')
                ->where('status', 'active')
                ->first();
        }
        
        if (!$subscription) {
            $this->error('No subscription found. Please create a subscription first or specify --subscription_id');
            return 1;
        }
        
        // Get the latest payment or create a fake one
        $payment = $subscription->payments()->latest()->first();
        
        if (!$payment) {
            // Create a fake payment for testing
            $payment = new SubscriptionPayment([
                'subscription_id' => $subscription->id,
                'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
                'amount' => $subscription->configured_price ?? 500,
                'currency' => 'czk',
                'status' => 'paid',
                'paid_at' => now(),
                'period_start' => now()->subMonth(),
                'period_end' => now(),
            ]);
            
            $this->warn('No payment found, using fake test payment data');
        }
        
        $this->info('Sending test payment success email...');
        $this->line('');
        $this->line("To: {$email}");
        $this->line("Subscription: {$subscription->subscription_number}");
        $this->line("Amount: {$payment->amount} Kč");
        $this->line("Next billing: {$subscription->next_billing_date?->format('j. n. Y')}");
        $this->line('');
        
        try {
            Mail::to($email)->send(new SubscriptionPaymentSuccess($subscription, $payment));
            
            $this->info('✓ Test email sent successfully!');
            $this->line('Check your inbox at: ' . $email);
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            $this->line('');
            $this->line('Error details:');
            $this->line($e->getTraceAsString());
            
            return 1;
        }
    }
}

