<?php

namespace App\Helpers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Str;

/**
 * Výběr článku pro řádek „Tip z blogu“ na detailu produktu (config/blog_tips.php).
 */
class BlogTipHelper
{
    /** @var array<string, Post|null> */
    private static array $posts = [];

    /**
     * Publikovaný článek podle prvního pravidla, které na produkt sedí, jinak null.
     */
    public static function forProduct(Product $product): ?Post
    {
        $slug = self::matchingPostSlug($product);

        if ($slug === null) {
            return null;
        }

        if (! array_key_exists($slug, self::$posts)) {
            self::$posts[$slug] = Post::published()->where('slug_cs', $slug)->first();
        }

        return self::$posts[$slug];
    }

    /**
     * slug_cs článku podle prvního pravidla, které na produkt sedí.
     */
    public static function matchingPostSlug(Product $product): ?string
    {
        $categories = is_array($product->category) ? $product->category : [];

        foreach (config('blog_tips.rules', []) as $rule) {
            $matches = match ($rule['match'] ?? null) {
                'category' => in_array($rule['category'], $categories, true),
                'processing' => $product->isCoffee() && self::containsAny(self::processingText($product), $rule['keywords'] ?? []),
                'name' => in_array($rule['category'], $categories, true)
                    && self::containsAny($product->name . ' ' . $product->name_en, $rule['keywords'] ?? []),
                default => false,
            };

            if ($matches) {
                return $rule['post'];
            }
        }

        return null;
    }

    private static function processingText(Product $product): string
    {
        $attributes = is_array($product->attributes) ? $product->attributes : [];

        return ($attributes['processing'] ?? '') . ' ' . ($attributes['processing_en'] ?? '');
    }

    private static function containsAny(string $text, array $keywords): bool
    {
        return $keywords !== [] && Str::contains(mb_strtolower($text), array_map('mb_strtolower', $keywords));
    }
}
