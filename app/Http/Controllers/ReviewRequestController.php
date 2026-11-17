<?php

namespace App\Http\Controllers;

use App\Models\ReviewRequest;
use Illuminate\Http\Request;

class ReviewRequestController extends Controller
{
    /**
     * Kliknutí z e-mailu. Zaznamená hvězdičku a pošle zákazníka rovnou na
     * veřejný profil - recenzi píše jen jednou, na jednom místě. Hvězdička
     * se na Google nepřenese, tam ji zákazník vybírá znovu.
     */
    public function track(Request $request, string $token, ?int $rating = null)
    {
        $reviewRequest = ReviewRequest::where('tracking_token', $token)->firstOrFail();

        if ($rating !== null && ($rating < 1 || $rating > 5)) {
            $rating = null;
        }

        $reviewRequest->markAsClicked($request->ip(), $rating);

        return redirect()->away($this->publicReviewUrl($reviewRequest));
    }

    /**
     * Každá doména má vlastní Google profil - viz ReviewRequest::profileLocale().
     */
    protected function publicReviewUrl(ReviewRequest $reviewRequest): string
    {
        return config('services.review_links.'.$reviewRequest->profileLocale())
            ?? config('services.review_links.cs');
    }
}
