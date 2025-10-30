<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCoffeeAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'shipment_schedule_id',
        'coffee_product_id',
        'quantity',
        'status',
        'allocated_at',
    ];

    protected $casts = [
        'allocated_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function shipmentSchedule()
    {
        return $this->belongsTo(ShipmentSchedule::class);
    }

    public function coffeeProduct()
    {
        return $this->belongsTo(Product::class, 'coffee_product_id');
    }

    /**
     * Mark allocation as shipped
     */
    public function markAsShipped(): void
    {
        $this->update(['status' => 'shipped']);
    }

    /**
     * Mark allocation as cancelled
     */
    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}

