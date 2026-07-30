<?php

namespace App\Http\Controllers;

use App\Models\ReviewRequest;
use App\Services\EmailService;
use Illuminate\Http\Request;

class ReviewRequestController extends Controller
{
    /**
     * Kliknutí z e-mailu. Zaznamená hvězdičku a pošle zákazníka rovnou na
     * veřejný profil - recenzi píše jen jednou, na jednom místě.
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
     * Každá doména má vlastní Google profil, vybírá se podle měny objednávky.
     */
    protected function publicReviewUrl(ReviewRequest $reviewRequest): string
    {
        $locale = EmailService::getLocaleFromCurrency($this->currencyFor($reviewRequest));

        return config("services.review_links.{$locale}")
            ?? config('services.review_links.cs');
    }

    protected function currencyFor(ReviewRequest $reviewRequest): string
    {
        return $reviewRequest->order?->currency
            ?? $reviewRequest->subscription?->currency
            ?? 'CZK';
    }
}
