<?php

namespace App\Http\Middleware;

use App\Helpers\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectRegion
{
    /**
     * Handle an incoming request.
     *
     * Detects the site (config/sites.php) by domain and sets locale/currency accordingly.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Neznámá doména (localhost apod.) = český web
        $site = Site::forHost($request->getHost()) ?? Site::forLocale(Site::PRIMARY_LOCALE);

        // Set application locale
        app()->setLocale($site->locale);

        // Store currency and region in session and config for easy access
        session(['currency' => $site->currency()]);
        session(['region' => $site->region()]);

        // Also set in config for non-session contexts (API, commands, etc.)
        config(['app.currency' => $site->currency()]);
        config(['app.region' => $site->region()]);

        // Configure email settings based on the site
        $this->configureMailSettings($site);

        // Share with all views
        view()->share('currentCurrency', $site->currency());
        view()->share('currentRegion', $site->region());
        view()->share('currentLocale', $site->locale);

        return $next($request);
    }

    /**
     * Odesílatel a mailer pro e-maily odeslané v rámci requestu (podle webu).
     */
    protected function configureMailSettings(Site $site): void
    {
        if ($site->isPrimary()) {
            // Czech domain - default mail config (MAIL_FROM_*)
            return;
        }

        config([
            'mail.from.address' => $site->mailFrom()['address'],
            'mail.from.name' => $site->mailFrom()['name'],
        ]);

        // Use the site's mailer if configured
        if (config('mail.mailers.' . $site->mailer())) {
            config(['mail.default' => $site->mailer()]);
        }
    }
}
