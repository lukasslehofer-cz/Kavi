<?php

namespace App\Observers;

use App\Mail\OrderDelivered;
use App\Mail\OrderShipped;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Send "order shipped" email when shipped_at is set
        if ($order->isDirty('shipped_at') && $order->shipped_at !== null) {
            $this->sendOrderShippedEmail($order);
        }
        
        // Send "order delivered" email when delivered_at is set
        if ($order->isDirty('delivered_at') && $order->delivered_at !== null) {
            $this->sendOrderDeliveredEmail($order);
        }
    }
    
    /**
     * Send order shipped email
     */
    protected function sendOrderShippedEmail(Order $order): void
    {
        try {
            // Get email from shipping address or user
            $email = $order->shipping_address['email'] ?? $order->user?->email ?? null;
            
            if ($email) {
                Mail::to($email)->send(new OrderShipped($order));
                \Log::info('Order shipped email sent', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'email' => $email
                ]);
            } else {
                \Log::warning('Could not send order shipped email - no email found', [
                    'order_id' => $order->id
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send order shipped email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Send order delivered email
     */
    protected function sendOrderDeliveredEmail(Order $order): void
    {
        try {
            // Get email from shipping address or user
            $email = $order->shipping_address['email'] ?? $order->user?->email ?? null;
            
            if ($email) {
                Mail::to($email)->send(new OrderDelivered($order));
                \Log::info('Order delivered email sent', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'email' => $email
                ]);
            } else {
                \Log::warning('Could not send order delivered email - no email found', [
                    'order_id' => $order->id
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send order delivered email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
