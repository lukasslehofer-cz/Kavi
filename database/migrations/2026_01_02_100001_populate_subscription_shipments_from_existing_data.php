<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Models\SubscriptionShipment;
use App\Models\ShipmentSchedule;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Populate subscription_shipments table from existing subscription data
     */
    public function up(): void
    {
        $subscriptions = Subscription::with(['payments' => function($q) {
            $q->where('status', 'paid')->orderBy('paid_at', 'asc');
        }])->get();

        foreach ($subscriptions as $subscription) {
            // 1. Create "sent" records for past shipments based on last_shipment_date
            if ($subscription->last_shipment_date) {
                $frequencyMonths = max(1, (int)($subscription->frequency_months ?? 1));
                
                // Work backwards from last_shipment_date to find all past shipments
                $candidate = $subscription->last_shipment_date->copy();
                $shipmentDates = collect([$candidate->copy()]);
                
                // Go back up to 12 shipments to capture history
                $guard = 0;
                while ($guard < 12) {
                    $prev = $candidate->copy()->subMonths($frequencyMonths);
                    
                    // Stop if we've gone before the subscription start
                    if ($subscription->starts_at && $prev->lt($subscription->starts_at)) {
                        break;
                    }
                    
                    // Stop if we've gone before the subscription creation
                    if ($prev->lt($subscription->created_at->subMonths(1))) {
                        break;
                    }
                    
                    $shipmentDates->push($prev->copy());
                    $candidate = $prev;
                    $guard++;
                }

                // Create "sent" shipment records for each historical shipment
                foreach ($shipmentDates as $shipmentDate) {
                    // Find matching schedule
                    $schedule = ShipmentSchedule::getForMonth($shipmentDate->year, $shipmentDate->month);
                    $actualShipDate = $schedule?->shipment_date ?? $shipmentDate->copy()->day(20);
                    
                    // Find payment that covers this shipment
                    $payment = $subscription->payments
                        ->filter(function($p) use ($actualShipDate) {
                            if (!$p->period_start || !$p->period_end) {
                                return false;
                            }
                            return $actualShipDate->between($p->period_start, $p->period_end);
                        })
                        ->first();
                    
                    // For first shipment, use first payment
                    if (!$payment && $shipmentDates->last()->eq($shipmentDate)) {
                        $payment = $subscription->payments->first();
                    }

                    // Check if record already exists
                    $exists = SubscriptionShipment::where('subscription_id', $subscription->id)
                        ->whereDate('shipment_date', $actualShipDate->toDateString())
                        ->exists();

                    if (!$exists) {
                        SubscriptionShipment::create([
                            'subscription_id' => $subscription->id,
                            'shipment_schedule_id' => $schedule?->id,
                            'shipment_date' => $actualShipDate,
                            'subscription_payment_id' => $payment?->id,
                            'status' => 'sent',
                            'sent_at' => $actualShipDate, // Approximate
                            'packeta_packet_id' => $shipmentDate->eq($subscription->last_shipment_date) 
                                ? $subscription->packeta_packet_id 
                                : null,
                            'packeta_tracking_url' => $shipmentDate->eq($subscription->last_shipment_date)
                                ? $subscription->packeta_tracking_url
                                : null,
                            'notes' => 'Migrated from existing data',
                        ]);
                    }
                }
            }

            // 2. Create records for next shipment (active/paused subscriptions)
            if (in_array($subscription->status, ['active', 'paused'])) {
                // For paused subscriptions, handle skipped shipments and create pending after pause
                if ($subscription->status === 'paused' && $subscription->paused_until_date) {
                    $this->createShipmentsForPausedSubscription($subscription);
                } else {
                    // Active subscription - create pending for next shipment
                    $nextShipmentDate = $subscription->next_shipment_date;
                    
                    if ($nextShipmentDate) {
                        $schedule = ShipmentSchedule::getForMonth($nextShipmentDate->year, $nextShipmentDate->month);
                        
                        $exists = SubscriptionShipment::where('subscription_id', $subscription->id)
                            ->whereDate('shipment_date', $nextShipmentDate->toDateString())
                            ->exists();

                        if (!$exists) {
                            SubscriptionShipment::create([
                                'subscription_id' => $subscription->id,
                                'shipment_schedule_id' => $schedule?->id,
                                'shipment_date' => $nextShipmentDate,
                                'status' => 'pending',
                                'notes' => 'Created during migration',
                            ]);
                        }
                    }
                }
            }
            
            // 3. For one-time boxes without shipment, create pending
            if ($subscription->frequency_months == 0 
                && !$subscription->last_shipment_date 
                && in_array($subscription->status, ['active', 'pending'])) {
                
                $createdAt = $subscription->starts_at ?? $subscription->created_at;
                $schedule = ShipmentSchedule::getForMonth($createdAt->year, $createdAt->month);
                
                // If created after cutoff, ship next month
                $shipDate = $schedule?->shipment_date;
                if (!$shipDate || $createdAt->day > 15) {
                    $nextMonth = $createdAt->copy()->addMonthNoOverflow();
                    $nextSchedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
                    $shipDate = $nextSchedule?->shipment_date ?? $nextMonth->day(20);
                }
                
                $exists = SubscriptionShipment::where('subscription_id', $subscription->id)
                    ->where('status', 'pending')
                    ->exists();

                if (!$exists) {
                    SubscriptionShipment::create([
                        'subscription_id' => $subscription->id,
                        'shipment_schedule_id' => $schedule?->id,
                        'shipment_date' => $shipDate,
                        'subscription_payment_id' => $subscription->payments->first()?->id,
                        'status' => 'pending',
                        'notes' => 'One-time box - created during migration',
                    ]);
                }
            }
        }
        
        \Log::info('Migration completed: subscription_shipments populated from existing data', [
            'total_subscriptions' => $subscriptions->count(),
            'total_shipments_created' => SubscriptionShipment::count(),
        ]);
    }

    /**
     * Create shipment records for paused subscriptions
     * - Creates 'skipped' records for shipments during pause period
     * - Creates 'pending' record for first shipment after pause ends
     * 
     * IMPORTANT: Logic is based on BILLING_DATE (15th), not shipment_date (20th)
     * A month is skipped if billing_date < paused_until_date
     */
    private function createShipmentsForPausedSubscription(Subscription $subscription): void
    {
        $pauseEndDate = $subscription->paused_until_date;
        $frequencyMonths = max(1, (int)($subscription->frequency_months ?? 1));
        $lastShipment = $subscription->last_shipment_date ?? $subscription->starts_at ?? $subscription->created_at;
        
        // Calculate all shipment dates from last shipment until after pause ends
        $candidate = $lastShipment->copy();
        
        // Go forward from last shipment, creating skipped/pending records
        for ($i = 0; $i < 24; $i++) { // Max 24 iterations (2 years) as safety
            $candidate = $candidate->copy()->addMonths($frequencyMonths);
            
            // Find the actual shipment and billing dates for this month
            $schedule = ShipmentSchedule::getForMonth($candidate->year, $candidate->month);
            $shipDate = $schedule?->shipment_date ?? $candidate->copy()->day(20);
            $billingDate = ShipmentSchedule::getBillingDateForMonth($candidate->year, $candidate->month);
            
            // Check if this shipment already exists
            $exists = SubscriptionShipment::where('subscription_id', $subscription->id)
                ->whereDate('shipment_date', $shipDate->toDateString())
                ->exists();
            
            if ($exists) {
                continue;
            }
            
            // Determine status based on BILLING DATE vs pause end date
            // Month is skipped if billing_date < paused_until_date
            if ($billingDate->lt($pauseEndDate)) {
                // Billing is before pause ends - month is skipped
                SubscriptionShipment::create([
                    'subscription_id' => $subscription->id,
                    'shipment_schedule_id' => $schedule?->id,
                    'shipment_date' => $shipDate,
                    'status' => 'skipped',
                    'notes' => 'Skipped due to pause - billing date before pause end (migration)',
                ]);
            } else {
                // First month where billing_date >= pause_end - mark as pending and stop
                SubscriptionShipment::create([
                    'subscription_id' => $subscription->id,
                    'shipment_schedule_id' => $schedule?->id,
                    'shipment_date' => $shipDate,
                    'status' => 'pending',
                    'notes' => 'First shipment after pause - billing date after pause end (migration)',
                ]);
                break; // Only create one pending record
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete only records created during migration
        SubscriptionShipment::where('notes', 'like', '%migration%')->delete();
    }
};

