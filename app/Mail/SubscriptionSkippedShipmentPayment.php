<?php

namespace App\Mail;

use App\Helpers\CurrencyHelper;
use App\Helpers\DateFormat;
use App\Helpers\SubscriptionPricing;
use App\Helpers\SubscriptionSummary;
use App\Models\Subscription;
use App\Models\SubscriptionShipment;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;

/**
 * Výzva k doplacení rozesílky vynechané kvůli neplacení. Posílá ji admin ručně
 * z detailu předplatného, odkaz k platbě platí jen do konce dne odeslání.
 */
class SubscriptionSkippedShipmentPayment extends LocalizedMailable
{
    public Subscription $subscription;

    public SubscriptionShipment $shipment;

    public function __construct(Subscription $subscription, SubscriptionShipment $shipment, ?string $locale = null)
    {
        $this->subscription = $subscription;
        $this->shipment = $shipment;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.subscription_skipped_payment.subject', [
                'number' => $this->subscription->subscription_number ?? '#'.$this->subscription->id,
            ]),
        );
    }

    public function content(): Content
    {
        $breakdown = SubscriptionPricing::forRecurringPayment($this->subscription);

        return new Content(
            view: 'emails.subscription-skipped-payment',
            with: [
                'shipmentDate' => DateFormat::full($this->shipment->shipment_date, $this->emailLocale),
                'amount' => CurrencyHelper::formatByCurrency($breakdown->total, $breakdown->currency),
                'today' => DateFormat::full(now(), $this->emailLocale),
                'summaryRows' => SubscriptionSummary::rows($this->subscription, $this->emailLocale),
                // Relativní podpis jako u dárkového certifikátu - doména se liší podle jazyka
                'payUrl' => $this->siteUrl.URL::temporarySignedRoute(
                    $this->emailLocale === 'en' ? 'subscriptions.pay-skipped.en' : 'subscriptions.pay-skipped',
                    now()->endOfDay(),
                    ['shipment' => $this->shipment->id],
                    false
                ),
            ],
        );
    }
}
