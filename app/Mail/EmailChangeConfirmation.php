<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class EmailChangeConfirmation extends LocalizedMailable
{
    public User $user;
    public string $newEmail;
    public string $verificationUrl;

    public function __construct(User $user, string $newEmail, string $confirmationUrl, ?string $locale = null)
    {
        $this->user = $user;
        $this->newEmail = $newEmail;
        $this->verificationUrl = $confirmationUrl;
        $this->setLocale($locale ?? EmailService::getLocaleFromUser($user));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.email_change.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.email-change-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
