<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'stock',
        'image',
        'images',
        'category',
        'roastery_id',
        'attributes',
        'is_active',
        'is_featured',
        'is_coffee_of_month',
        'coffee_of_month_date',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'images' => 'array',
        'attributes' => 'array',
        'category' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_coffee_of_month' => 'boolean',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function roastery()
    {
        return $this->belongsTo(Roastery::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForShop($query)
    {
        // Pro eshop - aktivní produkty, které NEJSOU kávy měsíce
        return $query->where('is_active', true)
                     ->where('is_coffee_of_month', false);
    }

    public function scopeCoffeeOfMonth($query)
    {
        return $query->where('is_coffee_of_month', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->whereJsonContains('category', $category);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get coffees of the month based on current date
     * Logic: Show coffees for next month starting from 16th of current month
     */
    public static function getCoffeesOfMonth()
    {
        $today = now();
        $currentDay = $today->day;
        
        // If today is 16th or later, show next month's coffees
        if ($currentDay >= 16) {
            $targetMonth = $today->copy()->addMonth()->format('Y-m');
        } else {
            $targetMonth = $today->format('Y-m');
        }
        
        return self::with('roastery')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', $targetMonth)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Scope to get products by coffee_of_month_date (format: Y-m)
     */
    public function scopeByMonthDate($query, $month)
    {
        return $query->where('coffee_of_month_date', $month);
    }
}




