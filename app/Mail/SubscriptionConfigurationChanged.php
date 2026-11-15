<?php

namespace App\Mail;

use App\Helpers\CurrencyHelper;
use App\Helpers\DateFormat;
use App\Helpers\SubscriptionPricing;
use App\Helpers\SubscriptionSummary;
use App\Models\Subscription;
use App\Models\SubscriptionConfigurationChange;
use App\Services\EmailService;
use App\Services\SubscriptionConfigurationService;
use App\Services\SubscriptionShipmentService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Potvrzení změny konfigurace předplatného (velikost boxu, druh kávy, frekvence).
 *
 * Posílá se jednou, při zadání změny - ať se použila hned, nebo čeká za už zaplacený
 * box. Mění se jí cena i termín, takže ji zákazník má mít černé na bílém.
 */
class SubscriptionConfigurationChanged extends LocalizedMailable
{
    public Subscription $subscription;

    public SubscriptionConfigurationChange $change;

    public function __construct(Subscription $subscription, SubscriptionConfigurationChange $change, ?string $locale = null)
    {
        $this->subscription = $subscription;
        $this->change = $change;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.subscription_configuration_changed.subject', [
                'number' => $this->subscription->subscription_number ?? '#'.$this->subscription->id,
            ]),
        );
    }

    public function content(): Content
    {
        $scheduled = $this->change->isScheduled();
        $breakdown = SubscriptionPricing::forRecurringPayment($this->subscription);

        // Naplánovaná změna na předplatném ještě není - cenu je potřeba dopočítat z ní
        if ($scheduled && $this->change->new_configured_price !== null) {
            $box = (float) $this->change->new_configured_price;
            $breakdown = SubscriptionPricing::fromValues(
                $box,
                app(SubscriptionConfigurationService::class)->discountFor($this->subscription, $box) ?? 0.0,
                SubscriptionPricing::shippingCost($this->subscription),
                $breakdown->currency,
            );
        }

        $lockedDate = $scheduled
            ? app(SubscriptionShipmentService::class)->lockedShipment($this->subscription)?->shipment_date
            : null;

        // U naplánované změny se datum platby určí až při jejím použití
        $nextPayment = ! $scheduled && $this->subscription->isActive()
            ? $this->subscription->next_billing_date
            : null;

        return new Content(
            view: 'emails.subscription-configuration-changed',
            with: [
                'scheduled' => $scheduled,
                'newRows' => SubscriptionSummary::configRows(
                    $this->change->new_configuration ?? [],
                    $this->change->new_frequency_months,
                    $this->emailLocale,
                ),
                'oldRows' => SubscriptionSummary::configRows(
                    $this->change->old_configuration ?? [],
                    $this->change->old_frequency_months,
                    $this->emailLocale,
                ),
                'effectiveDate' => $this->change->effective_shipment_date
                    ? DateFormat::full($this->change->effective_shipment_date, $this->emailLocale)
                    : null,
                'lockedDate' => $lockedDate ? DateFormat::full($lockedDate, $this->emailLocale) : null,
                'nextPayment' => $nextPayment ? DateFormat::full($nextPayment, $this->emailLocale) : null,
                'amount' => CurrencyHelper::formatByCurrency($breakdown->total, $breakdown->currency),
                'manageUrl' => $this->mailUrl('dashboard.subscription'),
            ],
        );
    }
}
