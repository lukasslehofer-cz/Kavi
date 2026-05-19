<?php

namespace App\Console\Commands;

use App\Models\AffiliateReward;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillAffiliateSubscriptionRewards extends Command
{
    protected $signature = 'affiliate:backfill-subscription-rewards
                            {--dry-run : Show what would be created without writing}
                            {--subscription= : Limit to a single subscription_number}';

    protected $description = 'Vytvoří chybějící AffiliateReward záznamy za již proběhlé platby předplatného (oprava bugu, kdy custom-billing cron neukládal odměny za opakované platby)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $onlyNumber = $this->option('subscription');

        $query = Subscription::whereNotNull('coupon_id')->with('coupon');
        if ($onlyNumber) {
            $query->where('subscription_number', $onlyNumber);
        }
        $subscriptions = $query->get();

        $planned = [];

        foreach ($subscriptions as $sub) {
            $coupon = $sub->coupon;
            if (! $coupon || ! $coupon->hasAffiliateSubscriptionReward()) {
                continue;
            }

            $limit = $coupon->affiliate_reward_subscription_months;
            $payments = SubscriptionPayment::where('subscription_id', $sub->id)
                ->where('status', 'paid')
                ->orderBy('paid_at')
                ->orderBy('id')
                ->get();

            $existing = AffiliateReward::where('subscription_id', $sub->id)
                ->whereNotNull('subscription_payment_number')
                ->pluck('subscription_payment_number')
                ->all();

            foreach ($payments as $i => $payment) {
                $paymentNumber = $i + 1;

                if ($limit !== null && $paymentNumber > $limit) {
                    break;
                }
                if (in_array($paymentNumber, $existing, true)) {
                    continue;
                }

                $planned[] = [
                    'subscription' => $sub,
                    'coupon' => $coupon,
                    'payment' => $payment,
                    'payment_number' => $paymentNumber,
                    'reward_amount' => $coupon->calculateAffiliateSubscriptionReward(),
                ];
            }
        }

        if (empty($planned)) {
            $this->info('Nic k dopočítání — žádné chybějící odměny.');
            return 0;
        }

        $this->table(
            ['Subscription', 'Payment #', 'Paid at', 'Partner ID', 'Coupon', 'Reward', 'Currency'],
            array_map(fn ($p) => [
                $p['subscription']->subscription_number,
                $p['payment_number'],
                $p['payment']->paid_at?->toDateTimeString(),
                $p['coupon']->affiliate_partner_id,
                $p['coupon']->code,
                $p['reward_amount'],
                strtoupper($p['subscription']->currency ?? 'CZK'),
            ], $planned)
        );

        $this->info('Celkem k vytvoření: '.count($planned));

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
                DB::transaction(function () use ($p, &$created) {
                    $reward = AffiliateReward::create([
                        'affiliate_partner_id' => $p['coupon']->affiliate_partner_id,
                        'coupon_id' => $p['coupon']->id,
                        'order_id' => null,
                        'subscription_id' => $p['subscription']->id,
                        'subscription_payment_number' => $p['payment_number'],
                        'reward_type' => 'subscription',
                        'reward_amount' => $p['reward_amount'],
                        'currency' => $p['subscription']->currency ?? 'czk',
                        'status' => 'pending',
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
