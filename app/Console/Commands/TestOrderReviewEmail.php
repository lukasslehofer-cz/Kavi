<?php

namespace App\Console\Commands;

use App\Mail\OrderReviewRequest;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestOrderReviewEmail extends Command
{
    protected $signature = 'email:test-order-review {email}';
    protected $description = 'Send a test order review request email';

    public function handle()
    {
        $email = $this->argument('email');
        
        // Get the most recent order
        $order = Order::with(['items.product.roastery'])->latest()->first();
        
        if (!$order) {
            $this->error('No orders found in database');
            return 1;
        }
        
        // Set delivered date for testing
        $order->delivered_at = now()->subDays(7);
        
        try {
            Mail::to($email)->send(new OrderReviewRequest($order));
            $this->info("Order review request email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
