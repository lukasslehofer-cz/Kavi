<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'shipment_schedule_id',
        'shipment_date',
        'packeta_packet_id',
        'packeta_tracking_url',
        'carrier_id',
        'carrier_pickup_point',
        'package_weight',
        'package_length',
        'package_width',
        'package_height',
        'subscription_payment_id',
        'status',
        'notes',
        'sent_at',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'package_weight' => 'decimal:2',
        'package_length' => 'integer',
        'package_width' => 'integer',
        'package_height' => 'integer',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the subscription that owns this shipment
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the payment/invoice associated with this shipment
     */
    public function payment()
    {
        return $this->belongsTo(\App\Models\SubscriptionPayment::class, 'subscription_payment_id');
    }

    /**
     * Get the shipment schedule for this shipment
     */
    public function schedule()
    {
        return $this->belongsTo(\App\Models\ShipmentSchedule::class, 'shipment_schedule_id');
    }

    /**
     * Check if shipment was skipped (paused)
     */
    public function isSkipped(): bool
    {
        return $this->status === 'skipped';
    }

    /**
     * Check if shipment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if shipment was sent
     */
    public function isSent(): bool
    {
        return in_array($this->status, ['sent', 'delivered']);
    }

    /**
     * Mark shipment as sent
     */
    public function markAsSent(string $packetId, string $trackingUrl): void
    {
        $this->update([
            'packeta_packet_id' => $packetId,
            'packeta_tracking_url' => $trackingUrl,
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Get package dimensions as array for Packeta
     */
    public function getPackageDimensions(): array
    {
        return [
            'length' => $this->package_length,
            'width' => $this->package_width,
            'height' => $this->package_height,
        ];
    }

    /**
     * Scope to get pending shipments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get sent shipments
     */
    public function scopeSent($query)
    {
        return $query->whereIn('status', ['sent', 'delivered']);
    }

    /**
     * Scope to get shipments for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('shipment_date', $date);
    }

    /**
     * Scope to get skipped shipments
     */
    public function scopeSkipped($query)
    {
        return $query->where('status', 'skipped');
    }

    /**
     * Scope to get cancelled shipments
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => __('shipments.status.pending'),
            'sent' => __('shipments.status.sent'),
            'delivered' => __('shipments.status.delivered'),
            'skipped' => __('shipments.status.skipped'),
            'cancelled' => __('shipments.status.cancelled'),
            default => $this->status,
        };
    }
}
