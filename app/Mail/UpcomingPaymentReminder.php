<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class UpcomingPaymentReminder extends LocalizedMailable
{
    public Subscription $subscription;

    public function __construct(Subscription $subscription, ?string $locale = null)
    {
        $this->subscription = $subscription;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.upcoming_payment.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.upcoming-payment-reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
