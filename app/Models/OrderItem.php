<?php

namespace App\Models;

use App\Helpers\VatHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'price',
        'quantity',
        'total',
        'vat_rate',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'vat_rate' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate VAT amount from total price and VAT rate
     */
    public function getVat(): float
    {
        return VatHelper::calculateVat($this->total, $this->vat_rate);
    }
}




