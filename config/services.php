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

];




