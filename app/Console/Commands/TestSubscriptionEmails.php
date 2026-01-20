<?php

namespace App\Console\Commands;

use App\Mail\PauseEndingReminder;
use App\Mail\SubscriptionBoxDelivered;
use App\Mail\SubscriptionBoxPreparing;
use App\Mail\SubscriptionBoxShipped;
use App\Mail\SubscriptionCancelled;
use App\Mail\SubscriptionConfirmation;
use App\Mail\SubscriptionPaused;
use App\Mail\SubscriptionPaymentFailed;
use App\Mail\SubscriptionPaymentSuccess;
use App\Mail\UpcomingPaymentReminder;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionEmails extends Command
{
    protected $signature = 'email:test-subscription-all {email} {--subscription-id=}';
    protected $description = 'Send all subscription emails to test address';

    public function handle()
    {
        $email = $this->argument('email');
        $subscriptionId = $this->option('subscription-id');
        
        // Get a subscription
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
        }
        
        $this->info('Using subscription: ' . $subscription->subscription_number);
        $this->info('Sending test emails to: ' . $email);
        $this->info('');
        
        // 1. Subscription Confirmation
        $this->info('1/10 Sending: Subscription Confirmation...');
        Mail::to($email)->send(new SubscriptionConfirmation($subscription));
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 2. Box Preparing
        $this->info('2/10 Sending: Box Preparing...');
        Mail::to($email)->send(new SubscriptionBoxPreparing($subscription));
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 3. Box Shipped
        $this->info('3/10 Sending: Box Shipped...');
        // Set some test tracking data
        $originalPacketId = $subscription->packeta_packet_id;
        $originalTrackingUrl = $subscription->packeta_tracking_url;
        $subscription->packeta_packet_id = 'Z123456789';
        $subscription->packeta_tracking_url = 'https://tracking.packeta.com/cs/?id=Z123456789';
        Mail::to($email)->send(new SubscriptionBoxShipped($subscription));
        // Restore original values
        $subscription->packeta_packet_id = $originalPacketId;
        $subscription->packeta_tracking_url = $originalTrackingUrl;
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 4. Box Delivered
        $this->info('4/10 Sending: Box Delivered...');
        Mail::to($email)->send(new SubscriptionBoxDelivered($subscription));
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 5. Payment Success
        $this->info('5/10 Sending: Payment Success...');
        // Get or create a fake payment
        $payment = SubscriptionPayment::where('subscription_id', $subscription->id)->latest()->first();
        if (!$payment) {
            // Create a temporary fake payment object
            $payment = new SubscriptionPayment([
                'subscription_id' => $subscription->id,
                'amount' => $subscription->configured_price,
                'currency' => $subscription->currency ?? 'CZK',
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }
        Mail::to($email)->send(new SubscriptionPaymentSuccess($subscription, $payment));
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 6. Payment Failed
        $this->info('6/10 Sending: Payment Failed...');
        Mail::to($email)->send(new SubscriptionPaymentFailed($subscription, 'Nedostatek prostředků na účtu'));
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 7. Subscription Paused (user request)
        $this->info('7/10 Sending: Subscription Paused (user request)...');
        // Set paused_until_date for testing
        $originalPausedUntil = $subscription->paused_until_date;
        $subscription->paused_until_date = Carbon::now()->addMonth();
        Mail::to($email)->send(new SubscriptionPaused($subscription, 'user_request'));
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 8. Subscription Paused (payment failed)
        $this->info('8/10 Sending: Subscription Paused (payment failed)...');
        Mail::to($email)->send(new SubscriptionPaused($subscription, 'payment_failed'));
        $subscription->paused_until_date = $originalPausedUntil;
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 9. Pause Ending Reminder
        $this->info('9/10 Sending: Pause Ending Reminder...');
        // Set paused_until_date for testing
        $subscription->paused_until_date = Carbon::now()->addDays(3);
        Mail::to($email)->send(new PauseEndingReminder($subscription));
        $subscription->paused_until_date = $originalPausedUntil;
        $this->info('   ✓ Sent!');
        sleep(2);
        
        // 10. Upcoming Payment Reminder
        $this->info('10/10 Sending: Upcoming Payment Reminder...');
        // Set next_billing_date for testing
        $originalBillingDate = $subscription->next_billing_date;
        $subscription->next_billing_date = Carbon::now()->addDays(3);
        Mail::to($email)->send(new UpcomingPaymentReminder($subscription));
        $subscription->next_billing_date = $originalBillingDate;
        $this->info('   ✓ Sent!');
        
        $this->info('');
        $this->info('11/11 Sending: Subscription Cancelled...');
        Mail::to($email)->send(new SubscriptionCancelled($subscription));
        $this->info('   ✓ Sent!');
        
        $this->info('');
        $this->info('============================================');
        $this->info('✅ All 11 subscription emails sent successfully!');
        $this->info('============================================');
        $this->info('Check your inbox at: ' . $email);
        
        return 0;
    }
}
