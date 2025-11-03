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
        $this->update([
            'status' => 'cancelled',
            'ends_at' => now(),
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
            'paused_until_date' => null,
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
     */
    public function shouldShipOn(\Carbon\Carbon $date): bool
    {
        return \App\Helpers\SubscriptionHelper::shouldShipInNextBatch($this, $date);
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


