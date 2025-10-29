<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentMethodChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $cardLast4,
        public string $cardBrand
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Platební metoda byla změněna - Kavi Coffee',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-method-changed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

