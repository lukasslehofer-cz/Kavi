<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Záznam o posledním plném běhu subscriptions:charge-payments.
 *
 * Čte ho monitor (subscriptions:monitor-billing) a záložní běh billingu v 06:00
 * v Console\Kernel. Záměrně mimo aplikační cache, stejně jako Google recenze:
 * na produkci je cache v Redisu, deploy na ni pouští cache:clear (= FLUSHDB)
 * a klíč z ní mizel i mezi běhy. Monitor pak každou hodinu hlásil, že billing
 * neproběhl, i když proběhl.
 *
 * Tvar souboru:
 *
 *   ['last_run' => ISO 8601,
 *    'summary' => ['timestamp', 'total', 'successful', 'failed', 'skipped', 'results']]
 */
class BillingCronHeartbeat
{
    private const PATH = 'billing-cron/last-run.json';

    /**
     * @param  array<string, mixed>  $summary
     */
    public static function record(array $summary): void
    {
        Storage::disk('local')->put(self::PATH, json_encode([
            'last_run' => now()->toIso8601String(),
            'summary' => $summary,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public static function lastRun(): ?Carbon
    {
        $lastRun = self::read()['last_run'] ?? null;

        // ISO 8601 nese pevný offset - převod zpět do zóny aplikace, ať isToday()
        // počítá s pražským dnem i přes přechod letního času
        return is_string($lastRun)
            ? Carbon::parse($lastRun)->setTimezone(config('app.timezone'))
            : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function lastSummary(): ?array
    {
        $summary = self::read()['summary'] ?? null;

        return is_array($summary) ? $summary : null;
    }

    public static function ranToday(): bool
    {
        return self::lastRun()?->isToday() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    private static function read(): array
    {
        if (! Storage::disk('local')->exists(self::PATH)) {
            return [];
        }

        $decoded = json_decode(Storage::disk('local')->get(self::PATH), true);

        return is_array($decoded) ? $decoded : [];
    }
}
