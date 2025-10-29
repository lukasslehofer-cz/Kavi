<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaused extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public string $reason = 'user_request' // 'user_request' or 'payment_failed'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Předplatné pozastaveno - ' . ($this->subscription->subscription_number ?? 'Kavi Coffee'),
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

