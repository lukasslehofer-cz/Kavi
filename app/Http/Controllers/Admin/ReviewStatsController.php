<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewRequest;

class ReviewStatsController extends Controller
{
    /**
     * Účinnost žádostí o hodnocení. Recenze samotné žijí na Googlu, u nás
     * zůstává jen informace o tom, komu jsme napsali a jak zareagoval.
     */
    public function index()
    {
        $sent = ReviewRequest::real()->whereNotNull('email_sent_at')->count();
        $clicked = ReviewRequest::real()->whereNotNull('clicked_at')->count();
        $rated = ReviewRequest::real()->whereNotNull('rating')->count();

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
