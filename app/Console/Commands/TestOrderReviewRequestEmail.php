<?php

namespace App\Console\Commands;

use App\Mail\OrderReviewRequestMail;
use App\Models\Order;
use App\Models\ReviewRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestOrderReviewRequestEmail extends Command
{
    protected $signature = 'email:test-order-review-request {email} {--order-id=}';
    protected $description = 'Send a test order review request email';

    public function handle()
    {
        $email = $this->argument('email');
        $orderId = $this->option('order-id');
        
        // Get order - either specified or latest
        if ($orderId) {
            $order = Order::with(['items.product.roastery'])->find($orderId);
            if (!$order) {
                $this->error("Order with ID {$orderId} not found!");
                return 1;
            }
        } else {
            $order = Order::with(['items.product.roastery'])->latest()->first();
            if (!$order) {
                $this->error('No orders found in database');
                return 1;
            }
            $this->info("Using latest order: {$order->order_number}");
        }
        
        // Create a fake review request for testing (not saved to DB)
        $reviewRequest = new ReviewRequest([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'review_type' => 'order',
            'tracking_token' => Str::random(32),
            'email_sent_at' => now(),
        ]);
        
        try {
            Mail::to($email)->send(new OrderReviewRequestMail($order, $reviewRequest));
            $this->info("Order review request email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
