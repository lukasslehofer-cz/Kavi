<?php

namespace App\Console\Commands;

use App\Models\AffiliateReward;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillAffiliateSubscriptionRewards extends Command
{
    protected $signature = 'affiliate:backfill-subscription-rewards
                            {--dry-run : Show what would be created without writing}
                            {--subscription= : Limit to a single subscription_number}
                            {--coupon= : Limit to a single coupon code}
                            {--from= : Only payments paid on/after this date (YYYY-MM-DD)}
                            {--to= : Only payments paid on/before this date (YYYY-MM-DD)}
                            {--status=pending : pending | approved | paid – použij paid pro odměny, které partner už dostal vyplacené mimo systém}';

    protected $description = 'Vytvoří chybějící AffiliateReward záznamy za již proběhlé platby předplatného (oprava bugu, kdy custom-billing cron neukládal odměny za opakované platby)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $onlyNumber = $this->option('subscription');
        $onlyCoupon = $this->option('coupon');
        $status = $this->option('status');

        if (! in_array($status, ['pending', 'approved', 'paid'], true)) {
            $this->error('Neplatný --status, povolené hodnoty: pending, approved, paid.');

            return 1;
        }

        if ($status === 'paid') {
            $this->warn('Odměny se zapíšou rovnou jako VYPLACENÉ – použij jen pro to, co partner dostal mimo systém.');
        }

        try {
            $from = $this->option('from') ? Carbon::parse($this->option('from'))->startOfDay() : null;
            $to = $this->option('to') ? Carbon::parse($this->option('to'))->endOfDay() : null;
        } catch (\Exception $e) {
            $this->error('Neplatné datum – použij formát YYYY-MM-DD.');

            return 1;
        }

        if ($from || $to) {
            $this->info(sprintf(
                'Omezeno na platby %s%s',
                $from ? 'od '.$from->format('d.m.Y').' ' : '',
                $to ? 'do '.$to->format('d.m.Y') : ''
            ));
            $this->line('Pořadová čísla plateb se počítají z celé historie, filtr omezuje jen vytváření odměn.');
        }

        $query = Subscription::whereNotNull('coupon_id')->with('coupon');
        if ($onlyNumber) {
            $query->where('subscription_number', $onlyNumber);
        }
        if ($onlyCoupon) {
            $query->whereHas('coupon', fn ($q) => $q->where('code', strtoupper($onlyCoupon)));
        }
        $subscriptions = $query->get();

        $planned = [];
        $skippedShifted = [];
        $skippedByDate = 0;

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

            $existingRewards = AffiliateReward::where('subscription_id', $sub->id)
                ->whereNotNull('subscription_payment_number')
                ->where('status', '!=', 'cancelled')
                ->get();

            $existing = $existingRewards->pluck('subscription_payment_number')->sort()->values()->all();

            // Pojistka proti dvojímu vyplacení: pořadová čísla odměn se odvozují
            // z počtu zaplacených plateb a umí se posunout (viz affiliate:audit,
            // sekce "Posunuté číslování"). Doplňovat je bezpečné jen tehdy, když
            // existující odměny tvoří souvislou řadu od jedničky – jinak nelze
            // spolehlivě určit, která rozesílka odměnu ještě nemá.
            if ($existing !== [] && $existing !== range(1, count($existing))) {
                $skippedShifted[] = [
                    $sub->subscription_number,
                    $payments->count(),
                    implode(', ', $existing),
                ];

                continue;
            }

            foreach ($payments as $i => $payment) {
                $paymentNumber = $i + 1;

                if (in_array($paymentNumber, $existing, true)) {
                    continue;
                }

                // O výši i o tom, zda odměna vůbec náleží, rozhoduje kupón
                // (úvodní odměna / navazující dlouhodobá odměna / nic).
                $tier = $coupon->affiliateSubscriptionTierFor($paymentNumber);

                if ($tier === null) {
                    continue;
                }

                $amount = $coupon->calculateAffiliateSubscriptionReward($paymentNumber, $currency);

                if ($amount <= 0) {
                    continue;
                }

                // Datový filtr až tady – pořadové číslo už je spočítané z celé
                // historie, takže se filtrem neposune hranice mezi úvodní a dlouhodobou odměnou.
                $paidAt = $payment->paid_at;

                if (($from && (! $paidAt || $paidAt->lt($from))) || ($to && (! $paidAt || $paidAt->gt($to)))) {
                    $skippedByDate++;

                    continue;
                }

                $planned[] = [
                    'subscription' => $sub,
                    'coupon' => $coupon,
                    'payment' => $payment,
                    'payment_number' => $paymentNumber,
                    'tier' => $tier,
                    'currency' => $currency,
                    'reward_amount' => $amount,
                ];
            }
        }

        if ($skippedShifted) {
            $this->newLine();
            $this->line('<fg=cyan>Přeskočeno kvůli nesouvislému číslování ('.count($skippedShifted).')</>');
            $this->line('Odměny netvoří souvislou řadu od jedničky, takže nelze bezpečně určit, která rozesílka odměnu nemá.');
            $this->line('Řeš ručně – detail najdeš v affiliate:audit.');
            $this->table(['Předplatné', 'Zaplaceno plateb', 'Čísla odměn'], $skippedShifted);
        }

        if ($skippedByDate > 0) {
            $this->newLine();
            $this->line("<fg=cyan>Mimo zadané datové rozmezí: {$skippedByDate} plateb (odměna se nevytvoří)</>");
        }

        if (empty($planned)) {
            $this->info('Nic k dopočítání — žádné chybějící odměny.');
            return 0;
        }

        $this->table(
            ['Předplatné', 'Platba #', 'Zaplaceno', 'Partner', 'Kód', 'Typ odměny', 'Částka', 'Měna'],
            array_map(fn ($p) => [
                $p['subscription']->subscription_number,
                $p['payment_number'],
                $p['payment']->paid_at?->toDateTimeString(),
                $p['coupon']->affiliate_partner_id,
                $p['coupon']->code,
                $p['tier'],
                $p['reward_amount'],
                $p['currency'],
            ], $planned)
        );

        $this->info('Celkem k vytvoření: '.count($planned).' (stav: '.$status.')');

        if ($dryRun) {
            $this->warn('Dry-run — nic se nezapsalo.');
            return 0;
        }

        if (! $this->confirm('Vytvořit tyto odměny?', false)) {
            $this->warn('Zrušeno uživatelem.');
            return 0;
        }

        $created = 0;
        $failed = 0;

        foreach ($planned as $p) {
            try {
                DB::transaction(function () use ($p, $status, &$created) {
                    $reward = AffiliateReward::create([
                        'affiliate_partner_id' => $p['coupon']->affiliate_partner_id,
                        'coupon_id' => $p['coupon']->id,
                        'order_id' => null,
                        'subscription_id' => $p['subscription']->id,
                        'subscription_payment_number' => $p['payment_number'],
                        'reward_type' => 'subscription',
                        'reward_tier' => $p['tier'],
                        'reward_amount' => $p['reward_amount'],
                        'currency' => $p['currency'],
                        'status' => $status,
                        // U vyplacených dozpětně datuj i výplatu, ať sedí historie
                        'paid_at' => $status === 'paid' ? $p['payment']->paid_at : null,
                        'notes' => $status === 'paid' ? 'Doplněno zpětně – vyplaceno mimo systém' : null,
                    ]);

                    // Backfill created_at na datum platby, aby admin UI ukazoval historicky správné datum
                    $reward->forceFill([
                        'created_at' => $p['payment']->paid_at,
                        'updated_at' => $p['payment']->paid_at,
                    ])->save();

                    \Log::info('Affiliate reward backfilled', [
                        'subscription_id' => $p['subscription']->id,
                        'payment_number' => $p['payment_number'],
                        'reward_id' => $reward->id,
                        'reward_amount' => $reward->reward_amount,
                        'partner_id' => $reward->affiliate_partner_id,
                    ]);

                    $created++;
                });
            } catch (\Exception $e) {
                $failed++;
                $this->error(sprintf(
                    'Selhalo: %s #%d — %s',
                    $p['subscription']->subscription_number,
                    $p['payment_number'],
                    $e->getMessage()
                ));
            }
        }

        $this->info("Hotovo. Vytvořeno: {$created}, selhalo: {$failed}.");

        return $failed === 0 ? 0 : 1;
    }
}
