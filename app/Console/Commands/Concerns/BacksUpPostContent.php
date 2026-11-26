<?php

namespace App\Console\Commands\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Záloha původního obsahu článků před hromadnou úpravou (storage/app/blog-content-backups).
 */
trait BacksUpPostContent
{
    /**
     * @param  array<int, array<string, string>>  $original  post id => [sloupec => původní HTML]
     */
    protected function backupPostContent(string $name, array $original): string
    {
        $path = 'blog-content-backups/' . now()->format('Y-m-d_His') . '-' . $name . '.json';

        Storage::disk('local')->put(
            $path,
            json_encode($original, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        return storage_path('app/' . $path);
    }
}
