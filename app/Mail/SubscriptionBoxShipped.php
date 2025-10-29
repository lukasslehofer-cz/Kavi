<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionBoxShipped extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Váš kávový box byl expedován 📦 - ' . ($this->subscription->subscription_number ?? 'Kavi Coffee'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-box-shipped',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

