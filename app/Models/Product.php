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
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_coffee_of_month' => 'boolean',
        'coffee_of_month_date' => 'date',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
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
        return $query->where('category', $category);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}




