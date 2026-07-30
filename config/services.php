<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'packeta' => [
        'api_key' => env('PACKETA_API_KEY'),
        'api_password' => env('PACKETA_API_PASSWORD'),
        'sender_id' => env('PACKETA_SENDER_ID'),
        'widget_key' => env('PACKETA_WIDGET_KEY'),
    ],

    'fakturoid' => [
        'client_id' => env('FAKTUROID_CLIENT_ID'),
        'client_secret' => env('FAKTUROID_CLIENT_SECRET'),
        'slug' => env('FAKTUROID_SLUG'),
        'number_format' => env('FAKTUROID_NUMBER_FORMAT'),
        'user_agent' => env('FAKTUROID_USER_AGENT', 'Kavi (info@kavi.cz)'),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'min_score' => env('RECAPTCHA_MIN_SCORE', 0.5),
    ],

    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
        // Use 'https://api-free.deepl.com/v2' for free tier, 'https://api.deepl.com/v2' for pro
        'api_url' => env('DEEPL_API_URL', 'https://api-free.deepl.com/v2'),
    ],

    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'),
        'pixel_id' => env('META_PIXEL_ID'),
        'conversions_api_token' => env('META_CONVERSIONS_API_TOKEN'),
        'test_event_code' => env('META_TEST_EVENT_CODE'),
        'regions' => [
            'cz' => [
                'pixel_id' => env('META_PIXEL_ID_CZ', env('META_PIXEL_ID')),
                'conversions_api_token' => env('META_CONVERSIONS_API_TOKEN_CZ', env('META_CONVERSIONS_API_TOKEN')),
                'test_event_code' => env('META_TEST_EVENT_CODE_CZ', env('META_TEST_EVENT_CODE')),
            ],
            'com' => [
                'pixel_id' => env('META_PIXEL_ID_COM'),
                'conversions_api_token' => env('META_CONVERSIONS_API_TOKEN_COM'),
                'test_event_code' => env('META_TEST_EVENT_CODE_COM'),
            ],
        ],
    ],

    'google_analytics' => [
        'measurement_id' => env('GA_MEASUREMENT_ID', 'G-96W0CFYXP1'),
        'regions' => [
            'cz' => [
                'measurement_id' => env('GA_MEASUREMENT_ID_CZ', env('GA_MEASUREMENT_ID', 'G-96W0CFYXP1')),
            ],
            'com' => [
                'measurement_id' => env('GA_MEASUREMENT_ID_COM'),
            ],
        ],
    ],

    'ecomail' => [
        'api_key' => env('ECOMAIL_API_KEY'),
        'list_id' => env('ECOMAIL_LIST_ID'),
    ],

    'google' => [
        'search_console_verification' => env('GOOGLE_SEARCH_CONSOLE_VERIFICATION'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Odkazy na veřejné recenzní profily
    |--------------------------------------------------------------------------
    |
    | Každá doména má vlastní Business Profile. Odkazy míří na writereview,
    | tedy do vyhledávání - dialog pro napsání recenze se otevře nad lokálním
    | knowledge panelem, na všech zařízeních stejně.
    |
    | Proč ne tvar g.page/r/<kód>/review: ten se na Googlu větví podle
    | User-Agentu a zařízení bez tokenu Android/iPhone/iPad (tedy i iPad
    | v desktop módu) skončí na Mapách, což je pro napsání recenze horší.
    | Zůstává ale jako záloha - je to jediný tvar, který Google oficiálně
    | generuje (Business Profile -> Read reviews -> Get more reviews -> Copy),
    | zatímco writereview je nedokumentovaný endpoint.
    |
    |   kavi.cz      https://g.page/r/CUKHHPAV65MnEBM/review
    |   kavibox.com  https://g.page/r/CXi_Z2uRcZAiEBM/review
    |
    | Kdyby writereview přestal fungovat, přepni přes REVIEW_LINK_CS / _EN
    | v .env bez nasazování kódu.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Google Business Profile - čtení vlastních recenzí
    |--------------------------------------------------------------------------
    |
    | Recenze jsou dostupné jen na legacy Google My Business API v4; nová
    | rozdělená v1 API je neobsahují. Přístup Google schvaluje ručně přes
    | "GBP API contact form" a do schválení je kvóta 0 QPM.
    |
    | refresh_token se získá jednorázovým OAuth souhlasem vlastníka profilu
    | (scope business.manage, access_type=offline, prompt=consent). Service
    | account použít nelze. Consent screen musí být "In production", v režimu
    | "Testing" token vyprší po 7 dnech.
    |
    */

    'google_reviews' => [
        'client_id' => env('GOOGLE_REVIEWS_CLIENT_ID'),
        'client_secret' => env('GOOGLE_REVIEWS_CLIENT_SECRET'),

        // Sdílený refresh token pro případ, že oba profily spravuje jeden
        // Google účet. Když má každý profil vlastní účet, vyplň refresh_token
        // u konkrétního profilu níž - ten má přednost.
        'refresh_token' => env('GOOGLE_REVIEWS_REFRESH_TOKEN'),

        // Každá doména má vlastní Business Profile, klíč je locale.
        'profiles' => [
            'cs' => [
                'account_id' => env('GOOGLE_REVIEWS_CS_ACCOUNT_ID'),
                'location_id' => env('GOOGLE_REVIEWS_CS_LOCATION_ID'),
                'refresh_token' => env('GOOGLE_REVIEWS_CS_REFRESH_TOKEN'),
            ],
            'en' => [
                'account_id' => env('GOOGLE_REVIEWS_EN_ACCOUNT_ID'),
                'location_id' => env('GOOGLE_REVIEWS_EN_LOCATION_ID'),
                'refresh_token' => env('GOOGLE_REVIEWS_EN_REFRESH_TOKEN'),
            ],
        ],
    ],

    'review_links' => [
        // kavi.cz - Place ID ChIJUTMeyWmRnmERQocc8BXrkyc, CID 2851881468510897986
        'cs' => env('REVIEW_LINK_CS', 'https://search.google.com/local/writereview?placeid=ChIJUTMeyWmRnmERQocc8BXrkyc'),

        // kavibox.com - Place ID ChIJLztJeicVqCEReL9na5FxkCI
        'en' => env('REVIEW_LINK_EN', 'https://search.google.com/local/writereview?placeid=ChIJLztJeicVqCEReL9na5FxkCI'),
    ],

];




