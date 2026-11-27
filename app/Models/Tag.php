<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasLocalizedColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, HasLocalizedColumns;

    protected $fillable = [
        'name_cs',
        'name_en',
        'name_sk',
        'slug_cs',
        'slug_en',
        'slug_sk',
    ];

    /**
     * Get translated name based on current locale
     */
    public function getName(): string
    {
        return $this->localizedValue('name', null, 'name_cs');
    }

    /**
     * Get slug based on current locale
     */
    public function getSlug(): string
    {
        return $this->localizedValue('slug', null, 'slug_cs');
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
        
        // Slug kteréhokoli jazyka (přednost jazyka domény řeší findBySlug / BlogController)
        return $this->where(function ($query) use ($value, $locale) {
            foreach (self::slugColumns($locale) as $column) {
                $query->orWhere($column, $value);
            }
        })->first();
    }

    /**
     * Generate a unique slug from the name for a specific locale
     */
    public static function generateSlug(string $name, string $locale = 'cs', ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        $column = self::slugColumns($locale)[0];

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

    /**
     * Sloupce slugů všech jazyků webu, jazyk $locale první (slug_cs, slug_en, slug_sk).
     *
     * @return list<string>
     */
    public static function slugColumns(?string $locale = null): array
    {
        $locales = \App\Helpers\Site::locales();
        $locale = in_array($locale, $locales, true) ? $locale : \App\Helpers\Site::PRIMARY_LOCALE;

        return array_values(array_unique(array_map(fn ($l) => 'slug_' . $l, [$locale, ...$locales])));
    }
}
