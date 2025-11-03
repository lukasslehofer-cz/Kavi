<?php

namespace App\Http\Controllers;

use App\Models\ReviewRequest;
use Illuminate\Http\Request;

class ReviewRequestController extends Controller
{
    /**
     * Track click and redirect to Trustpilot
     */
    public function track(Request $request, string $token)
    {
        // Find review request by token
        $reviewRequest = ReviewRequest::where('tracking_token', $token)->firstOrFail();

        // Mark as clicked if not already clicked
        if (!$reviewRequest->hasClicked()) {
            $reviewRequest->markAsClicked($request->ip());
        }

        // Redirect to Trustpilot review page
        return redirect()->away('https://www.trustpilot.com/review/kavi.cz');
    }
}
