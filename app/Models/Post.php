<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

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
        $content = (app()->getLocale() === 'en' && !empty($this->content_en))
            ? $this->content_en
            : ($this->content_cs ?? '');

        // Sanitize admin-authored HTML to strip any <script>, event handlers,
        // javascript: URLs etc. while keeping normal blog formatting (stored XSS).
        return Purify::clean($content);
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
     * Ručně přiřazené související články v nastaveném pořadí
     */
    public function relatedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_related_posts', 'post_id', 'related_post_id')
            ->withPivot('position')
            ->withTimestamps()
            ->orderBy('post_related_posts.position');
    }

    /**
     * Uloží související články v daném pořadí (max. 3, bez duplicit a bez sebe sama)
     */
    public function syncRelatedPosts(array $ids): void
    {
        $ids = array_values(array_unique(array_filter(
            array_map('intval', $ids),
            fn (int $id) => $id > 0 && $id !== $this->id
        )));

        $sync = [];
        foreach (array_slice($ids, 0, 3) as $index => $id) {
            $sync[$id] = ['position' => $index + 1];
        }

        $this->relatedPosts()->sync($sync);
    }

    /**
     * Staré slugy, ze kterých se přesměrovává na aktuální
     */
    public function slugRedirects()
    {
        return $this->hasMany(PostSlugRedirect::class);
    }

    /**
     * Najde článek podle aktuálního slugu (přednost má slug jazyka domény)
     */
    public static function findBySlug(string $slug): ?self
    {
        [$first, $second] = app()->getLocale() === 'en' ? ['slug_en', 'slug_cs'] : ['slug_cs', 'slug_en'];

        return self::where($first, $slug)->first() ?? self::where($second, $slug)->first();
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
