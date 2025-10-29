<?php

namespace App\Console\Commands;

use App\Mail\OrderShipped;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestOrderShippedEmail extends Command
{
    protected $signature = 'email:test-order-shipped {email}';
    protected $description = 'Send a test order shipped email';

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
        $order->packeta_tracking_url = 'https://tracking.packeta.com/cs/?id=Z123456789';
        $order->shipped_at = now();
        
        try {
            Mail::to($email)->send(new OrderShipped($order));
            $this->info("Order shipped email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
