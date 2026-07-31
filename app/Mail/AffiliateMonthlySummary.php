<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AffiliateMonthlySummary extends LocalizedMailable
{
    public User $user;

    public Carbon $month;

    /**
     * @var array{earned: float, rewards_count: int, new_conversions: int, clicks: int, currency: string,
     *             payable_amount: float, threshold: float, threshold_reached: bool,
     *             active_subscriptions: int, estimated_monthly_income: float}
     */
    public array $summary;

    public string $affiliateUrl;

    /**
     * Název měsíce v jazyce e-mailu (např. "Červenec 2026")
     */
    public string $monthLabel;

    public function __construct(User $partner, Carbon $month, array $summary, ?string $locale = null)
    {
        $this->user = $partner;
        $this->month = $month;
        $this->summary = $summary;
        $this->setLocale($locale ?? EmailService::getLocaleFromUser($partner));
        $this->affiliateUrl = $this->localizedRouteFor('dashboard.affiliate');
        $this->monthLabel = $this->trans('emails.affiliate_monthly_summary.months.'.$month->month).' '.$month->year;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.affiliate_monthly_summary.subject', [
                'month' => $this->monthLabel,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliate-monthly-summary',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
