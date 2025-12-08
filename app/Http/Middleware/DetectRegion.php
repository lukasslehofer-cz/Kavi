<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectRegion
{
    /**
     * Handle an incoming request.
     *
     * Detects the region based on the domain and sets locale/currency accordingly.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Get region configuration
        $regions = config('app.regions', []);
        
        // Find matching region by domain (supports subdomains like www.)
        $region = null;
        foreach ($regions as $domain => $config) {
            if ($host === $domain || str_ends_with($host, '.' . $domain) || $host === 'www.' . $domain) {
                $region = $config;
                break;
            }
        }
        
        // Default to Czech if no region matched (for localhost, etc.)
        if (!$region) {
            $region = [
                'locale' => 'cs',
                'currency' => 'CZK',
                'region_code' => 'cz',
            ];
        }
        
        // Set application locale
        app()->setLocale($region['locale']);
        
        // Store currency and region in session and config for easy access
        session(['currency' => $region['currency']]);
        session(['region' => $region['region_code'] ?? 'cz']);
        
        // Also set in config for non-session contexts (API, commands, etc.)
        config(['app.currency' => $region['currency']]);
        config(['app.region' => $region['region_code'] ?? 'cz']);
        
        // Share with all views
        view()->share('currentCurrency', $region['currency']);
        view()->share('currentRegion', $region['region_code'] ?? 'cz');
        view()->share('currentLocale', $region['locale']);
        
        return $next($request);
    }
}

