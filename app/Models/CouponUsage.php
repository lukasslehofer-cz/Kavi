<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'subscription_id',
        'usage_type',
        'discount_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        // Dárkový poukaz: stav dárku v adminu sleduje použití kódu (Uplatněno / Vystaveno)
        static::created(fn (CouponUsage $usage) => GiftPurchase::syncVoucherStatus((int) $usage->coupon_id));
        static::deleted(fn (CouponUsage $usage) => GiftPurchase::syncVoucherStatus((int) $usage->coupon_id));
    }

    /**
     * Vztah ke kupónu
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Vztah k uživateli
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vztah k objednávce
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Vztah k předplatnému
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}

