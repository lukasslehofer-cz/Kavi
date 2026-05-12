<?php

namespace App\Helpers;

class GoogleAnalyticsHelper
{
    public static function configFor(string $region): array
    {
        $config = config("services.google_analytics.regions.{$region}");

        return [
            'measurement_id' => $config['measurement_id'] ?? null,
        ];
    }

    // `gtag('config', ...)` must remain centralized in the layout templates — downstream
    // `dataLayer.push({event: ...})` calls fire to whichever measurement ID the layout configured.
    public static function currentMeasurementId(): ?string
    {
        return self::measurementIdFor(CurrencyHelper::region());
    }

    public static function measurementIdFor(string $region): ?string
    {
        return self::configFor($region)['measurement_id'];
    }
}
