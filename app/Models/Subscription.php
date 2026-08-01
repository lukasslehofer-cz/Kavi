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
        'consecutive_unpaid_shipments',
        'last_payment_failure_at',
        'last_payment_failure_reason',
        'pending_invoice_id',
        'pending_invoice_amount',
        'cancellation_reason',
        'starts_at',
        'next_billing_date',
        // KROK 9: last_shipment_date se už neukládá – je to accessor odvozený z ledgeru
        // (MAX shipment_date z sent/delivered zásilek). Viz getLastShipmentDateAttribute.
        'ends_at',
        'delivery_notes',
        'configuration',
        'configured_price',
        'vat_rate',
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
        // KROK 8: per-box Packeta tracking (packet_id, tracking_url, shipment_status, sent_at)
        // se už NEukládá na subscription – žije na subscription_shipments (ledger).
        // Čtení "posledního trackingu" jde přes accessor packeta_* → latestShipment.
        'shipping_cost',
        'gift_voucher_shipping_credit',
        'shipping_country',
        'shipping_rate_id',
        'meta_event_id',
        'meta_fbp',
        'meta_fbc',
        'meta_capi_sent_at',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'next_billing_date' => 'date',
        'ends_at' => 'date',
        'paused_until_date' => 'date',
        'meta_capi_sent_at' => 'datetime',
        'last_payment_failure_at' => 'datetime',
        'configuration' => 'array',
        'shipping_address' => 'array',
        'configured_price' => 'decimal:2',
        'payment_failure_count' => 'integer',
        'consecutive_unpaid_shipments' => 'integer',
        'vat_rate' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'pending_invoice_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'gift_voucher_shipping_credit' => 'decimal:2',
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

    /**
     * KROK 8: Poslední odeslaná/doručená zásilka – jediný zdroj "aktuálního" trackingu.
     */
    public function latestShipment()
    {
        return $this->hasOne(SubscriptionShipment::class)
            ->whereIn('status', ['sent', 'delivered'])
            ->orderByDesc('shipment_date')
            ->orderByDesc('id');
    }

    /**
     * KROK 8: "Poslední Packeta tracking předplatného" se odvozuje z ledgeru,
     * ne z (odstraněných) sloupců na subscription. Zachovává API $subscription->packeta_*
     * pro stávající čtenáře (např. e-mail o odeslání boxu).
     */
    public function getPacketaTrackingUrlAttribute(): ?string
    {
        return $this->latestShipment?->packeta_tracking_url;
    }

    public function getPacketaPacketIdAttribute(): ?string
    {
        return $this->latestShipment?->packeta_packet_id;
    }

    /**
     * KROK 9: last_shipment_date je odvozený z ledgeru = MAX(shipment_date) přes
     * odeslané/doručené zásilky. Rodičovský sloupec byl odstraněn; tímto accessorem
     * zůstává API $subscription->last_shipment_date funkční pro čtenáře (blade, cadence).
     */
    public function getLastShipmentDateAttribute(): ?\Carbon\Carbon
    {
        $max = $this->relationLoaded('shipments')
            ? $this->shipments->whereIn('status', ['sent', 'delivered'])->max('shipment_date')
            : $this->shipments()->whereIn('status', ['sent', 'delivered'])->max('shipment_date');

        return $max ? \Carbon\Carbon::parse($max) : null;
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

    public function isComplimentary(): bool
    {
        return $this->status === 'complimentary';
    }

    public function scopeComplimentary($query)
    {
        return $query->where('status', 'complimentary');
    }

    public function requiresBilling(): bool
    {
        return !$this->isComplimentary();
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
        // KROK 7: jediný zdroj pravdy = SubscriptionShipmentService (ledger-first).
        return app(\App\Services\SubscriptionShipmentService::class)
            ->calculateNextShipmentDate($this);
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
        
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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


