<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReservation extends Model
{
    use HasFactory;

    public $timestamps = false; // Using only updated_at

    protected $fillable = [
        'product_id',
        'shipment_schedule_id',
        'reserved_quantity',
        'actual_quantity',
        'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shipmentSchedule()
    {
        return $this->belongsTo(ShipmentSchedule::class);
    }

    /**
     * Check if there's enough stock available
     */
    public function hasEnoughStock(): bool
    {
        $product = $this->product;
        if (!$product) {
            return false;
        }

        return $product->stock >= $this->actual_quantity;
    }

    /**
     * Get remaining stock after reservations
     */
    public function getRemainingStock(): int
    {
        $product = $this->product;
        if (!$product) {
            return 0;
        }

        return $product->stock - $this->actual_quantity;
    }
}

