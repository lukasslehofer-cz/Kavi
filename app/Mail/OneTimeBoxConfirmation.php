<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OneTimeBoxConfirmation extends LocalizedMailable
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
            subject: $this->trans('emails.onetime_box.subject', [
                'subscription_number' => $this->subscription->subscription_number ?? ''
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.onetime-box-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
