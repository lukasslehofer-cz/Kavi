<?php

namespace App\Services;

use App\Helpers\CurrencyHelper;
use App\Models\AffiliateLink;
use App\Models\AffiliateLinkClick;
use App\Models\AffiliateReward;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
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
    public function calculateOrderReward(Coupon $coupon, float $orderValue): float
    {
        return $coupon->calculateAffiliateOrderReward($orderValue);
    }

    /**
     * Vypočítá odměnu za jedno opakování předplatného
     */
    public function calculateSubscriptionReward(Coupon $coupon): float
    {
        return $coupon->calculateAffiliateSubscriptionReward();
    }

    /**
     * Vytvoří záznam odměny za objednávku
     */
    public function createOrderReward(Order $order, Coupon $coupon): ?AffiliateReward
    {
        if (!$coupon->hasAffiliateOrderReward()) {
            return null;
        }

        // Vypočítej odměnu
        $rewardAmount = $this->calculateOrderReward($coupon, $order->total);

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

        return AffiliateReward::create([
            'affiliate_partner_id' => $coupon->affiliate_partner_id,
            'coupon_id' => $coupon->id,
            'order_id' => $order->id,
            'subscription_id' => null,
            'subscription_payment_number' => null,
            'reward_type' => 'order',
            'reward_amount' => $rewardAmount,
            'currency' => $order->currency ?? CurrencyHelper::stripeCode(),
            'status' => 'pending',
        ]);
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

        // Vypočítej odměnu
        $rewardAmount = $this->calculateSubscriptionReward($coupon);

        if ($rewardAmount <= 0) {
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

        return AffiliateReward::create([
            'affiliate_partner_id' => $coupon->affiliate_partner_id,
            'coupon_id' => $coupon->id,
            'order_id' => null,
            'subscription_id' => $subscription->id,
            'subscription_payment_number' => $paymentNumber,
            'reward_type' => 'subscription',
            'reward_amount' => $rewardAmount,
            'currency' => $subscription->currency ?? CurrencyHelper::stripeCode(),
            'status' => 'pending',
        ]);
    }

    /**
     * Zkontroluje, zda má být vytvořena odměna za platbu předplatného
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

        // Kontrola limitu opakování
        if ($coupon->affiliate_reward_subscription_months !== null) {
            if ($paymentNumber > $coupon->affiliate_reward_subscription_months) {
                return false;
            }
        }

        return true;
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

        $totalEarned = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->sum('reward_amount');

        $pendingAmount = $rewards->get('pending')?->total ?? 0;
        $approvedAmount = $rewards->get('approved')?->total ?? 0;
        $paidAmount = $rewards->get('paid')?->total ?? 0;

        $pendingCount = $rewards->get('pending')?->count ?? 0;
        $approvedCount = $rewards->get('approved')?->count ?? 0;
        $paidCount = $rewards->get('paid')?->count ?? 0;

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

        return [
            'total_clicks' => $totalClicks,
            'total_earned' => $totalEarned,
            'pending_amount' => $pendingAmount,
            'approved_amount' => $approvedAmount,
            'paid_amount' => $paidAmount,
            'pending_count' => $pendingCount,
            'approved_count' => $approvedCount,
            'paid_count' => $paidCount,
            'order_conversions' => $orderConversions,
            'subscription_conversions' => $subscriptionConversions,
            'total_conversions' => $orderConversions + $subscriptionConversions,
        ];
    }

    /**
     * Získá seznam odměn partnera
     */
    public function getPartnerRewards(User $partner, ?string $status = null)
    {
        $query = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->with(['coupon', 'order', 'subscription'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
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
