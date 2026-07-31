<?php

namespace App\Services;

use App\Helpers\CurrencyHelper;
use App\Mail\AffiliateCodeUsed;
use App\Mail\AffiliatePayoutThresholdReached;
use App\Models\AffiliateLink;
use App\Models\AffiliateLinkClick;
use App\Models\AffiliateReward;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AffiliateService
{
    /**
     * Zaznamená kliknutí na affiliate link
     */
    public function trackLinkClick(AffiliateLink $link, Request $request): AffiliateLinkClick
    {
        // Inkrementuj počítadlo
        $link->incrementClicks();

        // Vytvoř záznam kliknutí
        return AffiliateLinkClick::create([
            'affiliate_link_id' => $link->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'session_id' => session()->getId(),
            'clicked_at' => now(),
        ]);
    }

    /**
     * Uloží affiliate kód do session
     */
    public function storeAffiliateCodeInSession(string $code): void
    {
        session(['affiliate_code' => strtoupper($code)]);
    }

    /**
     * Získá affiliate kód ze session
     */
    public function getAffiliateCodeFromSession(): ?string
    {
        return session('affiliate_code');
    }

    /**
     * Vymaže affiliate kód ze session
     */
    public function clearAffiliateCode(): void
    {
        session()->forget('affiliate_code');
    }

    /**
     * Vypočítá odměnu za objednávku
     */
    public function calculateOrderReward(Coupon $coupon, float $orderValue, ?string $currency = null): float
    {
        return $coupon->calculateAffiliateOrderReward($orderValue, $currency);
    }

    /**
     * Vypočítá odměnu za konkrétní opakování předplatného
     */
    public function calculateSubscriptionReward(Coupon $coupon, int $paymentNumber = 1, ?string $currency = null): float
    {
        return $coupon->calculateAffiliateSubscriptionReward($paymentNumber, $currency);
    }

    /**
     * Vytvoří záznam odměny za objednávku
     */
    public function createOrderReward(Order $order, Coupon $coupon): ?AffiliateReward
    {
        if (!$coupon->hasAffiliateOrderReward()) {
            return null;
        }

        // Měnu ber z objednávky – tato metoda běží ve webhooku, kde session neexistuje
        $currency = $order->currency ?? CurrencyHelper::stripeCode();

        // Vypočítej odměnu
        $rewardAmount = $this->calculateOrderReward($coupon, $order->total, $currency);

        if ($rewardAmount <= 0) {
            return null;
        }

        // Kontrola duplicity
        $existing = AffiliateReward::where('order_id', $order->id)
            ->where('coupon_id', $coupon->id)
            ->first();

        if ($existing) {
            \Log::info('Affiliate reward already exists for order', [
                'order_id' => $order->id,
                'coupon_id' => $coupon->id,
                'existing_reward_id' => $existing->id,
            ]);
            return $existing;
        }

        $reward = AffiliateReward::create([
            'affiliate_partner_id' => $coupon->affiliate_partner_id,
            'coupon_id' => $coupon->id,
            'order_id' => $order->id,
            'subscription_id' => null,
            'subscription_payment_number' => null,
            'reward_type' => 'order',
            'reward_tier' => 'order',
            'reward_amount' => $rewardAmount,
            'currency' => $currency,
            'status' => 'pending',
        ]);

        $this->notifyRewardCreated($reward);

        return $reward;
    }

    /**
     * Vytvoří záznam odměny za platbu předplatného
     */
    public function createSubscriptionReward(
        Subscription $subscription,
        Coupon $coupon,
        int $paymentNumber
    ): ?AffiliateReward {
        if (!$coupon->hasAffiliateSubscriptionReward()) {
            return null;
        }

        // Kontrola, zda má být vytvořena odměna
        if (!$this->shouldCreateSubscriptionReward($subscription, $coupon, $paymentNumber)) {
            return null;
        }

        // Měnu ber z předplatného – tato metoda běží v cronu/webhooku, kde session neexistuje
        $currency = $subscription->currency ?? CurrencyHelper::stripeCode();

        // Urči sazbu (initial / followup) a vypočítej odměnu
        $tier = $coupon->affiliateSubscriptionTierFor($paymentNumber);
        $rewardAmount = $this->calculateSubscriptionReward($coupon, $paymentNumber, $currency);

        if ($tier === null || $rewardAmount <= 0) {
            return null;
        }

        // Kontrola duplicity
        $existing = AffiliateReward::where('subscription_id', $subscription->id)
            ->where('subscription_payment_number', $paymentNumber)
            ->first();

        if ($existing) {
            \Log::info('Affiliate reward already exists for subscription payment', [
                'subscription_id' => $subscription->id,
                'payment_number' => $paymentNumber,
                'existing_reward_id' => $existing->id,
            ]);
            return $existing;
        }

        $reward = AffiliateReward::create([
            'affiliate_partner_id' => $coupon->affiliate_partner_id,
            'coupon_id' => $coupon->id,
            'order_id' => null,
            'subscription_id' => $subscription->id,
            'subscription_payment_number' => $paymentNumber,
            'reward_type' => 'subscription',
            'reward_tier' => $tier,
            'reward_amount' => $rewardAmount,
            'currency' => $currency,
            'status' => 'pending',
        ]);

        $this->notifyRewardCreated($reward);

        return $reward;
    }

    /**
     * Zkontroluje, zda má být vytvořena odměna za platbu předplatného
     *
     * O výši a případné nulovosti odměny rozhoduje sazba (tier) na kupónu –
     * viz Coupon::affiliateSubscriptionTierFor(). Tady zůstává jen guard na stav
     * předplatného.
     */
    public function shouldCreateSubscriptionReward(
        Subscription $subscription,
        Coupon $coupon,
        int $paymentNumber
    ): bool {
        // Předplatné nesmí být zrušené
        if ($subscription->status === 'cancelled' || $subscription->status === 'ended') {
            return false;
        }

        return $coupon->affiliateSubscriptionTierFor($paymentNumber) !== null;
    }

    /**
     * Získá statistiky partnera
     */
    public function getPartnerStatistics(User $partner): array
    {
        // Celkový počet kliknutí
        $totalClicks = DB::table('affiliate_link_clicks')
            ->join('affiliate_links', 'affiliate_link_clicks.affiliate_link_id', '=', 'affiliate_links.id')
            ->where('affiliate_links.affiliate_partner_id', $partner->id)
            ->count();

        // Odměny podle statusu
        $rewards = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->select('status', DB::raw('SUM(reward_amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $pendingAmount = (float) ($rewards->get('pending')?->total ?? 0);
        $approvedAmount = (float) ($rewards->get('approved')?->total ?? 0);
        $paidAmount = (float) ($rewards->get('paid')?->total ?? 0);
        $cancelledAmount = (float) ($rewards->get('cancelled')?->total ?? 0);

        $pendingCount = (int) ($rewards->get('pending')?->count ?? 0);
        $approvedCount = (int) ($rewards->get('approved')?->count ?? 0);
        $paidCount = (int) ($rewards->get('paid')?->count ?? 0);

        // Zrušené odměny se do výdělku nepočítají
        $totalEarned = $pendingAmount + $approvedAmount + $paidAmount;

        // Částka, kterou partner ještě neobdržel
        $payableAmount = $pendingAmount + $approvedAmount;

        // Počet konverzí (unikátní objednávky a předplatná)
        $orderConversions = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->where('reward_type', 'order')
            ->distinct('order_id')
            ->count('order_id');

        $subscriptionConversions = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->where('reward_type', 'subscription')
            ->where('subscription_payment_number', 1) // Pouze první platby
            ->distinct('subscription_id')
            ->count('subscription_id');

        $totalConversions = $orderConversions + $subscriptionConversions;

        // Výdělek v aktuálním měsíci
        $thisMonthEarned = (float) AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->counted()
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('reward_amount');

        // Aktivní doporučená předplatná a odhad měsíčního příjmu z nich
        $active = $this->getPartnerSubscriptions($partner)->where('is_active', true);

        $threshold = $this->getPayoutThreshold($partner);

        return [
            'total_clicks' => $totalClicks,
            'total_earned' => $totalEarned,
            'pending_amount' => $pendingAmount,
            'approved_amount' => $approvedAmount,
            'paid_amount' => $paidAmount,
            'cancelled_amount' => $cancelledAmount,
            'payable_amount' => $payableAmount,
            'pending_count' => $pendingCount,
            'approved_count' => $approvedCount,
            'paid_count' => $paidCount,
            'order_conversions' => $orderConversions,
            'subscription_conversions' => $subscriptionConversions,
            'total_conversions' => $totalConversions,
            // null = poměr nedává smysl (kód se používá ručně, bez prokliku odkazem)
            'conversion_rate' => $totalClicks > 0 ? round($totalConversions / $totalClicks * 100, 1) : null,
            'this_month_earned' => $thisMonthEarned,
            'active_subscriptions_count' => $active->count(),
            'estimated_monthly_income' => (float) $active->sum('current_rate'),
            'payout_threshold' => $threshold['amount'],
            'payout_currency' => $threshold['currency'],
            'threshold_reached' => $payableAmount >= $threshold['amount'],
            'threshold_progress' => $threshold['amount'] > 0
                ? min(100, round($payableAmount / $threshold['amount'] * 100, 1))
                : 100.0,
        ];
    }

    /**
     * Vývoj výdělku a kliknutí po měsících (nejstarší první)
     *
     * @return array<int, array{month: string, label: string, earned: float, rewards_count: int, clicks: int}>
     */
    public function getMonthlyBreakdown(User $partner, int $months = 12): array
    {
        $from = now()->startOfMonth()->subMonths($months - 1);

        $rewards = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->counted()
            ->where('created_at', '>=', $from)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(reward_amount) as earned, COUNT(*) as cnt")
            ->groupBy('ym')
            ->get()
            ->keyBy('ym');

        $clicks = DB::table('affiliate_link_clicks')
            ->join('affiliate_links', 'affiliate_link_clicks.affiliate_link_id', '=', 'affiliate_links.id')
            ->where('affiliate_links.affiliate_partner_id', $partner->id)
            ->where('affiliate_link_clicks.clicked_at', '>=', $from)
            ->selectRaw("DATE_FORMAT(affiliate_link_clicks.clicked_at, '%Y-%m') as ym, COUNT(*) as cnt")
            ->groupBy('ym')
            ->get()
            ->keyBy('ym');

        $result = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $from->copy()->addMonths($i);
            $key = $month->format('Y-m');

            $result[] = [
                'month' => $key,
                'label' => $month->format('n/y'),
                'earned' => (float) ($rewards->get($key)?->earned ?? 0),
                'rewards_count' => (int) ($rewards->get($key)?->cnt ?? 0),
                'clicks' => (int) ($clicks->get($key)?->cnt ?? 0),
            ];
        }

        return $result;
    }

    /**
     * Přehled předplatných, která partnerovi vydělávají
     *
     * Neobsahuje žádné osobní údaje zákazníka – jen číslo předplatného.
     * Admin si jméno dohledá přes detail předplatného.
     */
    public function getPartnerSubscriptions(User $partner): Collection
    {
        $rows = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->where('reward_type', 'subscription')
            ->counted()
            ->whereNotNull('subscription_id')
            ->selectRaw('subscription_id, coupon_id, COUNT(*) as rewarded_payments, SUM(reward_amount) as total_earned, MAX(subscription_payment_number) as last_payment_number, MIN(created_at) as first_reward_at, MAX(created_at) as last_reward_at, MAX(currency) as currency')
            ->groupBy('subscription_id', 'coupon_id')
            ->get();

        if ($rows->isEmpty()) {
            return collect();
        }

        $subscriptions = Subscription::whereIn('id', $rows->pluck('subscription_id'))
            ->get()
            ->keyBy('id');

        $coupons = Coupon::whereIn('id', $rows->pluck('coupon_id')->unique())
            ->get()
            ->keyBy('id');

        return $rows->map(function ($row) use ($subscriptions, $coupons) {
            $subscription = $subscriptions->get($row->subscription_id);
            $coupon = $coupons->get($row->coupon_id);

            if (! $subscription || ! $coupon) {
                return null;
            }

            $currency = $row->currency ?: ($subscription->currency ?? 'CZK');
            $nextPaymentNumber = ((int) $row->last_payment_number) + 1;

            $isActive = ! in_array($subscription->status, ['cancelled', 'ended'], true);
            $nextTier = $isActive ? $coupon->affiliateSubscriptionTierFor($nextPaymentNumber) : null;
            $nextRate = $isActive ? $coupon->calculateAffiliateSubscriptionReward($nextPaymentNumber, $currency) : 0.0;

            $limit = $coupon->affiliate_reward_subscription_months;
            $remainingInitial = $limit !== null
                ? max(0, $limit - (int) $row->last_payment_number)
                : null;

            return (object) [
                'subscription_id' => $subscription->id,
                'subscription_number' => $subscription->subscription_number,
                'status' => $subscription->status,
                'is_active' => $isActive,
                'currency' => $currency,
                'started_at' => $subscription->starts_at ?? $subscription->created_at,
                'coupon_code' => $coupon->code,
                'coupon' => $coupon,
                'rewarded_payments' => (int) $row->rewarded_payments,
                'last_payment_number' => (int) $row->last_payment_number,
                'total_earned' => (float) $row->total_earned,
                'first_reward_at' => $row->first_reward_at,
                'last_reward_at' => $row->last_reward_at,
                'current_tier' => $nextTier,
                'current_rate' => (float) $nextRate,
                'remaining_initial' => $remainingInitial,
                'next_reward_date' => $isActive && $nextTier ? $subscription->next_billing_date : null,
                'reward_scheme' => $coupon->getAffiliateSubscriptionRewardDescription($currency),
            ];
        })->filter()->sortByDesc('total_earned')->values();
    }

    /**
     * Výkon jednotlivých affiliate kódů partnera
     */
    public function getPartnerCodePerformance(User $partner): Collection
    {
        $coupons = $partner->affiliateCoupons()->with('affiliateLinks')->get();

        if ($coupons->isEmpty()) {
            return collect();
        }

        $rewardStats = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->counted()
            ->whereIn('coupon_id', $coupons->pluck('id'))
            ->selectRaw("coupon_id, SUM(reward_amount) as earned, COUNT(*) as rewards_count, COUNT(DISTINCT order_id) as order_conversions, COUNT(DISTINCT CASE WHEN subscription_payment_number = 1 THEN subscription_id END) as subscription_conversions")
            ->groupBy('coupon_id')
            ->get()
            ->keyBy('coupon_id');

        return $coupons->map(function (Coupon $coupon) use ($rewardStats) {
            $stats = $rewardStats->get($coupon->id);
            $clicks = (int) $coupon->affiliateLinks->sum('clicks_count');
            $conversions = (int) ($stats->order_conversions ?? 0) + (int) ($stats->subscription_conversions ?? 0);

            return (object) [
                'coupon' => $coupon,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'is_active' => (bool) $coupon->is_active && (bool) $coupon->affiliate_code_enabled,
                'clicks' => $clicks,
                'order_conversions' => (int) ($stats->order_conversions ?? 0),
                'subscription_conversions' => (int) ($stats->subscription_conversions ?? 0),
                'conversions' => $conversions,
                'rewards_count' => (int) ($stats->rewards_count ?? 0),
                'earned' => (float) ($stats->earned ?? 0),
                'conversion_rate' => $clicks > 0 ? round($conversions / $clicks * 100, 1) : null,
            ];
        })->sortByDesc('earned')->values();
    }

    /**
     * Souhrn pro admin výpis partnerů – jedna agregovaná query místo N dotazů na partnera
     *
     * @return Collection klíčovaná user_id
     */
    public function getPartnersOverview(array $partnerIds): Collection
    {
        if (empty($partnerIds)) {
            return collect();
        }

        $rewards = AffiliateReward::whereIn('affiliate_partner_id', $partnerIds)
            ->selectRaw("affiliate_partner_id, SUM(CASE WHEN status != 'cancelled' THEN reward_amount ELSE 0 END) as total_earned, SUM(CASE WHEN status IN ('pending','approved') THEN reward_amount ELSE 0 END) as payable_amount, SUM(CASE WHEN status = 'paid' THEN reward_amount ELSE 0 END) as paid_amount, COUNT(*) as rewards_count")
            ->groupBy('affiliate_partner_id')
            ->get()
            ->keyBy('affiliate_partner_id');

        $clicks = DB::table('affiliate_links')
            ->whereIn('affiliate_partner_id', $partnerIds)
            ->selectRaw('affiliate_partner_id, SUM(clicks_count) as clicks')
            ->groupBy('affiliate_partner_id')
            ->get()
            ->keyBy('affiliate_partner_id');

        return collect($partnerIds)->mapWithKeys(fn ($id) => [$id => (object) [
            'total_earned' => (float) ($rewards->get($id)?->total_earned ?? 0),
            'payable_amount' => (float) ($rewards->get($id)?->payable_amount ?? 0),
            'paid_amount' => (float) ($rewards->get($id)?->paid_amount ?? 0),
            'rewards_count' => (int) ($rewards->get($id)?->rewards_count ?? 0),
            'clicks' => (int) ($clicks->get($id)?->clicks ?? 0),
        ]]);
    }

    /**
     * Měna, ve které partner dostává odměny (podle poslední odměny)
     */
    public function getPartnerCurrency(User $partner): string
    {
        $currency = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->orderByDesc('created_at')
            ->value('currency');

        // Historické řádky mají měnu malými písmeny, normalizuj
        return $currency ? strtoupper($currency) : 'CZK';
    }

    /**
     * Fakturační hranice partnera (vlastní hodnota, jinak výchozí z configu)
     *
     * @return array{amount: float, currency: string, is_custom: bool}
     */
    public function getPayoutThreshold(User $partner): array
    {
        $currency = $this->getPartnerCurrency($partner);
        $custom = $partner->affiliate_payout_threshold;

        return [
            'amount' => $custom !== null
                ? (float) $custom
                : (float) (config('affiliate.payout_threshold.'.$currency) ?? config('affiliate.payout_threshold.CZK', 1000)),
            'currency' => $currency,
            'is_custom' => $custom !== null,
        ];
    }

    /**
     * Aktuální zůstatek partnera k výplatě (nezrušené a nevyplacené odměny)
     *
     * @return array{amount: float, currency: string, threshold: float, reached: bool}
     */
    public function getPayoutBalance(User $partner): array
    {
        $threshold = $this->getPayoutThreshold($partner);

        $amount = (float) AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->payable()
            ->sum('reward_amount');

        return [
            'amount' => $amount,
            'currency' => $threshold['currency'],
            'threshold' => $threshold['amount'],
            'reached' => $amount >= $threshold['amount'],
        ];
    }

    /**
     * Získá seznam odměn partnera
     *
     * @param  int|null  $perPage  null = vrátí kolekci, jinak stránkovaný výsledek
     */
    public function getPartnerRewards(User $partner, ?string $status = null, ?int $perPage = null)
    {
        $query = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->with(['coupon', 'order', 'subscription'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $perPage ? $query->paginate($perPage)->withQueryString() : $query->get();
    }

    /**
     * Pošle partnerovi maily navázané na právě vytvořenou odměnu
     *
     * Mail o použití kódu chodí jen u první odměny z dané konverze – za každou
     * navazující rozesílku už ne, ty pokrývá měsíční souhrn.
     */
    protected function notifyRewardCreated(AffiliateReward $reward): void
    {
        // Odměna už je uložená – žádná chyba v notifikaci nesmí shodit webhook ani cron
        try {
            $partner = $reward->affiliatePartner;

            if (! $partner || ! $partner->email) {
                return;
            }

            $isFirstReward = $reward->reward_type === 'order'
                || $reward->subscription_payment_number === 1;

            if ($isFirstReward && config('affiliate.emails.code_used', true)) {
                $reward->loadMissing(['coupon', 'order', 'subscription']);

                \Mail::to($partner->email)->send(new AffiliateCodeUsed($partner, $reward));
            }

            $this->maybeNotifyPayoutThreshold($partner);
        } catch (\Throwable $e) {
            \Log::error('Failed to notify affiliate partner about reward', [
                'reward_id' => $reward->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Pošle mail o dosažení fakturační hranice, pokud na něj partner právě dosáhl
     *
     * Razítko affiliate_threshold_notified_at brání opakovanému odeslání; ruší se
     * v resetPayoutThresholdNotice() po výplatě odměn.
     */
    public function maybeNotifyPayoutThreshold(User $partner): bool
    {
        if (! config('affiliate.emails.payout_threshold', true)) {
            return false;
        }

        if ($partner->affiliate_threshold_notified_at !== null) {
            return false;
        }

        $balance = $this->getPayoutBalance($partner);

        if (! $balance['reached'] || ! $partner->email) {
            return false;
        }

        try {
            \Mail::to($partner->email)->send(new AffiliatePayoutThresholdReached(
                $partner,
                $balance['amount'],
                $balance['currency'],
                $balance['threshold']
            ));

            $partner->forceFill(['affiliate_threshold_notified_at' => now()])->save();

            \Log::info('Affiliate payout threshold email sent', [
                'partner_id' => $partner->id,
                'amount' => $balance['amount'],
                'threshold' => $balance['threshold'],
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send affiliate payout threshold email', [
                'partner_id' => $partner->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Vynuluje razítko upozornění na hranici, jakmile zůstatek klesne pod ni
     *
     * Volá se po vyplacení odměn, aby partner dostal mail znovu, až hranici
     * překročí příště.
     */
    public function resetPayoutThresholdNotice(User $partner): void
    {
        if ($partner->affiliate_threshold_notified_at === null) {
            return;
        }

        if (! $this->getPayoutBalance($partner)['reached']) {
            $partner->forceFill(['affiliate_threshold_notified_at' => null])->save();
        }
    }

    /**
     * Vytvoří affiliate link pro kupón
     */
    public function createAffiliateLink(Coupon $coupon, ?string $customSlug = null): AffiliateLink
    {
        $slug = $customSlug 
            ? AffiliateLink::generateUniqueSlug($customSlug)
            : AffiliateLink::generateUniqueSlug(strtolower($coupon->code));

        return AffiliateLink::create([
            'affiliate_partner_id' => $coupon->affiliate_partner_id,
            'coupon_id' => $coupon->id,
            'slug' => $slug,
            'clicks_count' => 0,
            'is_active' => true,
        ]);
    }

    /**
     * Získá nebo vytvoří affiliate link pro kupón
     */
    public function getOrCreateAffiliateLink(Coupon $coupon): AffiliateLink
    {
        $link = AffiliateLink::where('coupon_id', $coupon->id)
            ->where('is_active', true)
            ->first();

        if (!$link) {
            $link = $this->createAffiliateLink($coupon);
        }

        return $link;
    }
}
