<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;
use App\Helpers\Site;
use Illuminate\Support\Facades\Route;

class EmailService
{
    /**
     * Get locale from currency code
     * CZK = cs (Czech), EUR = en (English)
     */
    public static function getLocaleFromCurrency(?string $currency): string
    {
        if ($currency === 'EUR') {
            return 'en';
        }
        
        return 'cs'; // Default to Czech
    }
    
    /**
     * Get locale from Order: jazyk webu, na kterém vznikla (sloupec locale), jinak podle měny
     */
    public static function getLocaleFromOrder(Order $order): string
    {
        return Site::exists($order->locale) ? $order->locale : self::getLocaleFromCurrency($order->currency);
    }
    
    /**
     * Get locale from Subscription: jazyk webu, na kterém vzniklo (sloupec locale), jinak podle měny
     */
    public static function getLocaleFromSubscription(Subscription $subscription): string
    {
        return Site::exists($subscription->locale) ? $subscription->locale : self::getLocaleFromCurrency($subscription->currency);
    }
    
    /**
     * Get locale from User
     * Priority: 1) User's stored locale, 2) Subscription currency, 3) Order currency, 4) Default cs
     */
    public static function getLocaleFromUser(User $user): string
    {
        // Priority 1: User's explicitly stored locale (set during registration based on domain)
        if ($user->locale) {
            return $user->locale;
        }
        
        // Priority 2: Try to get locale from user's most recent subscription
        $subscription = $user->subscriptions()->latest()->first();
        if ($subscription) {
            return self::getLocaleFromSubscription($subscription);
        }
        
        // Priority 3: Try to get locale from user's most recent order
        $order = $user->orders()->latest()->first();
        if ($order) {
            return self::getLocaleFromOrder($order);
        }
        
        // Default to Czech
        return 'cs';
    }
    
    /**
     * Get the "from" email address based on locale (config/sites.php)
     */
    public static function getFromAddress(string $locale): array
    {
        return Site::forLocale($locale)->mailFrom();
    }

    /**
     * Get site name based on locale
     */
    public static function getSiteName(string $locale): string
    {
        return Site::forLocale($locale)->name();
    }

    /**
     * Get site URL based on locale (bez lomítka na konci)
     */
    public static function getSiteUrl(string $locale): string
    {
        return Site::forLocale($locale)->url();
    }

    /**
     * Absolutní URL do e-mailu: doména podle jazyka příjemce, cesta podle jazykové
     * varianty routy.
     *
     * route() bere hostitele z requestu (admin posílá EN mail z kavi.cz) nebo
     * z APP_URL (cron) – obojí je špatně. Doména i cesta musí sedět dohromady,
     * jinak LocalizedUrlRedirect udělá 301 zpátky na druhou jazykovou variantu.
     *
     * Mailable třídy ji volají přes LocalizedMailable::mailUrl(), přímo ji používají
     * e-maily, které z LocalizedMailable nedědí (reset hesla, upozornění adminům).
     */
    public static function routeUrl(string $locale, string $name, array $parameters = []): string
    {
        return self::getSiteUrl($locale).route(self::routeName($locale, $name), $parameters, false);
    }

    /**
     * Název jazykové varianty routy (login.en); jazyky se stejnou cestou jako
     * čeština (většina slovenských) sdílejí českou routu bez přípony.
     */
    public static function routeName(string $locale, string $name): string
    {
        $localized = "{$name}.{$locale}";

        return Route::has($localized) ? $localized : $name;
    }

    /**
     * Logo do hlavičky e-mailu na doméně podle jazyka příjemce
     */
    public static function getLogoUrl(string $locale): string
    {
        return self::getSiteUrl($locale).'/images/kavi-logo-white.png';
    }

    /**
     * Get contact email based on locale
     */
    public static function getContactEmail(string $locale): string
    {
        return Site::forLocale($locale)->contactEmail();
    }

    /**
     * Get the mailer name based on locale
     * Different SMTP credentials are needed for each domain
     */
    public static function getMailer(string $locale): string
    {
        return Site::forLocale($locale)->mailer();
    }
}
