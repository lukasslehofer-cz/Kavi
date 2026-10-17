<?php

namespace App\Mail;

use App\Helpers\DateFormat;
use App\Helpers\ShipmentCycles;
use App\Models\Subscription;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Potvrzení uplatnění dárkového předplatného: první a poslední box
 */
class GiftSubscriptionActivated extends LocalizedMailable
{
    public Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription->loadMissing('giftPurchase');
        $this->setLocale($subscription->giftPurchase?->locale ?: \App\Services\EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('gifts.mail.activated_subject', ['date' => $this->dates()['first']]),
        );
    }

    public function content(): Content
    {
        $gift = $this->subscription->giftPurchase;

        return new Content(
            view: 'emails.gift-subscription-activated',
            with: $this->dates() + [
                'box' => $gift?->boxLabel($this->emailLocale),
                'accountUrl' => $this->mailUrl('dashboard.index'),
            ],
        );
    }

    /**
     * @return array{first: string, last: string}
     */
    private function dates(): array
    {
        $gift = $this->subscription->giftPurchase;
        [$year, $month] = array_map('intval', explode('-', $gift?->start_month ?? now()->format('Y-m')));
        $first = ShipmentCycles::forMonth($year, $month);
        $lastMonth = $first->month()->addMonthsNoOverflow(max(0, (int) ($gift?->months ?? 1) - 1));
        $last = ShipmentCycles::forMonth($lastMonth->year, $lastMonth->month);

        return [
            'first' => DateFormat::full($first->shipmentDate, $this->emailLocale),
            'last' => DateFormat::full($last->shipmentDate, $this->emailLocale),
        ];
    }
}
