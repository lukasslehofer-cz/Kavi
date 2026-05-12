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
}
