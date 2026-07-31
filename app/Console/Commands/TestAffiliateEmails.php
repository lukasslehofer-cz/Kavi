<?php

namespace App\Console\Commands;

use App\Mail\AffiliateAdminMessage;
use App\Mail\AffiliateCodeUsed;
use App\Mail\AffiliateMonthlySummary;
use App\Mail\AffiliatePayoutThresholdReached;
use App\Models\AffiliateReward;
use App\Models\Coupon;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestAffiliateEmails extends Command
{
    protected $signature = 'email:test-affiliate {email}
                            {--type=all : code-used | monthly-summary | payout-threshold | admin-message | all}
                            {--partner= : ID partnera, jehož data se použijí}
                            {--locale= : cs nebo en (výchozí: podle partnera)}';

    protected $description = 'Odešle testovací affiliate e-maily';

    public function handle(AffiliateService $affiliateService): int
    {
        $email = $this->argument('email');
        $type = $this->option('type');
        $locale = $this->option('locale');

        $partner = $this->resolvePartner();

        if (! $partner) {
            $this->error('Nenašel jsem žádného affiliate partnera. Vytvoř aspoň jednoho, nebo použij --partner=ID.');

            return self::FAILURE;
        }

        $this->info("Partner: {$partner->name} <{$partner->email}>");

        $types = $type === 'all'
            ? ['code-used', 'monthly-summary', 'payout-threshold', 'admin-message']
            : [$type];

        foreach ($types as $current) {
            try {
                $mailable = $this->buildMailable($current, $partner, $affiliateService, $locale);

                if (! $mailable) {
                    $this->error("  {$current}: neznámý typ");

                    continue;
                }

                Mail::to($email)->send($mailable);
                $this->info("  {$current}: odesláno na {$email}");
            } catch (\Exception $e) {
                $this->error("  {$current}: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }

    protected function resolvePartner(): ?User
    {
        if ($id = $this->option('partner')) {
            return User::find($id);
        }

        return User::where('is_affiliate_partner', true)->orderBy('id')->first();
    }

    protected function buildMailable(string $type, User $partner, AffiliateService $affiliateService, ?string $locale)
    {
        $balance = $affiliateService->getPayoutBalance($partner);

        return match ($type) {
            'code-used' => new AffiliateCodeUsed($partner, $this->resolveReward($partner), $locale),
            'monthly-summary' => new AffiliateMonthlySummary(
                $partner,
                now()->subMonthNoOverflow()->startOfMonth(),
                [
                    'earned' => 450.0,
                    'rewards_count' => 5,
                    'new_conversions' => 2,
                    'clicks' => 37,
                    'currency' => $balance['currency'],
                    'payable_amount' => max($balance['amount'], 850.0),
                    'threshold' => $balance['threshold'],
                    'threshold_reached' => false,
                    'active_subscriptions' => 3,
                    'estimated_monthly_income' => 150.0,
                ],
                $locale
            ),
            'payout-threshold' => new AffiliatePayoutThresholdReached(
                $partner,
                max($balance['amount'], $balance['threshold']),
                $balance['currency'],
                $balance['threshold'],
                $locale
            ),
            'admin-message' => new AffiliateAdminMessage(
                $partner,
                'Novinky v Kavi affiliate programu',
                "Ahoj,\n\nod tohoto měsíce ti u vybraných kódů běží dlouhodobá odměna – dostaneš ji za každou rozesílku až do konce předplatného.\n\nDetailní přehled najdeš ve své affiliate sekci.",
                $locale
            ),
            default => null,
        };
    }

    /**
     * Skutečná odměna partnera, jinak nepersistovaná ukázka
     */
    protected function resolveReward(User $partner): AffiliateReward
    {
        $reward = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->with('coupon')
            ->latest()
            ->first();

        if ($reward) {
            return $reward;
        }

        $coupon = $partner->affiliateCoupons()->first()
            ?? Coupon::where('affiliate_code_enabled', true)->first();

        $preview = new AffiliateReward([
            'affiliate_partner_id' => $partner->id,
            'coupon_id' => $coupon?->id,
            'subscription_payment_number' => 1,
            'reward_type' => 'subscription',
            'reward_tier' => 'initial',
            'reward_amount' => 200,
            'currency' => 'CZK',
            'status' => 'pending',
        ]);
        $preview->created_at = now();
        $preview->setRelation('coupon', $coupon);
        $preview->setRelation('affiliatePartner', $partner);

        return $preview;
    }
}
