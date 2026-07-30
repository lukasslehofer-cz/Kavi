<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\SubscriptionShipment;
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
                            {--limit=100 : Maximum number of items to check per type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Packeta API for delivery status and update orders and subscription shipments accordingly';

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

        // Check Orders
        $orderStats = $this->checkOrders($packetaService, $dryRun, $limit);
        
        // Check Subscription Shipments
        $shipmentStats = $this->checkSubscriptionShipments($packetaService, $dryRun, $limit);

        // Combined Summary
        $this->newLine();
        $this->info('=== Combined Summary ===');
        $this->line("Orders checked: {$orderStats['checked']}, delivered: {$orderStats['delivered']}, returned: {$orderStats['returned']}");
        $this->line("Shipments checked: {$shipmentStats['checked']}, delivered: {$shipmentStats['delivered']}, returned: {$shipmentStats['returned']}");
        
        if ($dryRun) {
            $this->warn('DRY RUN - No changes were made');
        }

        Log::info('Packeta delivery status check completed', [
            'orders' => $orderStats,
            'shipments' => $shipmentStats,
        ]);

        return Command::SUCCESS;
    }

    /**
     * Check and update order delivery statuses
     */
    protected function checkOrders(PacketaService $packetaService, bool $dryRun, int $limit): array
    {
        $this->newLine();
        $this->info('=== Checking Orders ===');

        // Find orders that:
        // 1. Have packeta_packet_id (were sent to Packeta)
        // 2. Are still in transit - 'submitted' nebo 'shipped' (admin může stav
        //    posunout ručně, dřív takové objednávky z dosahu polleru vypadly)
        // 3. Were created in last 30 days (older orders are likely already resolved)
        $orders = Order::whereNotNull('packeta_packet_id')
            ->whereIn('status', ['submitted', 'shipped'])
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('packeta_sent_at', 'asc')
            ->limit($limit)
            ->get();

        $this->reportUntrackable(
            Order::whereNull('packeta_packet_id')
                ->whereIn('status', ['submitted', 'shipped'])
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
            'objednávek'
        );

        $stats = [
            'checked' => 0,
            'delivered' => 0,
            'returned' => 0,
            'errors' => 0,
            'unchanged' => 0,
        ];

        if ($orders->isEmpty()) {
            $this->info('No orders pending delivery status check.');
            return $stats;
        }

        $this->info("Found {$orders->count()} orders to check.");
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
                Log::debug('Packeta status check result for order', [
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

        $this->info("Orders - Checked: {$stats['checked']}, Delivered: {$stats['delivered']}, Returned: {$stats['returned']}, Errors: {$stats['errors']}");

        return $stats;
    }

    /**
     * Check and update subscription shipment delivery statuses
     */
    protected function checkSubscriptionShipments(PacketaService $packetaService, bool $dryRun, int $limit): array
    {
        $this->newLine();
        $this->info('=== Checking Subscription Shipments ===');

        // Find subscription shipments that:
        // 1. Have packeta_packet_id (were sent to Packeta)
        // 2. Have status 'sent' (not yet delivered)
        // 3. Were sent in last 30 days
        $shipments = SubscriptionShipment::with('subscription')
            ->whereNotNull('packeta_packet_id')
            ->where('status', 'sent')
            ->where('sent_at', '>=', now()->subDays(30))
            ->orderBy('sent_at', 'asc')
            ->limit($limit)
            ->get();

        $this->reportUntrackable(
            SubscriptionShipment::whereNull('packeta_packet_id')
                ->where('status', 'sent')
                ->where('sent_at', '>=', now()->subDays(30))
                ->count(),
            'zásilek předplatného'
        );

        $stats = [
            'checked' => 0,
            'delivered' => 0,
            'returned' => 0,
            'errors' => 0,
            'unchanged' => 0,
        ];

        if ($shipments->isEmpty()) {
            $this->info('No subscription shipments pending delivery status check.');
            return $stats;
        }

        $this->info("Found {$shipments->count()} subscription shipments to check.");
        $this->output->progressStart($shipments->count());

        foreach ($shipments as $shipment) {
            $stats['checked']++;
            
            try {
                $status = $packetaService->getPacketStatus($shipment->packeta_packet_id);
                
                if ($status === null) {
                    Log::warning('Packeta API returned null for subscription shipment', [
                        'shipment_id' => $shipment->id,
                        'subscription_id' => $shipment->subscription_id,
                        'packeta_packet_id' => $shipment->packeta_packet_id,
                    ]);
                    $stats['errors']++;
                    $this->output->progressAdvance();
                    continue;
                }

                // Debug logging for every status check
                Log::debug('Packeta status check result for subscription shipment', [
                    'shipment_id' => $shipment->id,
                    'subscription_id' => $shipment->subscription_id,
                    'subscription_number' => $shipment->subscription?->subscription_number,
                    'packeta_packet_id' => $shipment->packeta_packet_id,
                    'status_code' => $status['statusCode'],
                    'code_text' => $status['codeText'],
                    'is_delivered' => $status['isDelivered'],
                    'is_returned' => $status['isReturned'],
                ]);

                if ($status['isDelivered']) {
                    // Update shipment as delivered
                    if (!$dryRun) {
                        // KROK 8: jediný zápis "doručeno" přes centrální službu (observer pošle e-mail).
                        app(\App\Services\SubscriptionShipmentService::class)->markAsDelivered($shipment);

                        Log::info('Subscription shipment marked as delivered from Packeta', [
                            'shipment_id' => $shipment->id,
                            'subscription_id' => $shipment->subscription_id,
                            'subscription_number' => $shipment->subscription?->subscription_number,
                            'packeta_packet_id' => $shipment->packeta_packet_id,
                            'packeta_status_code' => $status['statusCode'],
                            'packeta_status_text' => $status['codeText'],
                        ]);
                    }
                    
                    $stats['delivered']++;
                    $subscriptionNumber = $shipment->subscription?->subscription_number ?? $shipment->subscription_id;
                    $this->line(" ✓ Subscription #{$subscriptionNumber} - DELIVERED");
                    
                } elseif ($status['isReturned']) {
                    // Update shipment as returned
                    if (!$dryRun) {
                        $shipment->update([
                            'status' => 'returned',
                        ]);
                        
                        Log::warning('Subscription shipment marked as returned from Packeta', [
                            'shipment_id' => $shipment->id,
                            'subscription_id' => $shipment->subscription_id,
                            'subscription_number' => $shipment->subscription?->subscription_number,
                            'packeta_packet_id' => $shipment->packeta_packet_id,
                            'packeta_status_code' => $status['statusCode'],
                            'packeta_status_text' => $status['codeText'],
                        ]);
                    }
                    
                    $stats['returned']++;
                    $subscriptionNumber = $shipment->subscription?->subscription_number ?? $shipment->subscription_id;
                    $this->line(" ⚠ Subscription #{$subscriptionNumber} - RETURNED");
                    
                } else {
                    $stats['unchanged']++;
                }

            } catch (\Exception $e) {
                $stats['errors']++;
                Log::error('Error checking Packeta status for subscription shipment', [
                    'shipment_id' => $shipment->id,
                    'subscription_id' => $shipment->subscription_id,
                    'error' => $e->getMessage(),
                ]);
            }
            
            $this->output->progressAdvance();
            
            // Small delay to avoid overwhelming the API
            usleep(100000); // 100ms
        }

        $this->output->progressFinish();

        $this->info("Shipments - Checked: {$stats['checked']}, Delivered: {$stats['delivered']}, Returned: {$stats['returned']}, Errors: {$stats['errors']}");

        return $stats;
    }

    /**
     * Bez packeta_packet_id nemá poller co se Packety zeptat, takže se taková
     * položka nikdy nedostane na 'delivered'. Hlásíme to nahlas, aby se to
     * nedalo přehlédnout na měsíce.
     */
    protected function reportUntrackable(int $count, string $label): void
    {
        if ($count === 0) {
            return;
        }

        $this->warn("⚠ {$count} {$label} bez packeta_packet_id - poller je nemůže zkontrolovat");

        Log::warning('Packeta poller: položky bez packeta_packet_id', [
            'type' => $label,
            'count' => $count,
        ]);
    }
}
