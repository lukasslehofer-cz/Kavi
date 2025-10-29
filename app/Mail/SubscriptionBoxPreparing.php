<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionBoxPreparing extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Připravujeme váš kávový box ☕ - ' . ($this->subscription->subscription_number ?? 'Kavi Coffee'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-box-preparing',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

