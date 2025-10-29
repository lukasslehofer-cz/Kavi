<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Předplatné zrušeno - Budeme se těšit na viděnou! - Kavi Coffee',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-cancelled',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

