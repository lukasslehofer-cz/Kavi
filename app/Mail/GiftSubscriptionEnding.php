<?php

namespace App\Mail;

use App\Helpers\DateFormat;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Před posledním boxem dárkového předplatného: nabídka pokračování
 */
class GiftSubscriptionEnding extends LocalizedMailable
{
    public Subscription $subscription;

    protected Carbon $lastBox;

    public function __construct(Subscription $subscription, Carbon $lastBox)
    {
        $this->subscription = $subscription->loadMissing('giftPurchase');
        $this->lastBox = $lastBox;
        $this->setLocale($subscription->giftPurchase?->locale ?: \App\Services\EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('gifts.mail.ending_subject', ['date' => DateFormat::full($this->lastBox, $this->emailLocale)]),
        );
    }

    public function content(): Content
    {
        $bags = (int) ($this->subscription->giftPurchase?->bags ?? $this->subscription->configuration['amount'] ?? 3);

        return new Content(
            view: 'emails.gift-subscription-ending',
            with: [
                'lastBox' => DateFormat::full($this->lastBox, $this->emailLocale),
                'continueUrl' => $this->mailUrl('subscriptions.index') . '?plan=' . $bags,
            ],
        );
    }
}
