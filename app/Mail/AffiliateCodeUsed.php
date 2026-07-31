<?php

namespace App\Mail;

use App\Models\AffiliateReward;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AffiliateCodeUsed extends LocalizedMailable
{
    public User $user;

    public AffiliateReward $reward;

    public string $affiliateUrl;

    public function __construct(User $partner, AffiliateReward $reward, ?string $locale = null)
    {
        $this->user = $partner;
        $this->reward = $reward;
        $this->setLocale($locale ?? EmailService::getLocaleFromUser($partner));
        $this->affiliateUrl = $this->localizedRouteFor('dashboard.affiliate');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.affiliate_code_used.subject', [
                'code' => $this->reward->coupon?->code ?? '',
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliate-code-used',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
