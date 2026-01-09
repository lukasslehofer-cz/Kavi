<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        // Get statistics for each partner
        foreach ($partners as $partner) {
            $partner->statistics = $this->affiliateService->getPartnerStatistics($partner);
        }

        return view('admin.affiliate.partners.index', compact('partners', 'search'));
    }

    /**
     * Aktivuje uživatele jako affiliate partnera
     */
    public function activate(User $user)
    {
        $user->update([
            'is_affiliate_partner' => true,
            'affiliate_activated_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', __('affiliate.partner_activated'));
    }

    /**
     * Deaktivuje affiliate partnera
     */
    public function deactivate(User $user)
    {
        $user->update([
            'is_affiliate_partner' => false,
        ]);

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
                ->with('error', 'Tento uživatel už je affiliate partnerem.');
        }

        $user->update([
            'is_affiliate_partner' => true,
            'affiliate_activated_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', __('affiliate.partner_activated'));
    }
}
