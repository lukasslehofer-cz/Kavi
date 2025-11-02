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
        'shipping_address',
        'billing_address',
        'customer_notes',
        'admin_notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'packeta_packet_id',
        'packeta_tracking_url',
        'packeta_sent_at',
        'packeta_point_id',
        'packeta_point_name',
        'packeta_point_address',
        'packeta_shipment_status',
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
        'paid_at' => 'datetime',
        'last_payment_failure_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'packeta_sent_at' => 'datetime',
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

    public static function generateOrderNumber(): string
    {
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            $count = static::whereDate('created_at', today())->count() + 1 + $attempt;
            $orderNumber = 'KV-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            
            // Check if this number already exists
            $exists = static::where('order_number', $orderNumber)->exists();
            
            if (!$exists) {
                return $orderNumber;
            }
            
            $attempt++;
        } while ($attempt < $maxAttempts);
        
        // Fallback: use microtime for uniqueness
        return 'KV-' . date('Y') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT) . '-' . substr(microtime(true) * 10000, -4);
    }
}




