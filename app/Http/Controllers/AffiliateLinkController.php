<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLink;
use App\Services\AffiliateService;
use Illuminate\Http\Request;

class AffiliateLinkController extends Controller
{
    public function __construct(
        private AffiliateService $affiliateService
    ) {
    }

    /**
     * Přesměrování z affiliate linku
     * Route: GET /r/{slug}
     */
    public function redirect(Request $request, string $slug)
    {
        // Najdi link podle slugu
        $link = AffiliateLink::where('slug', $slug)
            ->where('is_active', true)
            ->with('coupon')
            ->first();

        if (!$link) {
            \Log::warning('Affiliate link not found', ['slug' => $slug]);
            return redirect()->localizedRoute('home')
                ->with('error', __('affiliate.link_not_found'));
        }

        // Kontrola, že kupón je aktivní
        if (!$link->coupon || !$link->coupon->is_active) {
            \Log::warning('Affiliate link has inactive coupon', [
                'slug' => $slug,
                'coupon_id' => $link->coupon_id,
            ]);
            return redirect()->localizedRoute('home')
                ->with('error', __('affiliate.link_expired'));
        }

        // Zaznamenej kliknutí
        try {
            $this->affiliateService->trackLinkClick($link, $request);
        } catch (\Exception $e) {
            \Log::error('Failed to track affiliate link click', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);
            // Pokračuj v přesměrování i když tracking selhal
        }

        // Ulož affiliate kód do session
        $this->affiliateService->storeAffiliateCodeInSession($link->coupon->code);

        \Log::info('Affiliate link clicked', [
            'slug' => $slug,
            'coupon_code' => $link->coupon->code,
            'partner_id' => $link->affiliate_partner_id,
        ]);

        // Přesměruj na homepage nebo zadanou URL
        $redirectTo = $request->query('redirect', localizedRoute('home'));
        
        // Bezpečnostní kontrola - pouze interní URL
        if (!str_starts_with($redirectTo, url('/'))) {
            $redirectTo = localizedRoute('home');
        }

        return redirect($redirectTo);
    }
}
