<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateReward;
use App\Models\Subscription;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Http\Request;

class AffiliatePartnerController extends Controller
{
    public function __construct(
        private AffiliateService $affiliateService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Zobrazí seznam affiliate partnerů
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $partners = User::where('is_affiliate_partner', true)
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->with('affiliateCoupons')
            ->withCount('affiliateRewards')
            ->orderBy('affiliate_activated_at', 'desc')
            ->paginate(20);

        // Souhrn v jedné agregované query místo N dotazů na partnera
        $overview = $this->affiliateService->getPartnersOverview($partners->pluck('id')->all());

        foreach ($partners as $partner) {
            $partner->overview = $overview->get($partner->id);
        }

        return view('admin.affiliate.partners.index', compact('partners', 'search'));
    }

    /**
     * Detail partnera – statistiky, předplatná, kódy a odměny
     */
    public function show(Request $request, User $user)
    {
        abort_unless($user->isAffiliatePartner(), 404);

        $statistics = $this->affiliateService->getPartnerStatistics($user);
        $monthlyBreakdown = $this->affiliateService->getMonthlyBreakdown($user);
        $referredSubscriptions = $this->affiliateService->getPartnerSubscriptions($user);
        $codePerformance = $this->affiliateService->getPartnerCodePerformance($user);

        $statusFilter = $request->query('status');
        $rewards = $this->affiliateService->getPartnerRewards($user, $statusFilter, 25);

        // Jména zákazníků vidí admin – partner ne
        $customers = $referredSubscriptions->isEmpty()
            ? collect()
            : Subscription::with('user:id,name,email')
                ->whereIn('id', $referredSubscriptions->pluck('subscription_id'))
                ->get()
                ->mapWithKeys(fn ($s) => [$s->id => $s->user?->name ?: ($s->shipping_address['email'] ?? null)]);

        return view('admin.affiliate.partners.show', [
            'partner' => $user,
            'statistics' => $statistics,
            'monthlyBreakdown' => $monthlyBreakdown,
            'referredSubscriptions' => $referredSubscriptions,
            'codePerformance' => $codePerformance,
            'rewards' => $rewards,
            'statusFilter' => $statusFilter,
            'customers' => $customers,
        ]);
    }

    /**
     * Uloží fakturační hranici partnera
     */
    public function updateSettings(Request $request, User $user)
    {
        abort_unless($user->isAffiliatePartner(), 404);

        $validated = $request->validate([
            'affiliate_payout_threshold' => 'nullable|numeric|min:0',
        ]);

        $user->forceFill([
            'affiliate_payout_threshold' => $validated['affiliate_payout_threshold'] ?? null,
        ])->save();

        // Nová hranice může znamenat, že partner na ni už (ne)dosáhl
        $this->affiliateService->resetPayoutThresholdNotice($user);
        $this->affiliateService->maybeNotifyPayoutThreshold($user);

        return redirect()->back()
            ->with('success', __('affiliate.payout_threshold_saved'));
    }

    /**
     * Označí všechny schválené odměny partnera jako vyplacené
     */
    public function payoutApproved(User $user)
    {
        abort_unless($user->isAffiliatePartner(), 404);

        $rewards = AffiliateReward::where('affiliate_partner_id', $user->id)
            ->where('status', 'approved')
            ->get();

        if ($rewards->isEmpty()) {
            return redirect()->back()->with('error', __('affiliate.bulk_paid_none'));
        }

        foreach ($rewards as $reward) {
            $reward->markAsPaid();
        }

        // Zůstatek klesl – ať partner dostane upozornění, až hranici překročí znovu
        $this->affiliateService->resetPayoutThresholdNotice($user->refresh());

        return redirect()->back()
            ->with('success', __('affiliate.bulk_paid_done', ['count' => $rewards->count()]));
    }

    /**
     * Aktivuje uživatele jako affiliate partnera
     */
    public function activate(User $user)
    {
        $user->forceFill([
            'is_affiliate_partner' => true,
            'affiliate_activated_at' => now(),
        ])->save();

        return redirect()->back()
            ->with('success', __('affiliate.partner_activated'));
    }

    /**
     * Deaktivuje affiliate partnera
     */
    public function deactivate(User $user)
    {
        $user->forceFill([
            'is_affiliate_partner' => false,
        ])->save();

        return redirect()->back()
            ->with('success', __('affiliate.partner_deactivated'));
    }

    /**
     * Aktivuje uživatele jako affiliate partnera podle emailu
     */
    public function activateByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->is_affiliate_partner) {
            return redirect()->back()
                ->with('error', __('affiliate.already_partner'));
        }

        $user->forceFill([
            'is_affiliate_partner' => true,
            'affiliate_activated_at' => now(),
        ])->save();

        return redirect()->back()
            ->with('success', __('affiliate.partner_activated'));
    }
}
