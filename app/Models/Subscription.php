<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_number',
        'user_id',
        'subscription_plan_id',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'discount_months_remaining',
        'discount_months_total',
        'stripe_subscription_id',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'status',
        'payment_failure_count',
        'last_payment_failure_at',
        'last_payment_failure_reason',
        'pending_invoice_id',
        'pending_invoice_amount',
        'starts_at',
        'next_billing_date',
        'last_shipment_date',
        'ends_at',
        'delivery_notes',
        'configuration',
        'configured_price',
        'currency',
        'frequency_months',
        'paused_iterations',
        'paused_until_date',
        'pause_reason',
        'shipping_address',
        'payment_method',
        'packeta_point_id',
        'packeta_point_name',
        'packeta_point_address',
        'carrier_id',
        'carrier_pickup_point',
        'packeta_packet_id',
        'packeta_tracking_url',
        'packeta_shipment_status',
        'packeta_sent_at',
        'shipping_cost',
        'shipping_country',
        'shipping_rate_id',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'next_billing_date' => 'date',
        'last_shipment_date' => 'date',
        'ends_at' => 'date',
        'paused_until_date' => 'date',
        'last_payment_failure_at' => 'datetime',
        'packeta_sent_at' => 'datetime',
        'configuration' => 'array',
        'shipping_address' => 'array',
        'configured_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'pending_invoice_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class)->orderBy('paid_at', 'desc');
    }

    public function shipments()
    {
        return $this->hasMany(SubscriptionShipment::class)->orderBy('shipment_date', 'desc');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPaused(): bool
    {
        return $this->status === 'paused';
    }

    public function isUnpaid(): bool
    {
        return $this->status === 'unpaid';
    }

    public function hasPaymentIssue(): bool
    {
        return $this->isUnpaid() || $this->payment_failure_count > 0;
    }

    public function cancel()
    {
        // Check if there's paid coverage for any future shipments
        $nextShipment = \App\Helpers\SubscriptionHelper::calculateNextShipmentDate($this);
        
        if ($nextShipment) {
            // Check if we have paid coverage for the next shipment
            $hasPaidCoverage = \App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($this, $nextShipment);
            
            if ($hasPaidCoverage) {
                // Find the last shipment date that has paid coverage
                $frequencyMonths = max(1, (int)($this->frequency_months ?? 1));
                $candidate = $nextShipment->copy();
                $lastPaidShipment = null;
                
                // Look ahead up to 12 months to find the last paid shipment
                $guard = 0;
                while ($guard < 12) {
                    if (\App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($this, $candidate)) {
                        $lastPaidShipment = $candidate->copy();
                    } else {
                        // Found the first unpaid shipment, stop
                        break;
                    }
                    $candidate = $candidate->copy()->addMonths($frequencyMonths);
                    $guard++;
                }
                
                // Cancel at the end of the last paid period (after last paid shipment is delivered)
                if ($lastPaidShipment) {
                    $this->update([
                        'status' => 'cancelled',
                        'ends_at' => $lastPaidShipment->copy()->endOfDay(),
                    ]);
                    
                    \Log::info('Subscription cancelled at period end', [
                        'subscription_id' => $this->id,
                        'last_paid_shipment' => $lastPaidShipment->toDateString(),
                        'ends_at' => $lastPaidShipment->toDateString(),
                    ]);
                    
                    return;
                }
            }
        }
        
        // No paid coverage found, cancel immediately
        $this->update([
            'status' => 'cancelled',
            'ends_at' => now(),
        ]);
        
        \Log::info('Subscription cancelled immediately (no paid coverage)', [
            'subscription_id' => $this->id,
            'ends_at' => now()->toDateString(),
        ]);
    }

    public function pause()
    {
        $this->update(['status' => 'paused']);
    }

    public function resume()
    {
        $this->update([
            'status' => 'active',
            'paused_iterations' => null,
            // Keep paused_until_date for next shipment calculation
            'pause_reason' => null,
        ]);
    }

    /**
     * Get the next shipment date for this subscription
     */
    public function getNextShipmentDateAttribute(): ?\Carbon\Carbon
    {
        return \App\Helpers\SubscriptionHelper::calculateNextShipmentDate($this);
    }

    /**
     * Check if this subscription should ship on the given date
     * Uses the central SubscriptionShipmentService for consistent logic
     */
    public function shouldShipOn(\Carbon\Carbon $date): bool
    {
        return app(\App\Services\SubscriptionShipmentService::class)
            ->shouldShipOn($this, $date);
    }

    /**
     * Generate unique subscription number
     */
    public static function generateSubscriptionNumber(): string
    {
        $year = date('Y');
        $prefix = 'KVS-' . $year . '-';
        
        // Get the highest number for today
        $lastSubscription = static::where('subscription_number', 'like', $prefix . '%')
            ->orderBy('subscription_number', 'desc')
            ->first();
        
        if ($lastSubscription) {
            // Extract the number from the last subscription number
            $lastNumber = (int) str_replace($prefix, '', $lastSubscription->subscription_number);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate pause end date based on iterations and subscription frequency
     */
    public function calculatePauseEndDate(int $iterations): \Carbon\Carbon
    {
        $frequencyMonths = max(1, (int)($this->frequency_months ?? 1));

        // Start pause from the first UNPAID shipment date
        $firstUnpaid = \App\Helpers\SubscriptionHelper::getFirstUnpaidShipmentDate($this);

        // Pause covers 'iterations' shipment dates, end date is the date of the last paused shipment
        $pausedUntil = $firstUnpaid->copy()->addMonths(($iterations - 1) * $frequencyMonths);

        return $pausedUntil->endOfDay();
    }

    /**
     * Pause for N iterations with reason
     */
    public function pauseFor(int $iterations, string $reason = 'user_request'): void
    {
        $endDate = $this->calculatePauseEndDate($iterations);

        $this->update([
            'status' => 'paused',
            'paused_iterations' => $iterations,
            'paused_until_date' => $endDate,
            'pause_reason' => $reason,
        ]);
    }

    /**
     * Whether the subscription can be resumed (end date has passed)
     */
    public function canResume(): bool
    {
        return $this->status === 'paused' && $this->paused_until_date && $this->paused_until_date->isPast();
    }
}


