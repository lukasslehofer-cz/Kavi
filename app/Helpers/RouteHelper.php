<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('localizedRoute')) {
    /**
     * Generate a URL for a named route using the current locale.
     *
     * For Czech (primary) locale: uses route name without suffix (e.g., 'login')
     * For English locale: uses route name with .en suffix (e.g., 'login.en')
     *
     * @param string $name The base route name (without locale suffix)
     * @param mixed $parameters Route parameters
     * @param bool $absolute Whether to generate an absolute URL
     * @return string The generated URL
     */
    function localizedRoute(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $locale = app()->getLocale();
        $primaryLocale = 'cs';
        
        // List of routes that are not localized (no locale suffix ever)
        $nonLocalizedRoutes = [
            'home',
            'feed.heureka',
            'coupon.activate',
            'api.calculate-shipping',
            'stripe.webhook',
        ];
        
        // Check if this route should not be localized
        if (in_array($name, $nonLocalizedRoutes) || str_starts_with($name, 'admin.')) {
            return route($name, $parameters, $absolute);
        }
        
        // For primary locale (Czech), use route without suffix
        if ($locale === $primaryLocale) {
            if (Route::has($name)) {
                return route($name, $parameters, $absolute);
            }
        }
        
        // For non-primary locales, try with locale suffix first
        $localizedName = "{$name}.{$locale}";
        
        if (Route::has($localizedName)) {
            return route($localizedName, $parameters, $absolute);
        }
        
        // Fallback to base route name (might work for primary locale routes)
        if (Route::has($name)) {
            return route($name, $parameters, $absolute);
        }
        
        // This will throw an exception if the route doesn't exist
        return route($name, $parameters, $absolute);
    }
}

if (!function_exists('safeRedirectPath')) {
    /**
     * Validate a user-supplied redirect target and return it only if it is a
     * safe internal path. Prevents open-redirect attacks (e.g. "//evil.com",
     * "https://evil.com", "javascript:...", backslash tricks).
     *
     * @param string|null $redirect The raw redirect value from the request
     * @return string|null The path if it is a safe relative URL, otherwise null
     */
    function safeRedirectPath(?string $redirect): ?string
    {
        if (!is_string($redirect) || $redirect === '') {
            return null;
        }

        // Must be an absolute path on this site: starts with a single "/".
        // Reject protocol-relative ("//host"), backslash variants ("/\host")
        // and anything containing a scheme separator or control characters.
        if (!str_starts_with($redirect, '/')) {
            return null;
        }

        if (str_starts_with($redirect, '//') || str_starts_with($redirect, '/\\')) {
            return null;
        }

        if (preg_match('#[\x00-\x1f\\\\]#', $redirect) || str_contains($redirect, ':')) {
            return null;
        }

        return $redirect;
    }
}

if (!function_exists('localizedRouteName')) {
    /**
     * Get the localized route name for a given base route name.
     *
     * @param string $name The base route name
     * @return string The localized route name
     */
    function localizedRouteName(string $name): string
    {
        $locale = app()->getLocale();
        $primaryLocale = 'cs';
        
        // Non-localized routes
        if (str_starts_with($name, 'admin.') || $name === 'home') {
            return $name;
        }
        
        // Primary locale doesn't use suffix
        if ($locale === $primaryLocale) {
            return $name;
        }
        
        return "{$name}.{$locale}";
    }
}

if (!function_exists('alternateLocaleUrl')) {
    /**
     * Get the URL for the current page in an alternate locale.
     * Useful for language switchers.
     *
     * @param string $locale The target locale
     * @return string|null The URL in the alternate locale, or null if not available
     */
    function alternateLocaleUrl(string $locale): ?string
    {
        $currentRoute = Route::current();
        $primaryLocale = 'cs';
        
        if (!$currentRoute) {
            return null;
        }
        
        $currentName = $currentRoute->getName();
        
        if (!$currentName) {
            return null;
        }
        
        // Extract base name (remove locale suffix if present)
        $baseName = preg_replace('/\.(cs|en)$/', '', $currentName);
        
        // Non-localized routes
        if (str_starts_with($baseName, 'admin.') || $baseName === 'home') {
            return route($baseName, $currentRoute->parameters());
        }
        
        // For primary locale target, use base name
        if ($locale === $primaryLocale) {
            if (Route::has($baseName)) {
                return route($baseName, $currentRoute->parameters());
            }
        } else {
            // For non-primary locale target, use suffixed name
            $alternateName = "{$baseName}.{$locale}";
            if (Route::has($alternateName)) {
                return route($alternateName, $currentRoute->parameters());
            }
        }
        
        return null;
    }
}

if (!function_exists('localizedRouteIs')) {
    /**
     * Check if the current route matches a given pattern, accounting for locale suffixes.
     *
     * @param string ...$patterns Route name patterns to check
     * @return bool
     */
    function localizedRouteIs(string ...$patterns): bool
    {
        $currentRoute = Route::current();
        
        if (!$currentRoute) {
            return false;
        }
        
        $currentName = $currentRoute->getName();
        
        if (!$currentName) {
            return false;
        }
        
        // Extract base name (remove locale suffix if present)
        $baseName = preg_replace('/\.(cs|en)$/', '', $currentName);
        
        foreach ($patterns as $pattern) {
            // Check against both the full name and the base name
            if (fnmatch($pattern, $currentName) || fnmatch($pattern, $baseName)) {
                return true;
            }
        }
        
        return false;
    }
}
