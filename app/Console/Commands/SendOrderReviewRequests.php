<?php

namespace App\Console\Commands;

use App\Mail\OrderReviewRequest;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOrderReviewRequests extends Command
{
    protected $signature = 'orders:send-review-requests';
    protected $description = 'Send review requests for orders delivered 7 days ago';

    public function handle()
    {
        // Find orders delivered exactly 7 days ago
        $sevenDaysAgo = now()->subDays(7)->startOfDay();
        $sevenDaysAgoEnd = now()->subDays(7)->endOfDay();
        
        $orders = Order::with(['items.product.roastery'])
            ->whereBetween('delivered_at', [$sevenDaysAgo, $sevenDaysAgoEnd])
            ->whereNotNull('delivered_at')
            ->get();
        
        $this->info("Found {$orders->count()} orders to send review requests for");
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($orders as $order) {
            // Get email from shipping address or user
            $email = $order->shipping_address['email'] ?? $order->user?->email ?? null;
            
            if (!$email) {
                $this->warn("No email found for order {$order->order_number}");
                $failCount++;
                continue;
            }
            
            try {
                Mail::to($email)->send(new OrderReviewRequest($order));
                $this->info("Review request sent for order {$order->order_number} to {$email}");
                $successCount++;
            } catch (\Exception $e) {
                $this->error("Failed to send email for order {$order->order_number}: " . $e->getMessage());
                \Log::error('Failed to send review request email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                $failCount++;
            }
        }
        
        $this->info("\nSummary:");
        $this->info("✓ Successfully sent: {$successCount}");
        if ($failCount > 0) {
            $this->warn("✗ Failed: {$failCount}");
        }
        
        return 0;
    }
}
