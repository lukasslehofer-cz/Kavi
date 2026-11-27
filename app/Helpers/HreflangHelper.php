<?php

namespace App\Helpers;

use App\Models\Post;
use App\Models\Product;
use App\Models\Roastery;
use Illuminate\Support\Facades\Route;

/**
 * Jazykové protějšky stránek mezi weby z config/sites.php (kavi.cz, kavibox.com, kavibox.sk).
 *
 * Všechny weby běží z jedné aplikace a databáze, takže existence protějšku se ověřuje
 * přímo v DB. Používá se pro <link rel="alternate" hreflang> a přepínač jazyka.
 * Počítá se jen se zapnutými weby (Site::enabled()).
 */
class HreflangHelper
{

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
     * hreflang značky aktuální stránky: ['cs' => url, 'en' => url, …], nebo [] když se nemají vložit
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
     * Cíle přepínače jazyka pro ostatní zapnuté weby: protějšek aktuální stránky
     * (bez parametrů), jinak homepage daného webu.
     *
     * @return array<string, string> locale => url
     */
    public static function switchUrls(): array
    {
        $current = app()->getLocale();
        $counterparts = self::counterparts() ?? [];
        $urls = [];

        foreach (self::locales() as $locale) {
            if ($locale !== $current) {
                $urls[$locale] = $counterparts[$locale] ?? self::siteUrl($locale);
            }
        }

        return $urls;
    }

    /**
     * Zapnuté jazykové weby (pořadí z config/sites.php).
     *
     * @return list<string>
     */
    public static function locales(): array
    {
        return Site::locales(true);
    }

    public static function siteUrl(string $locale): string
    {
        return Site::forLocale($locale)->url();
    }

    /**
     * Název routy pro daný jazyk: jazyky se shodnou cestou jako čeština sdílejí českou routu.
     */
    public static function routeNameFor(string $base, string $locale): string
    {
        return ($locale !== Site::PRIMARY_LOCALE && Route::has($base . '.' . $locale)) ? $base . '.' . $locale : $base;
    }

    /**
     * Základní název routy bez jazykové přípony (.en, .sk).
     */
    public static function baseRouteName(string $name): string
    {
        foreach (Site::locales() as $locale) {
            if (str_ends_with($name, '.' . $locale)) {
                return substr($name, 0, -strlen('.' . $locale));
            }
        }

        return $name;
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
        if (! in_array($locale, self::locales(), true)) {
            return null;
        }

        $base = self::baseRouteName($name);

        // Routa jiného jazyka na této doméně (LocalizedUrlRedirect by ji měl přesměrovat)
        if ($name !== self::routeNameFor($base, $locale)) {
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
     * Článek má protějšky v jazycích, ve kterých je publikovaný a přeložený
     * (vyplněný slug, titulek i obsah). Bez aspoň dvou jazyků se hreflang nevkládá.
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

        $urls = [];
        $slugs = [];
        foreach (self::locales() as $postLocale) {
            $postSlug = self::postSlug($post, $postLocale);
            if (blank($postSlug)
                || blank($post->getAttribute('title_' . $postLocale))
                || blank($post->getAttribute('content_' . $postLocale))) {
                continue;
            }
            $slugs[$postLocale] = $postSlug;
            $urls[$postLocale] = self::siteUrl($postLocale) . '/' . ltrim(route('blog.show', ['post' => $postSlug], false), '/');
        }

        // Slug jiného jazyka (BlogController ho přesměruje na slug této domény)
        if (! isset($urls[$locale]) || $slug !== $slugs[$locale] || count($urls) < 2) {
            return null;
        }

        return $urls;
    }

    /**
     * Slug článku v jazyce. Slovenský článek bez vlastního slugu (překlad titulku vyšel
     * stejně jako česky) běží na českém slugu stejně jako Post::getSlug(); angličtina
     * musí mít vlastní slug jako dřív.
     */
    private static function postSlug(Post $post, string $locale): ?string
    {
        $slug = $post->getAttribute('slug_' . $locale);

        if (blank($slug) && ! in_array($locale, [Site::PRIMARY_LOCALE, 'en'], true)) {
            return $post->slug_cs;
        }

        return $slug;
    }

    /**
     * @return array<string, string>
     */
    private static function build(string $base, array $parameters): array
    {
        $urls = [];

        foreach (self::locales() as $locale) {
            $path = trim(route(self::routeNameFor($base, $locale), $parameters, false), '/');
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
