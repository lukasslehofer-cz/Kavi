<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaymentFailed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public ?string $failureReason = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Problém s platbou předplatného - ' . ($this->subscription->subscription_number ?? 'KAVI.cz'),
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

