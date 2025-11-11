<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CleanupOrderNotes extends Command
{
    protected $signature = 'orders:cleanup-notes';
    protected $description = 'Cleanup customer_notes containing JSON cart_backup data';

    public function handle()
    {
        $this->info('Finding orders with JSON in customer_notes...');

        $orders = Order::whereNotNull('customer_notes')
            ->where('customer_notes', 'like', '%cart_backup%')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders need cleanup.');
            return 0;
        }

        $this->info("Found {$orders->count()} orders to cleanup.");
        
        $bar = $this->output->createProgressBar($orders->count());
        $bar->start();

        $cleaned = 0;
        
        foreach ($orders as $order) {
            try {
                $data = json_decode($order->customer_notes, true);
                
                if (is_array($data)) {
                    // Move cart_backup to admin_notes if it's not already there
                    if (isset($data['cart_backup'])) {
                        $adminNotes = json_decode($order->admin_notes, true) ?: [];
                        $adminNotes['cart_backup'] = $data['cart_backup'];
                        $order->admin_notes = json_encode($adminNotes);
                    }
                    
                    // Restore original notes or clear if none
                    $order->customer_notes = $data['original_notes'] ?? null;
                    $order->save();
                    
                    $cleaned++;
                }
            } catch (\Exception $e) {
                $this->error("\nFailed to cleanup order #{$order->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ Cleaned up {$cleaned} orders.");

        return 0;
    }
}









