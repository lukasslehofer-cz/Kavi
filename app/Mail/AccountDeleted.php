<?php

namespace App\Mail;

use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AccountDeleted extends LocalizedMailable
{
    public string $userEmail;

    public function __construct(string $userEmail, string $locale = 'cs')
    {
        $this->userEmail = $userEmail;
        $this->setLocale($locale);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.account_deleted.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-deleted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
