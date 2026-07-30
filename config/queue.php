<?php

$connection = env('QUEUE_CONNECTION', 'sync');

// Starší .env měly QUEUE_CONNECTION=file, což není platný driver. Bez tohoto
// fallbacku by takové nastavení shodilo každé odeslání queued mailu výjimkou.
if (! in_array($connection, ['sync', 'database', 'redis'], true)) {
    $connection = 'sync';
}

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Bez tohoto souboru se QUEUE_CONNECTION z .env vůbec nepřečte (env() se čte
    | jen uvnitř config souborů), config('queue.default') je null a Laravel
    | spadne zpět na NullQueue, která joby tiše zahazuje.
    |
    */

    'default' => $connection,

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
