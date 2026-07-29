<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionBoxShipped;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionBoxShippedEmail extends Command
{
    protected $signature = 'email:test-subscription-box-shipped {email} {--subscription-id=}';
    protected $description = 'Send a test subscription box shipped email';

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
        
        // KROK 8: tracking se čte přes accessor z latestShipment – pro preview
        // podstrčíme fake zásilku (bez zápisu do DB).
        $subscription->setRelation('latestShipment', new \App\Models\SubscriptionShipment([
            'packeta_packet_id' => 'Z123456789',
            'packeta_tracking_url' => 'https://tracking.packeta.com/cs/?id=Z123456789',
        ]));
        
        try {
            Mail::to($email)->send(new SubscriptionBoxShipped($subscription));
            $this->info("Subscription box shipped email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
