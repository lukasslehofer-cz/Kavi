<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SubscriptionPaymentFailed extends LocalizedMailable
{
    public Subscription $subscription;
    public string $failureReason;

    public function __construct(Subscription $subscription, string $failureReason = '', ?string $locale = null)
    {
        $this->subscription = $subscription;
        $this->failureReason = $failureReason;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.subscription_payment_failed.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-payment-failed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
