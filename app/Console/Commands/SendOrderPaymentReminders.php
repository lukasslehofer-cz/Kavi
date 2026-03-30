<?php

namespace App\Console\Commands;

use App\Mail\OrderPaymentReminder;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOrderPaymentReminders extends Command
{
    protected $signature = 'orders:send-payment-reminders';

    protected $description = 'Send payment reminder emails for orders with pending payment older than 2 hours';

    public function handle()
    {
        $this->info('Looking for orders with pending payments...');

        $orders = Order::where('payment_status', 'pending')
            ->where('created_at', '<', now()->subHours(2))
            ->whereNull('payment_reminder_sent_at')
            ->where('status', '!=', 'cancelled')
            ->with('user')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders found needing payment reminders.');
            return 0;
        }

        $this->info("Found {$orders->count()} order(s) to notify.");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($orders as $order) {
            try {
                $email = $order->shipping_address['email'] ?? $order->user->email ?? null;

                if (!$email) {
                    $this->warn("Order #{$order->id} has no email address. Skipping.");
                    $failedCount++;
                    continue;
                }

                retry(3, function () use ($email, $order) {
                    Mail::to($email)->send(new OrderPaymentReminder($order));
                }, 5000);

                $order->update(['payment_reminder_sent_at' => now()]);

                $this->info("Sent reminder to {$email} for order #{$order->id}");
                $sentCount++;

            } catch (\Exception $e) {
                $this->error("Failed to send reminder for order #{$order->id}: " . $e->getMessage());
                \Log::error('Failed to send order payment reminder', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                $failedCount++;
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("- Emails sent: {$sentCount}");
        if ($failedCount > 0) {
            $this->warn("- Failed: {$failedCount}");
        }

        return 0;
    }
}
