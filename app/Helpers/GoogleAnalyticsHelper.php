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
    // `gtag('event', ...)` calls fire to every destination of that Google tag (GA4 and the
    // linked Google Ads account). Never send events as `dataLayer.push({event: ...})`:
    // only Google Tag Manager reads that shape, gtag.js silently drops it.
    public static function currentMeasurementId(): ?string
    {
        return self::measurementIdFor(CurrencyHelper::region());
    }

    public static function measurementIdFor(string $region): ?string
    {
        return self::configFor($region)['measurement_id'];
    }
}
