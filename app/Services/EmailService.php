<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;

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
     * Get locale from Order
     */
    public static function getLocaleFromOrder(Order $order): string
    {
        return self::getLocaleFromCurrency($order->currency);
    }
    
    /**
     * Get locale from Subscription
     */
    public static function getLocaleFromSubscription(Subscription $subscription): string
    {
        return self::getLocaleFromCurrency($subscription->currency);
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
        if ($subscription && $subscription->currency) {
            return self::getLocaleFromCurrency($subscription->currency);
        }
        
        // Priority 3: Try to get locale from user's most recent order
        $order = $user->orders()->latest()->first();
        if ($order && $order->currency) {
            return self::getLocaleFromCurrency($order->currency);
        }
        
        // Default to Czech
        return 'cs';
    }
    
    /**
     * Get the "from" email address based on locale
     */
    public static function getFromAddress(string $locale): array
    {
        if ($locale === 'en') {
            return [
                'address' => env('MAIL_FROM_ADDRESS_EN', 'info@kavibox.com'),
                'name' => env('MAIL_FROM_NAME_EN', 'KAVI'),
            ];
        }
        
        return [
            'address' => env('MAIL_FROM_ADDRESS', 'info@kavi.cz'),
            'name' => env('MAIL_FROM_NAME', 'KAVI.cz'),
        ];
    }
    
    /**
     * Get site name based on locale
     */
    public static function getSiteName(string $locale): string
    {
        return $locale === 'en' ? 'KAVI' : 'KAVI.cz';
    }
    
    /**
     * Get site URL based on locale
     */
    public static function getSiteUrl(string $locale): string
    {
        return $locale === 'en' 
            ? env('APP_URL_EN', 'https://kavibox.com')
            : env('APP_URL', 'https://kavi.cz');
    }
    
    /**
     * Get contact email based on locale
     */
    public static function getContactEmail(string $locale): string
    {
        return $locale === 'en' ? 'info@kavibox.com' : 'info@kavi.cz';
    }
    
    /**
     * Get the mailer name based on locale
     * Different SMTP credentials are needed for each domain
     */
    public static function getMailer(string $locale): string
    {
        return $locale === 'en' ? 'smtp_en' : 'smtp';
    }
}

