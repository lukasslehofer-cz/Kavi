<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailChangeConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $newEmail,
        public string $confirmationToken
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Potvrďte změnu emailové adresy - KAVI.cz',
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

