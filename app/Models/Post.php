<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_cs',
        'title_en',
        'slug_cs',
        'slug_en',
        'perex_cs',
        'perex_en',
        'content_cs',
        'content_en',
        'featured_image',
        'author',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get translated title based on current locale
     */
    public function getTitle(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->title_en)) {
            return $this->title_en;
        }
        return $this->title_cs;
    }

    /**
     * Get translated perex based on current locale
     */
    public function getPerex(): ?string
    {
        if (app()->getLocale() === 'en' && !empty($this->perex_en)) {
            return $this->perex_en;
        }
        return $this->perex_cs;
    }

    /**
     * Get translated content based on current locale
     */
    public function getContent(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->content_en)) {
            return $this->content_en;
        }
        return $this->content_cs ?? '';
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
     * Relationship with tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Scope for published posts only
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Scope to order by newest first
     */
    public function scopeOrderByNewest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope to filter by tag (checks both slug_cs and slug_en)
     */
    public function scopeWithTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug_cs', $tagSlug)
              ->orWhere('slug_en', $tagSlug);
        });
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
     * Generate a unique slug from the title for a specific locale
     */
    public static function generateSlug(string $title, string $locale = 'cs', ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
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
     * Check if post is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' 
            && $this->published_at !== null 
            && $this->published_at->lte(now());
    }

    /**
     * Get formatted published date
     */
    public function getFormattedDate(): string
    {
        if (!$this->published_at) {
            return '';
        }

        if (app()->getLocale() === 'en') {
            return $this->published_at->format('F j, Y');
        }
        
        return $this->published_at->format('j. n. Y');
    }
}
