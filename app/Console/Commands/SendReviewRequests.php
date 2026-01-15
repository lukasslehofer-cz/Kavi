<?php

namespace App\Console\Commands;

use App\Mail\OrderReviewRequestMail;
use App\Mail\SubscriptionReviewRequestMail;
use App\Models\Order;
use App\Models\ReviewRequest;
use App\Models\Subscription;
use App\Models\SubscriptionShipment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReviewRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:send 
                            {--type= : Type of review requests to send (order|subscription|all)}
                            {--dry-run : Run without actually sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send review requests to customers based on their purchase history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type') ?? 'all';
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No emails will be sent');
        }

        $this->info('🚀 Starting review request sending process...');

        $ordersSent = 0;
        $subscriptionsSent = 0;

        // Send order review requests
        if ($type === 'all' || $type === 'order') {
            $ordersSent = $this->sendOrderReviewRequests($dryRun);
        }

        // Send subscription review requests
        if ($type === 'all' || $type === 'subscription') {
            $subscriptionsSent = $this->sendSubscriptionReviewRequests($dryRun);
        }

        $this->newLine();
        $this->info('✅ Review request sending complete!');
        $this->table(
            ['Type', 'Sent'],
            [
                ['Order Reviews', $ordersSent],
                ['Subscription Reviews', $subscriptionsSent],
                ['Total', $ordersSent + $subscriptionsSent],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Send review requests for delivered orders
     */
    protected function sendOrderReviewRequests(bool $dryRun): int
    {
        $this->info('📦 Processing order review requests...');

        $sent = 0;

        // Get all orders delivered exactly 7 days ago
        $recentOrders = Order::where('status', 'delivered')
            ->whereDate('delivered_at', now()->subDays(7)->toDateString())
            ->whereNotNull('user_id')
            ->with('user')
            ->get();

        $this->line("Found {$recentOrders->count()} delivered orders to check");

        foreach ($recentOrders as $order) {
            // Skip if no user
            if (!$order->user) {
                continue;
            }

            // Check if user has already clicked any review request
            if (ReviewRequest::userHasClickedAnyReview($order->user_id)) {
                continue;
            }

            // Check if we already sent review request for this order
            $existingRequest = ReviewRequest::where('user_id', $order->user_id)
                ->where('order_id', $order->id)
                ->where('review_type', 'order')
                ->first();

            if ($existingRequest) {
                continue;
            }

            // Count how many order review requests this user has received
            $orderReviewCount = ReviewRequest::getSentCountForUser($order->user_id, 'order');

            // Count total delivered orders for this user
            $totalDeliveredOrders = Order::where('user_id', $order->user_id)
                ->where('status', 'delivered')
                ->count();

            // Send review request on 1st, 3rd, 6th, 9th... order
            $isFirstOrder = $totalDeliveredOrders === 1;
            $isThirdMultiple = $totalDeliveredOrders >= 3 && $totalDeliveredOrders % 3 === 0;
            if (!$isFirstOrder && !$isThirdMultiple) {
                continue;
            }

            // Check if we haven't sent request for this specific "cycle"
            // 1st order = 1 request expected, 3rd = 2, 6th = 3, 9th = 4...
            $expectedRequests = $totalDeliveredOrders === 1 ? 1 : (1 + floor($totalDeliveredOrders / 3));
            if ($orderReviewCount >= $expectedRequests) {
                continue;
            }

            // Create review request
            $reviewRequest = ReviewRequest::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'review_type' => 'order',
                'tracking_token' => ReviewRequest::generateTrackingToken(),
                'email_sent_at' => now(),
            ]);

            if (!$dryRun) {
                // Send email
                Mail::to($order->user->email)->send(new OrderReviewRequestMail($order, $reviewRequest));
            }

            $this->line("  ✓ Order #{$order->order_number} → {$order->user->email}");
            $sent++;
        }

        $this->info("📧 Sent {$sent} order review requests");

        return $sent;
    }

    /**
     * Send review requests for subscription shipments delivered 7 days ago
     */
    protected function sendSubscriptionReviewRequests(bool $dryRun): int
    {
        $this->info('🔄 Processing subscription review requests...');

        $sent = 0;

        // Get all subscription shipments delivered exactly 7 days ago
        $recentShipments = SubscriptionShipment::whereDate('delivered_at', now()->subDays(7)->toDateString())
            ->with(['subscription.user'])
            ->get();

        $this->line("Found {$recentShipments->count()} subscription shipments delivered 7 days ago to check");

        foreach ($recentShipments as $shipment) {
            $subscription = $shipment->subscription;
            
            // Skip if no subscription or user
            if (!$subscription || !$subscription->user) {
                continue;
            }

            // Check if user has already clicked any review request
            if (ReviewRequest::userHasClickedAnyReview($subscription->user_id)) {
                continue;
            }

            // Check if we already sent review request for this subscription
            $existingRequest = ReviewRequest::where('user_id', $subscription->user_id)
                ->where('subscription_id', $subscription->id)
                ->where('review_type', 'subscription')
                ->first();

            if ($existingRequest) {
                continue;
            }

            // Count delivered shipments from this subscription
            $deliveredShipments = SubscriptionShipment::where('subscription_id', $subscription->id)
                ->whereNotNull('delivered_at')
                ->count();

            // Send review request after every 3rd subscription delivery (3., 6., 9....)
            if ($deliveredShipments % 3 !== 0) {
                continue;
            }

            // Count how many subscription review requests this user has received
            $subscriptionReviewCount = ReviewRequest::getSentCountForUser($subscription->user_id, 'subscription');

            // Check if we haven't sent request for this specific "cycle"
            $expectedRequests = floor($deliveredShipments / 3);
            if ($subscriptionReviewCount >= $expectedRequests) {
                continue;
            }

            // Create review request
            $reviewRequest = ReviewRequest::create([
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'review_type' => 'subscription',
                'tracking_token' => ReviewRequest::generateTrackingToken(),
                'email_sent_at' => now(),
            ]);

            if (!$dryRun) {
                // Send email
                Mail::to($subscription->user->email)->send(
                    new SubscriptionReviewRequestMail($subscription, $reviewRequest)
                );
            }

            $this->line("  ✓ Subscription #{$subscription->subscription_number} → {$subscription->user->email}");
            $sent++;
        }

        $this->info("📧 Sent {$sent} subscription review requests");

        return $sent;
    }
}
