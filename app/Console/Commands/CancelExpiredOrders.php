<?php

namespace App\Console\Commands;

use App\Mail\OrderCancelledExpired;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';

    protected $description = 'Cancel unpaid orders older than 24 hours and restore stock';

    public function handle()
    {
        $this->info('Looking for expired unpaid orders...');

        $orders = Order::where('payment_status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->where('status', '!=', 'cancelled')
            ->with(['items.product', 'user'])
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No expired orders found.');
            return 0;
        }

        $this->info("Found {$orders->count()} expired order(s).");

        $cancelledCount = 0;
        $failedCount = 0;

        foreach ($orders as $order) {
            try {
                // Restore stock for each order item
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }

                // Cancel the order
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'failed',
                ]);

                \Log::info('Order cancelled due to expired payment', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);

                // Send notification email
                $email = $order->shipping_address['email'] ?? $order->user?->email;
                if ($email) {
                    retry(3, function () use ($email, $order) {
                        Mail::to($email)->send(new OrderCancelledExpired($order));
                    }, 5000);
                }

                $this->info("Cancelled order #{$order->id} ({$order->order_number}), stock restored");
                $cancelledCount++;

            } catch (\Exception $e) {
                $this->error("Failed to cancel order #{$order->id}: " . $e->getMessage());
                \Log::error('Failed to cancel expired order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                $failedCount++;
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("- Orders cancelled: {$cancelledCount}");
        if ($failedCount > 0) {
            $this->warn("- Failed: {$failedCount}");
        }

        return 0;
    }
}
