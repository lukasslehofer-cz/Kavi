<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PaymentMethodChanged extends LocalizedMailable
{
    public User $user;
    public string $cardLast4;
    public string $cardBrand;

    public function __construct(User $user, string $cardLast4, string $cardBrand, ?string $locale = null)
    {
        $this->user = $user;
        $this->cardLast4 = $cardLast4;
        $this->cardBrand = $cardBrand;
        $this->setLocale($locale ?? EmailService::getLocaleFromUser($user));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.payment_method_changed.subject'),
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
