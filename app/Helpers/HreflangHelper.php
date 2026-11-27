<?php

namespace App\Helpers;

use App\Models\Post;
use App\Models\Product;
use App\Models\Roastery;
use Illuminate\Support\Facades\Route;

/**
 * Jazykové protějšky stránek kavi.cz (cs) ↔ kavibox.com (en).
 *
 * Oba weby běží z jedné aplikace a databáze, takže existence protějšku se ověřuje
 * přímo v DB. Používá se pro <link rel="alternate" hreflang> a přepínač jazyka.
 */
class HreflangHelper
{
    public const LOCALES = ['cs', 'en'];

    /**
     * Stránky, které mají protějšek vždy (základní název routy bez přípony .en).
     */
    private const STATIC_ROUTES = [
        'home',
        'subscriptions.index',
        'products.index',
        'roasteries.index',
        'monthly-feature.index',
        'how-it-works',
        'about',
        'contact',
        'blog.index',
        'terms-of-service',
        'privacy-policy',
    ];

    /**
     * Sledovací parametry, které na hreflang nemají vliv (canonical je stejně bez nich).
     */
    private const TRACKING_PARAMS = ['gclid', 'fbclid', 'srsltid', 'msclkid'];

    /** @var array<string, array<string, string>|null> */
    private static array $memo = [];

    /**
     * hreflang značky aktuální stránky: ['cs' => url, 'en' => url], nebo [] když se nemají vložit
     * (stránka bez protějšku nebo s parametry jako ?page=, ?category=, ?plan=, ?type=).
     *
     * @return array<string, string>
     */
    public static function alternates(): array
    {
        if (! request()->isMethod('GET') || self::hasContentQuery()) {
            return [];
        }

        return self::counterparts() ?? [];
    }

    /**
     * Cíl přepínače jazyka: protějšek aktuální stránky (bez parametrů), jinak homepage druhého webu.
     */
    public static function switchUrl(): string
    {
        $other = self::otherLocale();

        return self::counterparts()[$other] ?? self::siteUrl($other);
    }

    public static function otherLocale(): string
    {
        return app()->getLocale() === 'en' ? 'cs' : 'en';
    }

    public static function siteUrl(string $locale): string
    {
        return rtrim((string) config('app.site_urls.' . $locale), '/');
    }

    /**
     * Adresy aktuální stránky v obou jazycích, nebo null když protějšek neexistuje.
     *
     * @return array<string, string>|null
     */
    public static function counterparts(): ?array
    {
        $route = Route::current();
        $key = $route ? spl_object_id($route) . ':' . app()->getLocale() : '';

        if (! array_key_exists($key, self::$memo)) {
            self::$memo[$key] = $route ? self::resolve($route) : null;
        }

        return self::$memo[$key];
    }

    /**
     * @return array<string, string>|null
     */
    private static function resolve(\Illuminate\Routing\Route $route): ?array
    {
        $name = $route->getName();
        if (! $name) {
            return null;
        }

        $locale = app()->getLocale();
        if (! in_array($locale, self::LOCALES, true)) {
            return null;
        }

        $base = preg_replace('/\.en$/', '', $name);

        // Routa druhého jazyka na této doméně (LocalizedUrlRedirect by ji měl přesměrovat)
        $routeLocale = str_ends_with($name, '.en') ? 'en' : 'cs';
        $hasEnVariant = Route::has($base . '.en');
        if ($hasEnVariant && $routeLocale !== $locale) {
            return null;
        }

        if (in_array($base, self::STATIC_ROUTES, true)) {
            return self::build($base, []);
        }

        if ($base === 'gifts.index') {
            return GiftHelper::enabled() ? self::build($base, []) : null;
        }

        if ($base === 'products.show') {
            $product = $route->parameter('product');
            if (! $product instanceof Product
                || $product->is_coffee_of_month
                || ! ($product->price > 0)
                || ! ($product->price_eur > 0)) {
                return null;
            }

            return self::build($base, ['product' => $product->slug]);
        }

        if ($base === 'roasteries.show') {
            $roastery = $route->parameter('roastery');
            if (! $roastery instanceof Roastery || ! $roastery->is_active) {
                return null;
            }

            return self::build($base, ['roastery' => $roastery->slug]);
        }

        if ($base === 'blog.show') {
            return self::blogPost((string) $route->parameter('post'), $locale);
        }

        return null;
    }

    /**
     * Článek má protějšek, když je publikovaný a má vyplněnou anglickou verzi.
     *
     * @return array<string, string>|null
     */
    private static function blogPost(string $slug, string $locale): ?array
    {
        if ($slug === '' || ctype_digit($slug)) {
            return null;
        }

        $post = Post::findBySlug($slug);
        if (! $post || ! $post->isPublished()) {
            return null;
        }

        if (blank($post->slug_cs) || blank($post->slug_en) || blank($post->title_en) || blank($post->content_en)) {
            return null;
        }

        // Slug druhého jazyka (BlogController ho přesměruje na slug této domény)
        $ownSlug = $locale === 'en' ? $post->slug_en : $post->slug_cs;
        if ($slug !== $ownSlug) {
            return null;
        }

        return [
            'cs' => self::siteUrl('cs') . '/' . ltrim(route('blog.show', ['post' => $post->slug_cs], false), '/'),
            'en' => self::siteUrl('en') . '/' . ltrim(route('blog.show', ['post' => $post->slug_en], false), '/'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function build(string $base, array $parameters): array
    {
        $urls = [];

        foreach (self::LOCALES as $locale) {
            $name = ($locale === 'cs' || ! Route::has($base . '.en')) ? $base : $base . '.en';
            $path = trim(route($name, $parameters, false), '/');
            $urls[$locale] = self::siteUrl($locale) . ($path === '' ? '' : '/' . $path);
        }

        return $urls;
    }

    /**
     * Má request jiné než sledovací parametry?
     */
    private static function hasContentQuery(): bool
    {
        foreach (array_keys(request()->query()) as $param) {
            if (str_starts_with((string) $param, 'utm_') || in_array($param, self::TRACKING_PARAMS, true)) {
                continue;
            }

            return true;
        }

        return false;
    }
}
