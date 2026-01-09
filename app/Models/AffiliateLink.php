<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AffiliateLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_partner_id',
        'coupon_id',
        'slug',
        'clicks_count',
        'is_active',
    ];

    protected $casts = [
        'clicks_count' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Vztah k affiliate partnerovi
     */
    public function affiliatePartner()
    {
        return $this->belongsTo(User::class, 'affiliate_partner_id');
    }

    /**
     * Vztah ke kupónu
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Vztah ke kliknutím
     */
    public function clicks()
    {
        return $this->hasMany(AffiliateLinkClick::class);
    }

    /**
     * Scope pro aktivní linky
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Vygeneruje unikátní slug pro link
     */
    public static function generateUniqueSlug(string $baseSlug = null): string
    {
        if (!$baseSlug) {
            $baseSlug = Str::random(8);
        }

        $slug = Str::slug($baseSlug);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . Str::random(4);
            $counter++;
            
            // Bezpečnostní limit
            if ($counter > 100) {
                $slug = Str::random(12);
                break;
            }
        }

        return $slug;
    }

    /**
     * Získá plnou URL linku
     */
    public function getFullUrl(): string
    {
        return url('/r/' . $this->slug);
    }

    /**
     * Inkrementuje počítadlo kliknutí
     */
    public function incrementClicks(): void
    {
        $this->increment('clicks_count');
    }
}
