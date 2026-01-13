<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\PacketaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdatePacketaDeliveryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packeta:update-delivery-status 
                            {--dry-run : Show what would be updated without making changes}
                            {--limit=100 : Maximum number of orders to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Packeta API for delivery status and update orders accordingly';

    /**
     * Execute the console command.
     */
    public function handle(PacketaService $packetaService): int
    {
        $dryRun = $this->option('dry-run');
        $limit = (int) $this->option('limit');

        $this->info('Checking Packeta delivery status...');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Find orders that:
        // 1. Have packeta_packet_id (were sent to Packeta)
        // 2. Have status 'submitted' (not yet delivered)
        // 3. Were created in last 30 days (older orders are likely already resolved)
        $orders = Order::whereNotNull('packeta_packet_id')
            ->where('status', 'submitted')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('packeta_sent_at', 'asc')
            ->limit($limit)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders pending delivery status check.');
            Log::info('Packeta delivery status check: No orders to check');
            return Command::SUCCESS;
        }

        $this->info("Found {$orders->count()} orders to check.");
        
        $stats = [
            'checked' => 0,
            'delivered' => 0,
            'returned' => 0,
            'errors' => 0,
            'unchanged' => 0,
        ];

        $this->output->progressStart($orders->count());

        foreach ($orders as $order) {
            $stats['checked']++;
            
            try {
                $status = $packetaService->getPacketStatus($order->packeta_packet_id);
                
                if ($status === null) {
                    Log::warning('Packeta API returned null for order', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'packeta_packet_id' => $order->packeta_packet_id,
                    ]);
                    $stats['errors']++;
                    $this->output->progressAdvance();
                    continue;
                }

                // Debug logging for every status check
                Log::debug('Packeta status check result', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'packeta_packet_id' => $order->packeta_packet_id,
                    'status_code' => $status['statusCode'],
                    'code_text' => $status['codeText'],
                    'is_delivered' => $status['isDelivered'],
                    'is_returned' => $status['isReturned'],
                ]);

                if ($status['isDelivered']) {
                    // Update order as delivered
                    if (!$dryRun) {
                        $order->update([
                            'status' => 'delivered',
                            'delivered_at' => now(),
                            'packeta_shipment_status' => 'delivered',
                        ]);
                        
                        Log::info('Order marked as delivered from Packeta', [
                            'order_id' => $order->id,
                            'order_number' => $order->order_number,
                            'packeta_packet_id' => $order->packeta_packet_id,
                            'packeta_status_code' => $status['statusCode'],
                            'packeta_status_text' => $status['codeText'],
                        ]);
                    }
                    
                    $stats['delivered']++;
                    $this->line(" ✓ Order #{$order->order_number} - DELIVERED");
                    
                } elseif ($status['isReturned']) {
                    // Update order as returned
                    if (!$dryRun) {
                        $order->update([
                            'status' => 'returned',
                            'packeta_shipment_status' => 'returned',
                        ]);
                        
                        Log::warning('Order marked as returned from Packeta', [
                            'order_id' => $order->id,
                            'order_number' => $order->order_number,
                            'packeta_packet_id' => $order->packeta_packet_id,
                            'packeta_status_code' => $status['statusCode'],
                            'packeta_status_text' => $status['codeText'],
                        ]);
                    }
                    
                    $stats['returned']++;
                    $this->line(" ⚠ Order #{$order->order_number} - RETURNED");
                    
                } else {
                    $stats['unchanged']++;
                }

            } catch (\Exception $e) {
                $stats['errors']++;
                Log::error('Error checking Packeta status for order', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'error' => $e->getMessage(),
                ]);
            }
            
            $this->output->progressAdvance();
            
            // Small delay to avoid overwhelming the API
            usleep(100000); // 100ms
        }

        $this->output->progressFinish();

        // Summary
        $this->newLine();
        $this->info('=== Summary ===');
        $this->line("Checked: {$stats['checked']}");
        $this->line("Delivered: {$stats['delivered']}");
        $this->line("Returned: {$stats['returned']}");
        $this->line("Unchanged: {$stats['unchanged']}");
        $this->line("Errors: {$stats['errors']}");
        
        if ($dryRun) {
            $this->warn('DRY RUN - No changes were made');
        }

        Log::info('Packeta delivery status check completed', $stats);

        return Command::SUCCESS;
    }
}

