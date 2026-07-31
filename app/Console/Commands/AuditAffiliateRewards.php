<?php

namespace App\Console\Commands;

use App\Models\AffiliateReward;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Console\Command;

class AuditAffiliateRewards extends Command
{
    protected $signature = 'affiliate:audit
                            {--subscription= : Omezí kontrolu na jedno subscription_number}';

    protected $description = 'Zkontroluje konzistenci affiliate odměn – chybějící odměny a nesedící sazby';

    public function handle(): int
    {
        $onlyNumber = $this->option('subscription');

        $subscriptions = Subscription::whereNotNull('coupon_id')
            ->when($onlyNumber, fn ($q) => $q->where('subscription_number', $onlyNumber))
            ->with('coupon')
            ->get();

        $missing = [];
        $mismatched = [];
        $orphaned = [];
        $shifted = [];

        foreach ($subscriptions as $sub) {
            $coupon = $sub->coupon;

            if (! $coupon || ! $coupon->hasAffiliateSubscriptionReward()) {
                continue;
            }

            $currency = strtoupper($sub->currency ?? 'CZK');

            $payments = SubscriptionPayment::where('subscription_id', $sub->id)
                ->where('status', 'paid')
                ->orderBy('paid_at')
                ->orderBy('id')
                ->get();

            $rewards = AffiliateReward::where('subscription_id', $sub->id)
                ->whereNotNull('subscription_payment_number')
                ->get()
                ->keyBy('subscription_payment_number');

            // Posunuté číslování: odměn je stejně jako plateb, jen mají jiná pořadová
            // čísla (typicky proto, že se pořadí odvozuje z COUNT zaplacených plateb
            // a nějaká platba mezitím zmizela). Každá rozesílka JE odměněná – hlásit
            // je jako chybějící + přebývající by svádělo k dvojímu vyplacení.
            $activeRewards = $rewards->filter(fn ($r) => $r->status !== 'cancelled');
            $expectedNumbers = range(1, max(1, $payments->count()));

            if ($payments->isNotEmpty()
                && $activeRewards->count() === $payments->count()
                && $activeRewards->keys()->sort()->values()->all() !== $expectedNumbers) {
                $shifted[] = [
                    $sub->subscription_number,
                    $payments->count(),
                    $activeRewards->keys()->sort()->implode(', '),
                    $coupon->code,
                    $activeRewards->sum('reward_amount').' '.$currency,
                ];

                continue;
            }

            foreach ($payments as $i => $payment) {
                $paymentNumber = $i + 1;
                $tier = $coupon->affiliateSubscriptionTierFor($paymentNumber);
                $expected = $coupon->calculateAffiliateSubscriptionReward($paymentNumber, $currency);
                $reward = $rewards->get($paymentNumber);

                if (! $reward) {
                    if ($tier !== null && $expected > 0) {
                        $missing[] = [
                            $sub->subscription_number,
                            $paymentNumber,
                            $payment->paid_at?->format('Y-m-d'),
                            $coupon->code,
                            $tier,
                            $expected.' '.$currency,
                        ];
                    }

                    continue;
                }

                // Zrušené odměny řešil admin ručně, ty nekomentujeme
                if ($reward->status === 'cancelled') {
                    continue;
                }

                if (abs((float) $reward->reward_amount - (float) $expected) > 0.01) {
                    $mismatched[] = [
                        $sub->subscription_number,
                        $paymentNumber,
                        $coupon->code,
                        $reward->reward_amount.' '.$reward->currency,
                        $expected.' '.$currency,
                        $reward->reward_tier ?? '—',
                    ];
                }
            }

            // Odměny za platbu, která v ledgeru neexistuje
            $maxPaymentNumber = $payments->count();

            foreach ($rewards as $number => $reward) {
                if ($number > $maxPaymentNumber && $reward->status !== 'cancelled') {
                    $orphaned[] = [
                        $sub->subscription_number,
                        $number,
                        $maxPaymentNumber,
                        $reward->getFormattedAmount(),
                        $reward->status,
                    ];
                }
            }
        }

        $this->reportMissing($missing);
        $this->reportMismatched($mismatched);
        $this->reportOrphaned($orphaned);
        $this->reportShifted($shifted);

        if (empty($missing) && empty($mismatched) && empty($orphaned) && empty($shifted)) {
            $this->info('Vše sedí – žádné chybějící ani nesedící odměny.');

            return self::SUCCESS;
        }

        return self::FAILURE;
    }

    private function reportMissing(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $this->newLine();
        $this->warn('Chybějící odměny ('.count($rows).')');
        $this->line('Zaplacené platby předplatného, ke kterým neexistuje odměna. Typicky ruční úhrada faktury,');
        $this->line('jednorázový box nebo předplatné se 100% slevou. Doplníš přes affiliate:backfill-subscription-rewards.');
        $this->table(['Předplatné', 'Platba #', 'Zaplaceno', 'Kód', 'Sazba', 'Očekávaná odměna'], $rows);
    }

    private function reportMismatched(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $this->newLine();
        $this->warn('Nesedící částky ('.count($rows).')');
        $this->line('Uložená odměna neodpovídá aktuálnímu nastavení kupónu. Může jít o legitimní následek změny sazby,');
        $this->line('nebo o odměnu spočtenou ve špatné měně. Historické odměny se automaticky nepřepočítávají.');
        $this->table(['Předplatné', 'Platba #', 'Kód', 'Uloženo', 'Podle kupónu', 'Sazba'], $rows);
    }

    private function reportOrphaned(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $this->newLine();
        $this->warn('Odměny bez platby ('.count($rows).')');
        $this->line('Odměna má vyšší pořadové číslo, než kolik má předplatné zaplacených plateb.');
        $this->table(['Předplatné', 'Odměna platba #', 'Zaplaceno plateb', 'Částka', 'Stav'], $rows);
    }

    private function reportShifted(array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $this->newLine();
        $this->line('<fg=cyan>Posunuté číslování ('.count($rows).')</>');
        $this->line('Odměn je stejně jako zaplacených plateb, jen mají jiná pořadová čísla – každá rozesílka JE odměněná.');
        $this->line('Nedoplňuj je přes backfill, vznikly by duplicity. Ovlivňuje to jen hranici mezi úvodní a dlouhodobou sazbou.');
        $this->table(['Předplatné', 'Zaplaceno plateb', 'Čísla odměn', 'Kód', 'Vyplaceno celkem'], $rows);
    }
}
