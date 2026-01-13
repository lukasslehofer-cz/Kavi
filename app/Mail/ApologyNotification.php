<?php

namespace App\Mail;

use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ApologyNotification extends LocalizedMailable
{
    public function __construct(?string $locale = 'cs')
    {
        $this->setLocale($locale);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: 'Omluva za chybnou notifikaci - KAVI',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.apology-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
