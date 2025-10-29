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
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'subtotal',
        'shipping',
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
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
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

    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
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
        return 'KV-' . date('Y') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }
}




