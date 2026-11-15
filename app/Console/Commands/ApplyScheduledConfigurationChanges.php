<?php

namespace App\Console\Commands;

use App\Services\SubscriptionConfigurationService;
use Illuminate\Console\Command;

/**
 * Použije naplánované změny konfigurace předplatného.
 *
 * Změna zadaná v době, kdy má předplatné zaplacený, ale neodeslaný box, čeká se
 * status = scheduled - živou konfiguraci čte rozesílka, sklad i faktura, takže se
 * nesmí změnit dřív, než ten box odejde. Tenhle příkaz ji použije první noc, kdy už
 * žádný zamčený box nezbývá (SubscriptionShipmentService::lockedShipment).
 *
 * Běží v 00:30, tedy před strháváním plateb v 01:00, aby se další box účtoval už
 * v nové ceně.
 */
class ApplyScheduledConfigurationChanges extends Command
{
    protected $signature = 'subscriptions:apply-configuration-changes
                            {--dry-run : Jen vypíše, co by se stalo, nic nezapíše}
                            {--subscription= : Zpracovat jen jedno předplatné (ID)}';

    protected $description = 'Apply scheduled subscription configuration changes once the paid box has shipped';

    public function handle(SubscriptionConfigurationService $configurationService): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $subscriptionId = $this->option('subscription') ? (int) $this->option('subscription') : null;

        $this->info('Looking for scheduled subscription configuration changes...'.($dryRun ? ' (dry run)' : ''));

        $report = $configurationService->applyDueScheduledChanges($subscriptionId, $dryRun);

        foreach ($report['applied'] as $entry) {
            $this->info(sprintf(
                '  %s %s (change #%d)%s',
                $dryRun ? 'Would apply:' : 'Applied:',
                $entry['subscription_number'] ?? '#'.$entry['subscription_id'],
                $entry['change_id'],
                isset($entry['effective_shipment_date']) ? ' -> first box '.$entry['effective_shipment_date'] : '',
            ));
        }

        foreach ($report['waiting'] as $entry) {
            $this->line(sprintf(
                '  Waiting: %s (change #%d) - %s',
                $entry['subscription_number'] ?? '#'.$entry['subscription_id'],
                $entry['change_id'],
                $entry['reason'],
            ));
        }

        foreach ($report['cancelled'] as $entry) {
            $this->warn(sprintf(
                '  Cancelled: %s (change #%d) - %s',
                $entry['subscription_number'] ?? '#'.$entry['subscription_id'],
                $entry['change_id'],
                $entry['reason'],
            ));
        }

        foreach ($report['failed'] as $entry) {
            $this->error(sprintf(
                '  Failed: subscription #%d (change #%d) - %s',
                $entry['subscription_id'],
                $entry['change_id'],
                $entry['reason'],
            ));
        }

        foreach ($report['stock_warnings'] as $entry) {
            $this->warn(sprintf(
                '  Stock: %s (change #%d) - shipment %s short of: %s',
                $entry['subscription_number'] ?? '#'.$entry['subscription_id'],
                $entry['change_id'],
                $entry['shipment_date'] ?? '?',
                $this->describeShortage($entry),
            ));
        }

        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            [
                [$dryRun ? 'Would apply' : 'Applied', count($report['applied'])],
                ['Waiting', count($report['waiting'])],
                ['Cancelled', count($report['cancelled'])],
                ['Failed', count($report['failed'])],
                ['Stock warnings', count($report['stock_warnings'])],
            ]
        );

        if (! $dryRun && (count($report['failed']) > 0 || count($report['stock_warnings']) > 0)) {
            $this->sendAlert($report);
        }

        return count($report['failed']) > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function describeShortage(array $entry): string
    {
        $parts = array_merge(
            $entry['out_of_stock'] ?? [],
            array_map(fn ($slot) => 'slot '.strtoupper($slot).' not set', $entry['missing_slots'] ?? []),
        );

        return $parts ? implode(', ', $parts) : 'unknown';
    }

    /**
     * Upozornění adminovi - stejný kanál jako u subscriptions:resume-paused.
     *
     * Dvě věci, které by jinak zapadly v logu: změna, kterou se nepodařilo použít
     * (předplatné jede dál ve staré konfiguraci), a změna použitá do rozesílky, pro
     * kterou už zvolená káva nestačí (zákazník ji má potvrzenou, kávu je potřeba dokoupit
     * nebo se s ním domluvit).
     */
    private function sendAlert(array $report): void
    {
        try {
            $adminEmail = config('mail.from.address');

            if (! $adminEmail) {
                return;
            }

            $body = "Subscription Configuration Changes Alert\n\n"
                .'Date: '.now()->toDateTimeString()."\n"
                .'Applied: '.count($report['applied']).', Failed: '.count($report['failed'])
                .', Stock warnings: '.count($report['stock_warnings'])."\n";

            if ($report['failed']) {
                $body .= "\nFailed to apply (subscription keeps its old configuration):\n"
                    .collect($report['failed'])
                        ->map(fn ($d) => "- subscription #{$d['subscription_id']} (change #{$d['change_id']}): {$d['reason']}")
                        ->join("\n")."\n";
            }

            if ($report['stock_warnings']) {
                $body .= "\nApplied, but the nearest shipment is short of coffee:\n"
                    .collect($report['stock_warnings'])
                        ->map(fn ($d) => '- '.($d['subscription_number'] ?? '#'.$d['subscription_id'])
                            ." (change #{$d['change_id']}), shipment ".($d['shipment_date'] ?? '?').': '
                            .$this->describeShortage($d))
                        ->join("\n")."\n";
            }

            \Mail::raw($body, function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                    ->subject('Subscription Configuration Changes - '.now()->toDateString());
            });

            $this->warn("Alert email sent to {$adminEmail}");
        } catch (\Exception $e) {
            $this->error('Failed to send alert email: '.$e->getMessage());
        }
    }
}
