<?php

namespace App\Console\Commands;

use App\Mail\OrderPaymentFailed;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestOrderPaymentFailedEmail extends Command
{
    protected $signature = 'email:test-order-payment-failed {email} {--order-id=}';
    protected $description = 'Send a test order payment failed email';

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
        
        // Fake failure reason for testing
        $failureReason = 'Nedostatek prostředků na kartě';
        
        try {
            Mail::to($email)->send(new OrderPaymentFailed($order, $failureReason));
            $this->info("Order payment failed email sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
