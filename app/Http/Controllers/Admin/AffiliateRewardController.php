<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateReward;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Http\Request;

class AffiliateRewardController extends Controller
{
    public function __construct(
        private AffiliateService $affiliateService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Zobrazí seznam affiliate odměn
     */
    public function index(Request $request)
    {
        $partnerId = $request->query('partner');
        $status = $request->query('status');

        $rewards = AffiliateReward::with(['affiliatePartner', 'coupon', 'order', 'subscription'])
            ->when($partnerId, function($query, $partnerId) {
                $query->where('affiliate_partner_id', $partnerId);
            })
            ->when($status, function($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Get partners for filter
        $partners = User::where('is_affiliate_partner', true)
            ->orderBy('name')
            ->get();

        // Statistics
        $statistics = [
            'total_count' => AffiliateReward::count(),
            'total_amount' => AffiliateReward::sum('reward_amount'),
            'pending_count' => AffiliateReward::where('status', 'pending')->count(),
            'pending_amount' => AffiliateReward::where('status', 'pending')->sum('reward_amount'),
            'approved_count' => AffiliateReward::where('status', 'approved')->count(),
            'approved_amount' => AffiliateReward::where('status', 'approved')->sum('reward_amount'),
            'paid_count' => AffiliateReward::where('status', 'paid')->count(),
            'paid_amount' => AffiliateReward::where('status', 'paid')->sum('reward_amount'),
        ];

        return view('admin.affiliate.rewards.index', compact('rewards', 'partners', 'partnerId', 'status', 'statistics'));
    }

    /**
     * Schválí odměnu
     */
    public function approve(AffiliateReward $reward)
    {
        $reward->approve();

        return redirect()->back()
            ->with('success', __('affiliate.reward_approved'));
    }

    /**
     * Označí odměnu jako vyplacenou
     */
    public function markPaid(AffiliateReward $reward, Request $request)
    {
        $reward->markAsPaid($request->input('notes'));

        $this->resetThresholdNotice($reward);

        return redirect()->back()
            ->with('success', __('affiliate.reward_marked_paid'));
    }

    /**
     * Zruší odměnu
     */
    public function cancel(AffiliateReward $reward, Request $request)
    {
        $reward->cancel($request->input('notes'));

        $this->resetThresholdNotice($reward);

        return redirect()->back()
            ->with('success', __('affiliate.reward_cancelled'));
    }

    /**
     * Zůstatek klesl – ať partner dostane mail znovu, až hranici překročí příště
     */
    private function resetThresholdNotice(AffiliateReward $reward): void
    {
        if ($partner = $reward->affiliatePartner) {
            $this->affiliateService->resetPayoutThresholdNotice($partner);
        }
    }
}
