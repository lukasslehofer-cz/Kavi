<?php

namespace App\Mail;

use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class MagicLoginLink extends LocalizedMailable
{
    public string $loginUrl;
    public int $expiresInMinutes;

    public function __construct(string $loginUrl, int $expiresInMinutes = 15, string $locale = 'cs')
    {
        $this->loginUrl = $loginUrl;
        $this->expiresInMinutes = $expiresInMinutes;
        $this->setLocale($locale);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.magic_login.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.magic-login-link',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
