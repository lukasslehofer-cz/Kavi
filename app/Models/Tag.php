<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_cs',
        'name_en',
        'slug_cs',
        'slug_en',
    ];

    /**
     * Get translated name based on current locale
     */
    public function getName(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $this->name_cs;
    }

    /**
     * Get slug based on current locale
     */
    public function getSlug(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->slug_en)) {
            return $this->slug_en;
        }
        return $this->slug_cs;
    }

    /**
     * Relationship with posts
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * Resolve the route binding for the model (locale-aware)
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // If value is numeric, find by ID (for admin routes)
        if (is_numeric($value)) {
            return $this->where('id', $value)->first();
        }
        
        $locale = app()->getLocale();
        
        // Try locale-specific slug first, then fallback to other locale
        if ($locale === 'en') {
            return $this->where('slug_en', $value)
                        ->orWhere('slug_cs', $value)
                        ->first();
        }
        
        return $this->where('slug_cs', $value)
                    ->orWhere('slug_en', $value)
                    ->first();
    }

    /**
     * Generate a unique slug from the name for a specific locale
     */
    public static function generateSlug(string $name, string $locale = 'cs', ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        $column = $locale === 'en' ? 'slug_en' : 'slug_cs';

        $query = self::where($column, $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            $query = self::where($column, $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Get posts count
     */
    public function getPublishedPostsCount(): int
    {
        return $this->posts()->published()->count();
    }
}
