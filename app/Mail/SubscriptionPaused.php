<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SubscriptionPaused extends LocalizedMailable
{
    public Subscription $subscription;
    public string $reason;

    public function __construct(Subscription $subscription, string $reason = '', ?string $locale = null)
    {
        $this->subscription = $subscription;
        $this->reason = $reason;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.subscription_paused.subject', [
                'subscription_number' => $this->subscription->subscription_number ?? $this->siteName
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-paused',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
