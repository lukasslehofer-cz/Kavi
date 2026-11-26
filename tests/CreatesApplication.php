<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use RuntimeException;

trait CreatesApplication
{
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Pojistka ještě před RefreshDatabase: testy nikdy proti vývojové ani produkční DB
        // (např. při zacachovaném configu, který přebije phpunit.xml)
        if ($app['config']->get('database.connections.mysql.database') !== 'kavi_testing') {
            throw new RuntimeException('Testy musí běžet proti databázi kavi_testing (phpunit.xml). Nejdřív php artisan config:clear.');
        }

        return $app;
    }
}
