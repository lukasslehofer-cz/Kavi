<?php

/*
|--------------------------------------------------------------------------
| Jazykové weby
|--------------------------------------------------------------------------
|
| Jedna aplikace a jedna databáze obsluhuje několik domén. Klíč je locale,
| podle domény requestu ho vybírá DetectRegion. Pořadí určuje pořadí
| v přepínači jazyka a v hreflang značkách. Čtení jen přes App\Helpers\Site.
|
| url        kanonická adresa webu bez lomítka na konci. V e-mailech a hreflangu
|            se URL nesmí odvozovat od hostitele requestu (admin posílá EN maily
|            z kavi.cz) ani od APP_URL (cron).
| region     klíč regionu pro dopravní sazby, GA a Meta pixel (cz / com / sk)
| enabled    false = web běží na své doméně, ale ostatní weby na něj neodkazují
|            (přepínač jazyka, hreflang) – pro přípravu před spuštěním
|
*/

return [
    'cs' => [
        'domains' => ['kavi.cz', 'kavi.local'],
        'url' => env('SITE_URL_CS', 'https://kavi.cz'),
        'currency' => 'CZK',
        'region' => 'cz',
        'name' => 'KAVI.cz',
        'label' => 'CZ',
        'language_name' => 'Čeština',
        'og_locale' => 'cs_CZ',
        'contact_email' => 'info@kavi.cz',
        'mailer' => 'smtp',
        'mail_from_address' => env('MAIL_FROM_ADDRESS', 'info@kavi.cz'),
        'mail_from_name' => env('MAIL_FROM_NAME', 'KAVI.cz'),
        'default_country' => 'CZ',
        'enabled' => true,
    ],

    'en' => [
        'domains' => ['kavibox.com', 'kavibox.local'],
        'url' => env('SITE_URL_EN', 'https://kavibox.com'),
        'currency' => 'EUR',
        'region' => 'com',
        'name' => 'KAVI',
        'label' => 'EN',
        'language_name' => 'English',
        'og_locale' => 'en_US',
        'contact_email' => 'info@kavibox.com',
        'mailer' => 'smtp_en',
        'mail_from_address' => env('MAIL_FROM_ADDRESS_EN', 'info@kavibox.com'),
        'mail_from_name' => env('MAIL_FROM_NAME_EN', 'KAVI'),
        'default_country' => 'CZ',
        'enabled' => true,
    ],

    'sk' => [
        'domains' => ['kavibox.sk', 'kavibox-sk.localhost'],
        'url' => env('SITE_URL_SK', 'https://kavibox.sk'),
        'currency' => 'EUR',
        'region' => 'sk',
        'name' => 'KAVIbox.sk',
        'label' => 'SK',
        'language_name' => 'Slovenčina',
        'og_locale' => 'sk_SK',
        'contact_email' => 'info@kavibox.sk',
        'mailer' => 'smtp_sk',
        'mail_from_address' => env('MAIL_FROM_ADDRESS_SK', 'info@kavibox.sk'),
        'mail_from_name' => env('MAIL_FROM_NAME_SK', 'KAVIbox.sk'),
        'default_country' => 'SK',
        'enabled' => (bool) env('SITE_SK_ENABLED', false),
    ],
];
