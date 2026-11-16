<?php

namespace App\Mail;

use App\Helpers\BoxPricing;
use App\Helpers\CurrencyHelper;
use App\Models\Order;
use App\Models\Roastery;
use App\Services\CoffeeOfMonthService;
use App\Services\EmailService;
use App\Services\SubscriptionOfferService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderDelivered extends LocalizedMailable
{
    public Order $order;

    /** Nabídka předplatného pro kupce bez předplatného, null = bez nabídky */
    private ?array $subscriptionOffer = null;

    private bool $subscriptionOfferResolved = false;

    public function __construct(Order $order, ?string $locale = null)
    {
        $this->order = $order;
        $this->setLocale($locale ?? EmailService::getLocaleFromOrder($order));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.order_delivered.subject', [
                'order_number' => $this->order->order_number
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-delivered',
            with: [
                'subscriptionOffer' => $this->subscriptionOffer(),
                'orderUrl' => $this->mailUrl('dashboard.order.detail', ['order' => $this->order->id]),
                'dashboardUrl' => $this->mailUrl('dashboard.index'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    /**
     * Obsahuje e-mail nabídku předplatného? Podle toho observer zapíše k objednávce
     * subscription_offer_sent_at a od té chvíle se kupci počítá 60 dnů na přechod.
     */
    public function includesSubscriptionOffer(): bool
    {
        return $this->subscriptionOffer() !== null;
    }

    private function subscriptionOffer(): ?array
    {
        if ($this->subscriptionOfferResolved) {
            return $this->subscriptionOffer;
        }

        $this->subscriptionOfferResolved = true;

        try {
            if (! app(SubscriptionOfferService::class)->shouldOffer($this->order)) {
                return null;
            }

            $currency = $this->order->currency ?: ($this->emailLocale === 'en' ? 'EUR' : 'CZK');

            $this->subscriptionOffer = [
                // UTM jen pro přehled návštěv z e-mailu; přechod na předplatné se počítá
                // podle kupce, ne podle prokliku
                'url' => $this->mailUrl('subscriptions.index', [
                    'utm_source' => 'email',
                    'utm_medium' => 'transactional',
                    'utm_campaign' => 'order_delivered_box',
                ]),
                'price' => CurrencyHelper::formatByCurrency(BoxPricing::cheapest($currency), $currency),
                'lineup' => $this->lineupSentence(),
                // "Až tahle káva dojde" dává smysl jen u objednávky s kávou; poukaz nebo
                // příslušenství dostanou obecný nadpis
                'has_coffee' => $this->order->loadMissing('items.product')->items
                    ->contains(fn ($item) => (bool) $item->product?->isCoffee()),
            ];
        } catch (\Throwable $e) {
            // E-mail o doručení musí odejít i bez nabídky
            \Log::warning('Subscription offer skipped in order delivered e-mail', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $this->subscriptionOffer;
    }

    /**
     * "V prosincovém boxu jsou A, B a C." - pražírny boxu, do kterého by nová
     * objednávka předplatného spadla. Bez zadaných pražíren se věta vynechá.
     */
    private function lineupSentence(): ?string
    {
        $lineup = app(CoffeeOfMonthService::class)->lineup();

        $names = $lineup->displayRoasteries()
            ->map(fn (Roastery $roastery) => $this->emailLocale === 'en' && ! empty($roastery->name_en)
                ? $roastery->name_en
                : $roastery->name)
            ->join(', ', ' '.$this->trans('emails.upcoming_box.and').' ');

        if ($names === '') {
            return null;
        }

        return $this->trans('emails.subscription_offer.lineup', [
            'month' => $lineup->monthName('adjective_locative', $this->emailLocale),
            'roasteries' => $names,
        ]);
    }
}
