<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AffiliatePayoutThresholdReached extends LocalizedMailable
{
    public User $user;

    public float $amount;

    public string $currency;

    public float $threshold;

    public string $affiliateUrl;

    public function __construct(User $partner, float $amount, string $currency, float $threshold, ?string $locale = null)
    {
        $this->user = $partner;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->threshold = $threshold;
        $this->setLocale($locale ?? EmailService::getLocaleFromUser($partner));
        $this->affiliateUrl = $this->localizedRouteFor('dashboard.affiliate');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.affiliate_payout_threshold.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliate-payout-threshold',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
