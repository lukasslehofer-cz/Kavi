<?php

namespace App\Console\Commands;

use App\Mail\OrderDelivered;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestOrderDeliveredEmail extends Command
{
    protected $signature = 'email:test-order-delivered {email}';
    protected $description = 'Send a test order delivered email';

    public function handle()
    {
        $email = $this->argument('email');
        
        // Get the most recent order
        $order = Order::with(['items.product.roastery'])->latest()->first();
        
        if (!$order) {
            $this->error('No orders found in database');
            return 1;
        }
        
        // Add fake tracking data for testing
        $order->packeta_packet_id = 'Z123456789';
        $order->delivered_at = now();
        
        try {
            Mail::to($email)->send(new OrderDelivered($order));
            $this->info("Order delivered email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
