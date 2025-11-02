<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacketaCarrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrier_id',
        'name',
        'country_code',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope: Only active carriers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: For specific country
     */
    public function scopeForCountry($query, string $countryCode)
    {
        return $query->where('country_code', strtoupper($countryCode));
    }

    /**
     * Scope: Sorted (by country_code, then alphabetically by name)
     */
    public function scopeSorted($query)
    {
        return $query->orderBy('country_code')->orderBy('name');
    }

    /**
     * Get active carriers for a specific country, sorted
     */
    public static function getForCountry(string $countryCode): array
    {
        return self::active()
            ->forCountry($countryCode)
            ->sorted()
            ->get()
            ->map(fn($carrier) => [
                'id' => $carrier->carrier_id,
                'name' => $carrier->name,
                'country' => $carrier->country_code,
            ])
            ->toArray();
    }

    /**
     * Get all active carriers (for all countries), sorted by country code then name
     * This allows admins to choose any carrier for any country
     */
    public static function getAllActive(): array
    {
        return self::active()
            ->sorted()
            ->get()
            ->map(fn($carrier) => [
                'id' => $carrier->carrier_id,
                'name' => $carrier->name,
                'country' => $carrier->country_code,
            ])
            ->toArray();
    }
}
