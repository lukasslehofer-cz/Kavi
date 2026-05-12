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

];




