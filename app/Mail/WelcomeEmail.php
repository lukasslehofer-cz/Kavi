<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeEmail extends LocalizedMailable
{
    public User $user;

    public function __construct(User $user, ?string $locale = null)
    {
        $this->user = $user;
        $this->setLocale($locale ?? EmailService::getLocaleFromUser($user));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.welcome.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
