<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewRequest;

class ReviewStatsController extends Controller
{
    /**
     * Účinnost žádostí o hodnocení. Recenze samotné žijí na Googlu, u nás
     * zůstává jen informace o tom, komu jsme napsali a jak zareagoval.
     *
     * Proklik ještě není recenze: hvězdička z e-mailu se na Google nepřenese.
     * Skutečné recenze proto počítá google_review_found_at, které doplňuje
     * reviews:send spárováním recenzí z Googlu s kliknutími podle času.
     */
    public function index()
    {
        $sent = ReviewRequest::real()->whereNotNull('email_sent_at')->count();
        $clicked = ReviewRequest::real()->whereNotNull('clicked_at')->count();
        $rated = ReviewRequest::real()->whereNotNull('rating')->count();
        $reviewed = ReviewRequest::real()->whereNotNull('google_review_found_at')->count();

        $distribution = ReviewRequest::real()->whereNotNull('rating')
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $average = ReviewRequest::real()->whereNotNull('rating')->avg('rating');

        return view('admin.review-stats.index', [
            'stats' => [
                'sent' => $sent,
                'clicked' => $clicked,
                'rated' => $rated,
                'click_rate' => $sent > 0 ? round($clicked / $sent * 100, 1) : 0,
                'average' => $average ? round((float) $average, 1) : null,
                'reminders' => ReviewRequest::real()->whereNotNull('reminded_at')->count(),
                'reviewed' => $reviewed,
                'completion_rate' => $clicked > 0 ? round($reviewed / $clicked * 100, 1) : 0,
                'nudges' => ReviewRequest::real()->whereNotNull('nudged_at')->count(),
                // Bez těchhle dvou by nešlo poznat, jestli připomínka a výzva
                // k něčemu vedou, nebo jen přidávají poštu.
                'clicks_after_reminder' => ReviewRequest::real()
                    ->whereNotNull('reminded_at')
                    ->whereColumn('clicked_at', '>', 'reminded_at')
                    ->count(),
                'reviews_after_nudge' => ReviewRequest::real()
                    ->whereNotNull('nudged_at')
                    ->whereColumn('google_review_found_at', '>', 'nudged_at')
                    ->count(),
            ],
            'distribution' => $distribution,
            'recent' => ReviewRequest::real()->with(['user', 'order', 'subscription'])
                ->whereNotNull('email_sent_at')
                ->latest('email_sent_at')
                ->limit(30)
                ->get(),
        ]);
    }
}
