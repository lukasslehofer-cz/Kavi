<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subscription_id',
        'shipment_schedule_id',
        'shipped_with_subscription',
        'subscription_addon_slots_used',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'subtotal',
        'shipping',
        'shipping_rate_id',
        'shipping_country',
        'tax',
        'total',
        'currency',
        'status',
        'payment_status',
        'payment_failure_count',
        'last_payment_failure_at',
        'last_payment_failure_reason',
        'payment_method',
        'stripe_payment_intent_id',
        'pending_payment_intent_id',
        'fakturoid_invoice_id',
        'invoice_pdf_path',
        'digital_delivery_pdf_path',
        'shipping_address',
        'billing_address',
        'customer_notes',
        'admin_notes',
        'paid_at',
        'confirmation_email_sent_at',
        'payment_reminder_sent_at',
        'shipped_at',
        'delivered_at',
        'packeta_packet_id',
        'packeta_tracking_url',
        'packeta_sent_at',
        'packeta_point_id',
        'packeta_point_name',
        'packeta_point_address',
        'packeta_shipment_status',
        'package_weight',
        'package_length',
        'package_width',
        'package_height',
        'meta_event_id',
        'meta_fbp',
        'meta_fbc',
        'meta_capi_sent_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'shipped_with_subscription' => 'boolean',
        'meta_capi_sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'confirmation_email_sent_at' => 'datetime',
        'payment_reminder_sent_at' => 'datetime',
        'last_payment_failure_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'packeta_sent_at' => 'datetime',
        'package_weight' => 'decimal:2',
        'package_length' => 'decimal:2',
        'package_width' => 'decimal:2',
        'package_height' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function shippingRate()
    {
        return $this->belongsTo(ShippingRate::class);
    }

    public function shipmentSchedule()
    {
        return $this->belongsTo(ShipmentSchedule::class);
    }

    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function markAsSubmitted()
    {
        $this->update([
            'status' => 'submitted',
            'packeta_shipment_status' => 'submitted',
        ]);
    }

    public function markAsShipped()
    {
        $this->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }

    public function hasPaymentIssue(): bool
    {
        return $this->isUnpaid() || $this->payment_failure_count > 0;
    }

    public function containsDigitalProducts(): bool
    {
        return $this->items->contains(fn ($item) => $item->product?->is_digital);
    }

    public function containsOnlyDigitalProducts(): bool
    {
        return $this->items->isNotEmpty()
            && $this->items->every(fn ($item) => $item->product?->is_digital);
    }

    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $prefix = 'KV-' . $year . '-';
        
        // Get the highest number for this year
        $lastOrder = static::where('order_number', 'like', $prefix . '%')
            ->where('order_number', 'not like', $prefix . '%-____') // Exclude old fallback numbers
            ->orderByRaw('CAST(SUBSTRING(order_number, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();
        
        if ($lastOrder) {
            $lastNumber = (int) str_replace($prefix, '', $lastOrder->order_number);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get package dimensions as array for Packeta
     * Returns custom values if set, otherwise calculates from items
     */
    public function getPackageDimensions(): array
    {
        // If custom dimensions are set, use them
        if ($this->package_length && $this->package_width && $this->package_height) {
            return [
                'length' => (float) $this->package_length,
                'width' => (float) $this->package_width,
                'height' => (float) $this->package_height,
            ];
        }

        // Otherwise calculate from order items (current logic)
        $totalQuantity = $this->items->sum('quantity');

        $sizeCategory = match(true) {
            $totalQuantity <= 2 => 2,
            $totalQuantity == 3 => 3,
            default => 4,
        };

        return [
            'length' => \App\Models\SubscriptionConfig::get("package_{$sizeCategory}_length", 30),
            'width' => \App\Models\SubscriptionConfig::get("package_{$sizeCategory}_width", 20),
            'height' => \App\Models\SubscriptionConfig::get("package_{$sizeCategory}_height", 10),
        ];
    }

    /**
     * Get package weight
     * Returns custom value if set, otherwise calculates from items
     */
    public function getPackageWeight(): float
    {
        if ($this->package_weight) {
            return (float) $this->package_weight;
        }

        // Calculate from items (0.25 kg per item)
        $weight = $this->items->sum('quantity') * 0.25;
        return max($weight, 0.1); // Minimum 0.1 kg
    }

    /**
     * Check if order package dimensions can be edited (not sent to Packeta yet)
     */
    public function canEditPackageDimensions(): bool
    {
        return $this->packeta_shipment_status !== 'submitted';
    }
}




