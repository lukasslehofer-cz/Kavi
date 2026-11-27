<?php

namespace App\Helpers;

class MetaPixelHelper
{
    public static function configFor(string $region): array
    {
        $config = config("services.facebook.regions.{$region}");

        return [
            'pixel_id' => $config['pixel_id'] ?? null,
            'conversions_api_token' => $config['conversions_api_token'] ?? null,
            'test_event_code' => $config['test_event_code'] ?? null,
        ];
    }

    // Pixel `fbq('init', ...)` must remain centralized in the layout templates — downstream
    // `fbq('track', ...)` calls fire to whichever pixel the layout initialized.
    public static function currentPixelId(): ?string
    {
        return self::pixelIdFor(CurrencyHelper::region());
    }

    public static function pixelIdFor(string $region): ?string
    {
        return self::configFor($region)['pixel_id'];
    }

    public static function tokenFor(string $region): ?string
    {
        return self::configFor($region)['conversions_api_token'];
    }

    public static function testEventCodeFor(string $region): ?string
    {
        return self::configFor($region)['test_event_code'];
    }

    public static function regionFromCurrency(?string $currency): string
    {
        return strtoupper((string) $currency) === 'EUR' ? 'com' : 'cz';
    }

    /**
     * Region pixelu podle webu, na kterém objednávka / předplatné vzniklo
     * (kavibox.sk má vlastní pixel), u starých záznamů bez jazyka podle měny.
     */
    public static function regionFor(\App\Models\Order|\App\Models\Subscription $entity): string
    {
        return Site::exists($entity->locale)
            ? Site::forLocale($entity->locale)->region()
            : self::regionFromCurrency($entity->currency);
    }

    // IP a prohlížeč zákazníka se ukládají v pokladně (meta_client_*), protože Purchase
    // pro CAPI často odchází až z webhooku Stripe, kde request() patří Stripe.
    public static function clientIp(): ?string
    {
        return request()->ip() ?: null;
    }

    // 500 znaků = sloupec meta_client_user_agent i limit hodnoty ve Stripe metadata
    public static function clientUserAgent(): ?string
    {
        $userAgent = request()->userAgent();

        return $userAgent ? mb_substr($userAgent, 0, 500) : null;
    }
}
