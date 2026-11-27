<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Přihlášení v jazyce webu (kavibox.com/login, kavibox.sk/prihlasenie), ne vždy české.
        // Jazyk podle domény: auth běží před DetectRegion, app()->getLocale() je tu ještě výchozí.
        $locale = (\App\Helpers\Site::forHost($request->getHost()) ?? \App\Helpers\Site::forLocale(null))->locale;

        return route(\App\Services\EmailService::routeName($locale, 'login'));
    }
}




