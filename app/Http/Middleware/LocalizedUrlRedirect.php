<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalizedUrlRedirect
{
    /**
     * Routes that should be localized.
     * Maps route keys to their translation keys in lang/xx/routes.php
     */
    protected array $localizedRoutes = [
        // Static pages
        'how-it-works' => 'how-it-works',
        'about' => 'about',
        'contact' => 'contact',
        'privacy-policy' => 'privacy-policy',
        'terms-of-service' => 'terms-of-service',
        
        // Products
        'products.index' => 'products',
        'products.show' => 'product',
        
        // Roasteries
        'roasteries.index' => 'roasteries',
        'roasteries.show' => 'roastery',
        
        // Monthly feature
        'monthly-feature.index' => 'monthly-feature',
        
        // Subscriptions
        'subscriptions.index' => 'subscription',
        'subscriptions.checkout' => 'subscription-checkout',
        'subscriptions.show' => 'subscription-plan',
        
        // Cart
        'cart.index' => 'cart',
        
        // Checkout
        'checkout.index' => 'checkout',
        
        // Auth
        'login' => 'login',
        'register' => 'register',
        'password.request' => 'password-request',
        'password.reset' => 'password-reset',
        
        // Dashboard
        'dashboard.index' => 'dashboard',
        'dashboard.profile' => 'dashboard-profile',
        'dashboard.orders' => 'dashboard-orders',
        'dashboard.order.detail' => 'dashboard-order-detail',
        'dashboard.subscription' => 'dashboard-subscription',
        'dashboard.notifications' => 'dashboard-notifications',
        'dashboard.payment-methods.manage' => 'dashboard-payment-methods',
    ];

    /**
     * Handle an incoming request.
     *
     * Check if the current URL matches the expected locale and redirect if not.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for non-GET requests, AJAX, API, webhooks, admin
        if (!$request->isMethod('GET') 
            || $request->ajax() 
            || $request->is('api/*') 
            || $request->is('webhook/*')
            || $request->is('admin/*')
            || $request->is('feed/*')
        ) {
            return $next($request);
        }

        $currentLocale = app()->getLocale();
        $path = $request->path();
        
        // Get the opposite locale
        $oppositeLocale = $currentLocale === 'cs' ? 'en' : 'cs';
        
        // Try to find if current path matches a route from the opposite locale
        $redirectUrl = $this->findRedirectUrl($path, $currentLocale, $oppositeLocale);
        
        if ($redirectUrl) {
            return redirect($redirectUrl, 301);
        }

        return $next($request);
    }

    /**
     * Find if the current path needs to be redirected to the correct locale version.
     */
    protected function findRedirectUrl(string $path, string $currentLocale, string $oppositeLocale): ?string
    {
        // Load route translations for both locales
        $currentRoutes = $this->getRouteTranslations($currentLocale);
        $oppositeRoutes = $this->getRouteTranslations($oppositeLocale);

        // Check each localized route
        foreach ($this->localizedRoutes as $routeName => $translationKey) {
            $oppositePattern = $oppositeRoutes[$translationKey] ?? null;
            $currentPattern = $currentRoutes[$translationKey] ?? null;

            if (!$oppositePattern || !$currentPattern) {
                continue;
            }

            // Convert route pattern to regex
            $regex = $this->patternToRegex($oppositePattern);
            
            if (preg_match($regex, $path, $matches)) {
                // Found a match in the opposite locale - need to redirect
                $newPath = $currentPattern;
                
                // Replace parameters
                foreach ($matches as $key => $value) {
                    if (!is_numeric($key)) {
                        $newPath = str_replace('{' . $key . '}', $value, $newPath);
                    }
                }
                
                // Handle dashboard prefix
                if (str_starts_with($translationKey, 'dashboard-') && $translationKey !== 'dashboard') {
                    $dashboardPrefix = $currentRoutes['dashboard'] ?? 'dashboard';
                    $newPath = $dashboardPrefix . '/' . $newPath;
                }
                
                return '/' . $newPath;
            }
        }

        // Also check for dashboard prefix routes
        $oppositeDashboard = $oppositeRoutes['dashboard'] ?? 'dashboard';
        $currentDashboard = $currentRoutes['dashboard'] ?? 'dashboard';
        
        if (str_starts_with($path, $oppositeDashboard . '/') && $oppositeDashboard !== $currentDashboard) {
            // Dashboard prefix mismatch
            foreach ($this->localizedRoutes as $routeName => $translationKey) {
                if (!str_starts_with($translationKey, 'dashboard-')) {
                    continue;
                }
                
                $oppositeSubPath = $oppositeRoutes[$translationKey] ?? null;
                $currentSubPath = $currentRoutes[$translationKey] ?? null;
                
                if (!$oppositeSubPath || !$currentSubPath) {
                    continue;
                }
                
                $fullOppositePath = $oppositeDashboard . '/' . $oppositeSubPath;
                $regex = $this->patternToRegex($fullOppositePath);
                
                if (preg_match($regex, $path, $matches)) {
                    $newPath = $currentSubPath;
                    
                    foreach ($matches as $key => $value) {
                        if (!is_numeric($key)) {
                            $newPath = str_replace('{' . $key . '}', $value, $newPath);
                        }
                    }
                    
                    return '/' . $currentDashboard . '/' . $newPath;
                }
            }
        }

        return null;
    }

    /**
     * Convert a route pattern like "product/{product}" to a regex.
     */
    protected function patternToRegex(string $pattern): string
    {
        // Escape special regex characters except our placeholders
        $regex = preg_quote($pattern, '/');
        
        // Replace placeholders with named capture groups
        $regex = preg_replace('/\\\{([a-zA-Z_]+)\\\}/', '(?P<$1>[^\/]+)', $regex);
        
        return '/^' . $regex . '$/';
    }

    /**
     * Get route translations for a locale.
     */
    protected function getRouteTranslations(string $locale): array
    {
        $path = lang_path($locale . '/routes.php');
        
        if (file_exists($path)) {
            return require $path;
        }
        
        return [];
    }
}

