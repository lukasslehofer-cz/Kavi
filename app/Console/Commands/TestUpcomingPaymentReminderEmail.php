<?php

namespace App\Console\Commands;

use App\Mail\UpcomingPaymentReminder;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestUpcomingPaymentReminderEmail extends Command
{
    protected $signature = 'email:test-upcoming-payment-reminder {email} {--subscription-id=}';
    protected $description = 'Send a test upcoming payment reminder email';

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
        
        // Set test next_billing_date
        $subscription->next_billing_date = Carbon::now()->addDays(3);
        
        try {
            Mail::to($email)->send(new UpcomingPaymentReminder($subscription));
            $this->info("Upcoming payment reminder email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
