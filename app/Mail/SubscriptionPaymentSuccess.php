<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SubscriptionPaymentSuccess extends LocalizedMailable
{
    public Subscription $subscription;
    public SubscriptionPayment $payment;

    public function __construct(Subscription $subscription, SubscriptionPayment $payment, ?string $locale = null)
    {
        $this->subscription = $subscription;
        $this->payment = $payment;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.subscription_payment_success.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-payment-success',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
