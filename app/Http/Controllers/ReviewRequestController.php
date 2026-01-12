<?php

namespace App\Http\Controllers;

use App\Models\ReviewRequest;
use Illuminate\Http\Request;

class ReviewRequestController extends Controller
{
    /**
     * Track click and redirect to review page (Google for CZ, Trustpilot for EN)
     */
    public function track(Request $request, string $token)
    {
        // Find review request by token
        $reviewRequest = ReviewRequest::where('tracking_token', $token)->firstOrFail();

        // Mark as clicked if not already clicked
        if (!$reviewRequest->hasClicked()) {
            $reviewRequest->markAsClicked($request->ip());
        }

        // Determine currency from order or subscription
        $currency = null;
        if ($reviewRequest->order) {
            $currency = $reviewRequest->order->currency;
        } elseif ($reviewRequest->subscription) {
            $currency = $reviewRequest->subscription->currency;
        }

        // Redirect based on currency: CZK = Google Business, EUR = Trustpilot
        if ($currency === 'CZK') {
            return redirect()->away('https://g.page/r/CUKHHPAV65MnEBM/review');
        }

        // Default to Trustpilot for EUR/international
        return redirect()->away('https://www.trustpilot.com/review/kavi.cz');
    }
}
