<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Jedna změna konfigurace předplatného - audit i naplánovaná změna, která teprve čeká
 * na odeslání už zaplaceného boxu. Viz SubscriptionConfigurationService.
 */
class SubscriptionConfigurationChange extends Model
{
    /** Čeká, až odejde zaplacený box */
    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_APPLIED = 'applied';

    /** Zrušil ji zákazník, nebo předplatné mezitím skončilo */
    public const STATUS_CANCELLED = 'cancelled';

    /** Nahradila ji novější žádost dřív, než se stihla použít */
    public const STATUS_SUPERSEDED = 'superseded';

    public const SOURCE_CUSTOMER = 'customer';

    public const SOURCE_ADMIN = 'admin';

    public const SOURCE_SYSTEM = 'system';

    protected $fillable = [
        'subscription_id',
        'requested_by_user_id',
        'source',
        'status',
        'cancel_reason',
        'old_configuration',
        'new_configuration',
        'old_frequency_months',
        'new_frequency_months',
        'old_configured_price',
        'new_configured_price',
        'old_discount_amount',
        'new_discount_amount',
        'effective_shipment_date',
        'old_next_shipment_date',
        'new_next_shipment_date',
        'old_next_billing_date',
        'new_next_billing_date',
        'applied_at',
        'cancelled_at',
    ];

    protected $casts = [
        'old_configuration' => 'array',
        'new_configuration' => 'array',
        'old_frequency_months' => 'integer',
        'new_frequency_months' => 'integer',
        'old_configured_price' => 'decimal:2',
        'new_configured_price' => 'decimal:2',
        'old_discount_amount' => 'decimal:2',
        'new_discount_amount' => 'decimal:2',
        'effective_shipment_date' => 'date',
        'old_next_shipment_date' => 'date',
        'new_next_shipment_date' => 'date',
        'old_next_billing_date' => 'date',
        'new_next_billing_date' => 'date',
        'applied_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    /**
     * Cena boxu po změně. NULL ve sloupci znamená "beze změny", takže platí ta původní.
     */
    public function resultingPrice(): ?float
    {
        $price = $this->new_configured_price ?? $this->old_configured_price;

        return $price === null ? null : (float) $price;
    }

    public function changesPrice(): bool
    {
        return $this->new_configured_price !== null
            && (float) $this->new_configured_price !== (float) $this->old_configured_price;
    }

    public function changesFrequency(): bool
    {
        return (int) $this->new_frequency_months !== (int) $this->old_frequency_months;
    }
}
