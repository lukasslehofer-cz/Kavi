<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NormalizeOneTimeBoxConfiguredPrice extends Command
{
    protected $signature = 'subscriptions:normalize-onetime-price
                            {--force : Skutečně zapsat; bez tohoto přepínače jde jen o výpis}';

    protected $description = 'Srovná configured_price u starších jednorázových boxů na plnou cenu před slevou';

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        if (! $force) {
            $this->warn('DRY-RUN – nic se nezapisuje. Pro zápis přidej --force.');
        }

        $candidates = Subscription::where('frequency_months', 0)
            ->where('discount_amount', '>', 0)
            ->orderBy('id')
            ->get();

        if ($candidates->isEmpty()) {
            $this->info('Žádné jednorázové boxy se slevou k srovnání.');

            return self::SUCCESS;
        }

        $rows = [];
        $toFix = [];
        $skipped = [];

        foreach ($candidates as $subscription) {
            $payment = SubscriptionPayment::where('subscription_id', $subscription->id)
                ->orderBy('id')
                ->first();

            $configured = (float) $subscription->configured_price;
            $discount = (float) $subscription->discount_amount;
            $shipping = (float) ($subscription->shipping_cost ?? 0);

            if (! $payment) {
                // Bez platby nejde ověřit, který invariant platí – opuštěný checkout
                // nebo nezaplacený převod. Rozhodnout ručně.
                $skipped[] = [
                    $subscription->id,
                    $subscription->subscription_number,
                    $subscription->status,
                    'bez platby',
                ];

                continue;
            }

            $paid = (float) $payment->amount;

            // Starý invariant: uložená cena byla už po slevě, takže platba = cena + doprava.
            // Po srovnání platí paid = configured - discount + shipping, takže druhý běh
            // tuhle podmínku nesplní a příkaz je idempotentní.
            $legacyExpected = $configured + $shipping;

            if (abs($paid - $legacyExpected) > 0.01) {
                $skipped[] = [
                    $subscription->id,
                    $subscription->subscription_number,
                    $subscription->status,
                    'invariant nesedí (strženo '.number_format($paid, 2).', čekáno '.number_format($legacyExpected, 2).')',
                ];

                continue;
            }

            $rows[] = [
                $subscription->id,
                $subscription->subscription_number,
                $subscription->currency,
                number_format($configured, 2),
                number_format($configured + $discount, 2),
                number_format($paid, 2),
            ];
            $toFix[] = $subscription;
        }

        if ($rows) {
            $this->newLine();
            $this->table(
                ['ID', 'Číslo', 'Měna', 'configured_price teď', 'po srovnání', 'strženo'],
                $rows
            );
        } else {
            $this->info('Žádný řádek nesplňuje starý invariant – zřejmě už je vše srovnané.');
        }

        if ($skipped) {
            $this->newLine();
            $this->warn('Přeskočeno (rozhodnout ručně):');
            $this->table(['ID', 'Číslo', 'Stav', 'Důvod'], $skipped);
        }

        if (! $force || ! $toFix) {
            return self::SUCCESS;
        }

        foreach ($toFix as $subscription) {
            $before = (float) $subscription->configured_price;
            $after = $before + (float) $subscription->discount_amount;

            $subscription->update(['configured_price' => $after]);

            Log::info('Normalized one-time box configured_price', [
                'subscription_id' => $subscription->id,
                'subscription_number' => $subscription->subscription_number,
                'before' => $before,
                'after' => $after,
                'discount_amount' => (float) $subscription->discount_amount,
            ]);
        }

        $this->info('Srovnáno předplatných: '.count($toFix).'. subscription_payments.amount zůstal nedotčený.');

        return self::SUCCESS;
    }
}
