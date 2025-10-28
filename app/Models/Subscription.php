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
        'stripe_subscription_id',
        'status',
        'starts_at',
        'next_billing_date',
        'last_shipment_date',
        'ends_at',
        'delivery_notes',
        'configuration',
        'configured_price',
        'frequency_months',
        'shipping_address',
        'payment_method',
        'packeta_point_id',
        'packeta_point_name',
        'packeta_point_address',
        'packeta_packet_id',
        'packeta_shipment_status',
        'packeta_sent_at',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'next_billing_date' => 'date',
        'last_shipment_date' => 'date',
        'ends_at' => 'date',
        'packeta_sent_at' => 'datetime',
        'configuration' => 'array',
        'shipping_address' => 'array',
        'configured_price' => 'decimal:2',
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
        $this->update(['status' => 'active']);
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
        return 'SUB-' . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }
}


