<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Concerns\ImpersonatesUser;
use App\Http\Controllers\Controller;
use App\Services\AffiliateService;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    use ImpersonatesUser;

    public function __construct(
        private AffiliateService $affiliateService
    ) {
        $this->middleware('auth');
    }

    /**
     * Zobrazí affiliate dashboard
     */
    public function index(Request $request)
    {
        $user = $this->getViewingUser();

        // Kontrola, že je uživatel affiliate partner
        if (!$user->isAffiliatePartner()) {
            return redirect()->localizedRoute('dashboard.index')
                ->with('error', __('affiliate.not_affiliate_partner'));
        }

        // Získej statistiky
        $statistics = $this->affiliateService->getPartnerStatistics($user);

        // Získej affiliate kódy a linky
        $coupons = $user->affiliateCoupons()
            ->where('is_active', true)
            ->where('affiliate_code_enabled', true)
            ->with('affiliateLinks')
            ->get();

        // Získej odměny
        $statusFilter = $request->query('status');
        $rewards = $this->affiliateService->getPartnerRewards($user, $statusFilter);
        
        // Seskupit odměny pro tabulku
        $rewardsGrouped = $rewards->groupBy(function($reward) {
            return $reward->created_at->format('Y-m');
        });

        return view('dashboard.affiliate', compact(
            'statistics',
            'coupons',
            'rewards',
            'rewardsGrouped',
            'statusFilter'
        ));
    }
}
