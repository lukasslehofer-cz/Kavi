<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\SubscriptionShipment;

class LinkShipmentPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:link-shipment-payments {--force : Force re-link even if already linked} {--migrate-packeta : Migrate old Packeta data from subscriptions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link subscription shipments with their corresponding payments/invoices and optionally migrate old Packeta data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to link shipment payments...');
        
        // Get all shipments that need linking
        $query = SubscriptionShipment::with(['subscription.payments']);
        
        if (!$this->option('force')) {
            $query->whereNull('subscription_payment_id');
        }
        
        $shipments = $query->get();
        
        if ($shipments->isEmpty()) {
            $this->info('No shipments need linking.');
            return 0;
        }
        
        $this->info("Found {$shipments->count()} shipments to process.");
        
        $linked = 0;
        $skipped = 0;
        $failed = 0;
        
        $progressBar = $this->output->createProgressBar($shipments->count());
        $progressBar->start();
        
        foreach ($shipments as $shipment) {
            $payment = $this->findPaymentForShipment($shipment);
            
            if ($payment) {
                $shipment->update(['subscription_payment_id' => $payment->id]);
                $linked++;
                
                $this->newLine();
                $this->line("✓ Linked shipment #{$shipment->id} ({$shipment->shipment_date->format('Y-m-d')}) with payment #{$payment->id}");
            } else {
                $skipped++;
                
                if ($this->option('verbose')) {
                    $this->newLine();
                    $this->warn("✗ No payment found for shipment #{$shipment->id} ({$shipment->shipment_date->format('Y-m-d')})");
                }
            }
            
            // Migrate old Packeta data if requested
            if ($this->option('migrate-packeta')) {
                $this->migratePacketaData($shipment);
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info('Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['Linked', $linked],
                ['Skipped (no payment)', $skipped],
                ['Failed', $failed],
            ]
        );
        
        return 0;
    }
    
    /**
     * Find payment for given shipment
     */
    private function findPaymentForShipment(SubscriptionShipment $shipment): ?\App\Models\SubscriptionPayment
    {
        $subscription = $shipment->subscription;
        $shipmentDate = $shipment->shipment_date;
        
        // Look for payment where shipment_date falls within period_start and period_end
        // or find the most recent payment before this shipment date
        return $subscription->payments()
            ->where('status', 'paid')
            ->where(function($query) use ($shipmentDate) {
                $query->where(function($q) use ($shipmentDate) {
                    // Payment covers this date
                    $q->whereDate('period_start', '<=', $shipmentDate)
                      ->whereDate('period_end', '>=', $shipmentDate);
                })->orWhere(function($q) use ($shipmentDate) {
                    // Or find most recent payment before this date
                    $q->whereDate('paid_at', '<=', $shipmentDate);
                });
            })
            ->orderBy('paid_at', 'desc')
            ->first();
    }
    
    /**
     * Migrate old Packeta data from subscription to shipment
     */
    private function migratePacketaData(SubscriptionShipment $shipment): void
    {
        $subscription = $shipment->subscription;
        
        // Check if subscription has old Packeta data
        if (!$subscription->packeta_packet_id) {
            return;
        }
        
        // Check if this shipment was sent on or before last_shipment_date
        // This helps identify which shipment the old data belongs to
        if ($subscription->last_shipment_date && 
            $shipment->shipment_date->lte($subscription->last_shipment_date)) {
            
            // Only migrate if shipment doesn't already have Packeta data
            if (!$shipment->packeta_packet_id) {
                $updateData = [
                    'packeta_packet_id' => $subscription->packeta_packet_id,
                    'packeta_tracking_url' => $subscription->packeta_tracking_url,
                ];
                
                // Set status based on old packeta_shipment_status
                if ($subscription->packeta_shipment_status === 'sent') {
                    $updateData['status'] = 'sent';
                    $updateData['sent_at'] = $subscription->packeta_sent_at ?? $subscription->updated_at;
                }
                
                $shipment->update($updateData);
                
                $this->newLine();
                $this->info("  ↳ Migrated Packeta data: {$subscription->packeta_packet_id}");
            }
        }
    }
}
