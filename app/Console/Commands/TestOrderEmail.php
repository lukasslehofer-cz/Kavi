<?php

namespace App\Console\Commands;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestOrderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-order {email} {--order-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test order confirmation email by sending it to specified email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $orderId = $this->option('order-id');

        // Get order - either specified or latest
        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("Order with ID {$orderId} not found!");
                return 1;
            }
        } else {
            $order = Order::with('items')->latest()->first();
            if (!$order) {
                $this->error('No orders found in database. Create an order first.');
                return 1;
            }
            $this->info("Using latest order: {$order->order_number}");
        }

        $this->info("Sending test email to: {$email}");
        $this->info("Order: {$order->order_number}");
        
        try {
            Mail::to($email)->send(new OrderConfirmation($order));
            $this->info('✓ Email sent successfully!');
            $this->line('');
            $this->line('Check your inbox at: ' . $email);
            $this->line('If you don\'t see it, check your spam folder.');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->line('');
            $this->line('Troubleshooting:');
            $this->line('1. Check your .env configuration');
            $this->line('2. Verify Mailgun credentials');
            $this->line('3. Check storage/logs/laravel.log for details');
            return 1;
        }
    }
}

