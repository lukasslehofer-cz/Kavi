<?php

namespace App\Mail;

use App\Helpers\CurrencyHelper;
use App\Helpers\DateFormat;
use App\Helpers\SubscriptionPricing;
use App\Models\Subscription;
use App\Services\EmailService;
use App\Services\SubscriptionBoxPreview;
use App\Services\SubscriptionConfigurationService;
use Carbon\Carbon;

/**
 * Společný základ připomínek, které začínají obsahem dalšího boxu: upomínka platby
 * a konec pauzy. Nejdřív co přijde, pod tím platba a odkazy na přeskočení a změnu.
 *
 * Odkazy vedou do účtu (zákazník se přihlásí a přistane rovnou na akci), proto
 * nejsou podepsané.
 */
abstract class UpcomingBoxMailable extends LocalizedMailable
{
    public Subscription $subscription;

    /** Náhled boxu čte předmět i tělo, počítá se jednou */
    private ?array $boxPreview = null;

    private bool $boxPreviewLoaded = false;

    public function __construct(Subscription $subscription, ?string $locale = null)
    {
        $this->subscription = $subscription;
        $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($subscription));
    }

    /**
     * Datum, kdy se strhne platba za box z náhledu
     */
    abstract protected function paymentDate(): ?Carbon;

    public function attachments(): array
    {
        return [];
    }

    /**
     * Je známý obsah boxu (aspoň pražírny měsíce)? Podle toho se loguje, že chybí
     * zadat kávy dalšího měsíce.
     */
    public function hasKnownBoxContents(): bool
    {
        return ($this->box()['source'] ?? null) !== null;
    }

    protected function box(): ?array
    {
        if (! $this->boxPreviewLoaded) {
            $this->boxPreviewLoaded = true;

            try {
                $this->boxPreview = app(SubscriptionBoxPreview::class)->for($this->subscription, $this->emailLocale);
            } catch (\Throwable $e) {
                // Připomínka platby musí odejít i bez výčtu káv
                \Log::warning('Box preview failed for reminder e-mail', [
                    'subscription_id' => $this->subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->boxPreview;
    }

    /**
     * Název měsíce boxu ve tvaru z lang/{cs,en}/months.php ("listopadový", "listopadovém")
     */
    protected function boxMonth(string $form = 'adjective'): ?string
    {
        $box = $this->box();

        return $box ? $this->trans('months.'.$form.'.'.$box['month']->month) : null;
    }

    /**
     * "A, B a C" - pražírny boxu do předmětu a do věty o pražírnách měsíce
     */
    protected function roasteryNames(): string
    {
        return collect($this->box()['roasteries'] ?? [])
            ->pluck('name')
            ->join(', ', ' '.$this->trans('emails.upcoming_box.and').' ');
    }

    /**
     * Data společná oběma šablonám
     */
    protected function boxViewData(): array
    {
        $box = $this->box();
        $paymentDate = $this->paymentDate();
        $breakdown = SubscriptionPricing::forRecurringPayment($this->subscription);
        $subscriptionId = $this->subscription->id;

        $roasteries = collect($box['roasteries'] ?? [])->map(fn (array $roastery) => $roastery + [
            'url' => $roastery['slug'] ? $this->mailUrl('roasteries.show', ['roastery' => $roastery['slug']]) : null,
        ])->all();

        return [
            'boxSource' => $box['source'] ?? null,
            'roasteries' => $roasteries,
            'roasteryNames' => $this->roasteryNames(),
            'monthAdjective' => $this->boxMonth('adjective'),
            'monthLocative' => $this->boxMonth('adjective_locative'),
            'shipmentDate' => $box ? DateFormat::dayMonth($box['shipment_date'], $this->emailLocale) : null,
            'paymentDate' => $paymentDate ? DateFormat::full($paymentDate, $this->emailLocale) : null,
            'paymentDay' => $paymentDate ? DateFormat::dayMonth($paymentDate, $this->emailLocale) : null,
            'amount' => CurrencyHelper::formatByCurrency($breakdown->total, $breakdown->currency),
            'shipping' => $breakdown->shipping > 0
                ? CurrencyHelper::formatByCurrency($breakdown->shipping, $breakdown->currency)
                : null,
            // Strhává se v noci na den platby, poslední celý den na změnu je ten předchozí
            'deadline' => $paymentDate ? DateFormat::dayMonth($paymentDate->copy()->subDay(), $this->emailLocale) : null,
            // Tlačítko jen pro akci, kterou účet zákazníkovi opravdu dovolí
            'pauseUrl' => $this->subscription->customerCanPause()
                ? $this->mailUrl('dashboard.subscription', ['pause' => $subscriptionId])
                : null,
            'changeUrl' => app(SubscriptionConfigurationService::class)->eligibility($this->subscription)['allowed']
                ? $this->mailUrl('dashboard.subscription.configure', ['subscription' => $subscriptionId])
                : null,
            'manageUrl' => $this->mailUrl('dashboard.subscription'),
        ];
    }
}
