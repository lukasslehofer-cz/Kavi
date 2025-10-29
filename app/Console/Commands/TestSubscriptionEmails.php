<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionBoxPreparing;
use App\Mail\SubscriptionBoxShipped;
use App\Mail\SubscriptionCancelled;
use App\Mail\SubscriptionPaused;
use App\Mail\SubscriptionPaymentFailed;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionEmails extends Command
{
    protected $signature = 'email:test-subscription-all {email}';
    protected $description = 'Send all subscription emails to test address';

    public function handle()
    {
        $email = $this->argument('email');
        
        // Get a subscription
        $subscription = Subscription::latest()->first();
        
        if (!$subscription) {
            $this->error('No subscriptions found in database');
            return 1;
        }
        
        $this->info('Using subscription: ' . $subscription->subscription_number);
        $this->info('Sending test emails to: ' . $email);
        $this->info('');
        
        // 1. Box Preparing
        $this->info('1/5 Sending: Box Preparing...');
        Mail::to($email)->send(new SubscriptionBoxPreparing($subscription));
        $this->info('✓ Sent!');
        sleep(2);
        
        // 2. Box Shipped
        $this->info('2/5 Sending: Box Shipped...');
        // Set some test tracking data
        $subscription->packeta_packet_id = 'Z123456789';
        $subscription->packeta_tracking_url = 'https://tracking.packeta.com/cs/?id=Z123456789';
        Mail::to($email)->send(new SubscriptionBoxShipped($subscription));
        $this->info('✓ Sent!');
        sleep(2);
        
        // 3. Payment Failed
        $this->info('3/5 Sending: Payment Failed...');
        Mail::to($email)->send(new SubscriptionPaymentFailed($subscription, 'Nedostatek prostředků na účtu'));
        $this->info('✓ Sent!');
        sleep(2);
        
        // 4. Subscription Paused (both reasons)
        $this->info('4/5 Sending: Subscription Paused (user request)...');
        Mail::to($email)->send(new SubscriptionPaused($subscription, 'user_request'));
        $this->info('✓ Sent!');
        sleep(2);
        
        // 5. Subscription Cancelled
        $this->info('5/5 Sending: Subscription Cancelled...');
        Mail::to($email)->send(new SubscriptionCancelled($subscription));
        $this->info('✓ Sent!');
        
        $this->info('');
        $this->info('✅ All 5 subscription emails sent successfully!');
        $this->info('Check your inbox at: ' . $email);
        
        return 0;
    }
}

