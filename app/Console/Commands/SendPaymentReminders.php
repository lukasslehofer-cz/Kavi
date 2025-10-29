<?php

namespace App\Console\Commands;

use App\Mail\UpcomingPaymentReminder;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminder emails to subscribers 3 days before their next billing date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Looking for subscriptions with payments due in 3 days...');
        
        // Get date 3 days from now
        $targetDate = now()->addDays(3)->startOfDay();
        
        // Find active subscriptions with next_billing_date in 3 days
        $subscriptions = Subscription::where('status', 'active')
            ->whereDate('next_billing_date', $targetDate->toDateString())
            ->whereNotNull('next_billing_date')
            ->with('user')
            ->get();
        
        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions found with payments due in 3 days.');
            return 0;
        }
        
        $this->info("Found {$subscriptions->count()} subscription(s) to notify.");
        
        $sentCount = 0;
        $failedCount = 0;
        
        foreach ($subscriptions as $subscription) {
            try {
                // Get email from shipping_address or user
                $email = $subscription->shipping_address['email'] ?? $subscription->user->email ?? null;
                
                if (!$email) {
                    $this->warn("Subscription #{$subscription->id} has no email address. Skipping.");
                    $failedCount++;
                    continue;
                }
                
                // Send reminder email
                Mail::to($email)->send(new UpcomingPaymentReminder($subscription));
                
                $this->info("✓ Sent reminder to {$email} for subscription #{$subscription->id}");
                $sentCount++;
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder for subscription #{$subscription->id}: " . $e->getMessage());
                \Log::error('Failed to send payment reminder', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                $failedCount++;
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("- Emails sent: {$sentCount}");
        if ($failedCount > 0) {
            $this->warn("- Failed: {$failedCount}");
        }
        
        return 0;
    }
}

