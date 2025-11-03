<?php

namespace App\Console\Commands;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail extends Command
{
    protected $signature = 'order:send-confirmation {order_id}';
    protected $description = 'Manually send order confirmation email';

    public function handle()
    {
        $orderId = $this->argument('order_id');
        $order = Order::find($orderId);

        if (!$order) {
            $this->error("Order #{$orderId} not found.");
            return 1;
        }

        $this->info("Sending confirmation email for Order #{$order->order_number}...");

        try {
            $email = $order->shipping_address['email'] ?? $order->user?->email;
            
            if (!$email) {
                $this->error("No email address found for this order.");
                return 1;
            }

            Mail::to($email)->send(new OrderConfirmation($order));
            
            $this->info("✓ Email sent successfully to: {$email}");
            return 0;
            
        } catch (\Exception $e) {
            $this->error("✗ Failed to send email: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}


