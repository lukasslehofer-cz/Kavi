<?php

namespace App\Services;

use App\Helpers\BoxPricing;
use App\Helpers\CurrencyHelper;
use App\Helpers\SubscriptionHelper;
use App\Helpers\SubscriptionPricing;
use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use App\Models\SubscriptionConfigurationChange;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Změna konfigurace existujícího předplatného (velikost boxu, druh kávy, frekvence)
 * s platností od dalšího NEZAPLACENÉHO boxu.
 *
 * Konfigurace i cena žijí jen na subscriptions a všechno je čte živě: strhávání,
 * faktura, stránka rozesílky, rezervace skladu i Packeta. Ledger žádný snapshot nemá.
 * Drží se proto jediný invariant:
 *
 *     živá konfigurace předplatného = konfigurace nejbližšího boxu, který má odejít.
 *
 *  - Není-li žádný zamčený box (SubscriptionShipmentService::lockedShipment), použije se
 *    změna hned.
 *  - Je-li, uloží se jako naplánovaná (subscription_configuration_changes.status =
 *    scheduled) a použije ji noční příkaz subscriptions:apply-configuration-changes,
 *    jakmile žádný zamčený box nezbývá. markAsShipped ani billing cron se nemění.
 *
 * Cena: z aktuálního ceníku jen při změně velikosti nebo decaf. Změna druhu nebo
 * frekvence configured_price nemění, takže starší předplatné si svou cenu podrží.
 */
class SubscriptionConfigurationService
{
    public const AMOUNTS = [2, 3, 4];

    public const TYPES = ['espresso', 'filter', 'mix'];

    public const FREQUENCIES = [1, 2, 3];

    /** Změny konfigurace, které okno zrušení nabízí místo zrušení (viz cancellationAlternatives). */
    public const CANCEL_ALTERNATIVES = ['lower_frequency', 'higher_frequency', 'smaller_box', 'bigger_box', 'm_box'];

    public function __construct(
        private SubscriptionShipmentService $shipmentService,
        private StockReservationService $reservationService,
    ) {}

    /**
     * Sjednotí tvar konfigurace na [amount:int, type:string, isDecaf:bool, mix?:[espresso,filter]].
     *
     * Uložené konfigurace mají z formuláře řetězce ("2", "1") a u ne-mixu nulový mix.
     * Bez normalizace by porovnání "změnilo se něco?" hlásilo změnu i tam, kde žádná
     * není, a přecenilo by starší předplatné podle ceníku.
     *
     * @return array|null Null = konfigurace, se kterou změna neumí pracovat
     */
    public function normalize(mixed $configuration): ?array
    {
        if (is_string($configuration)) {
            $configuration = json_decode($configuration, true);
        }

        if (! is_array($configuration)) {
            return null;
        }

        $amount = (int) ($configuration['amount'] ?? 0);
        $type = $configuration['type'] ?? null;

        if (! in_array($amount, self::AMOUNTS, true) || ! in_array($type, self::TYPES, true)) {
            return null;
        }

        $normalized = [
            'amount' => $amount,
            'type' => $type,
            'isDecaf' => filter_var($configuration['isDecaf'] ?? false, FILTER_VALIDATE_BOOLEAN),
        ];

        if ($type === 'mix') {
            $normalized['mix'] = [
                'espresso' => (int) ($configuration['mix']['espresso'] ?? 0),
                'filter' => (int) ($configuration['mix']['filter'] ?? 0),
            ];
        }

        return $normalized;
    }

    /**
     * Smí zákazník konfiguraci měnit? Jediné místo, které to rozhoduje - čte ho karta
     * předplatného, formulář i samotné uložení.
     *
     * @return array{visible: bool, allowed: bool, reason: ?string, message: ?string, frequency_locked: bool}
     */
    public function eligibility(Subscription $subscription): array
    {
        $deny = fn (string $reason, ?string $message, bool $visible) => [
            'visible' => $visible,
            'allowed' => false,
            'reason' => $reason,
            'message' => $message,
            'frequency_locked' => false,
        ];

        // Bez vstupu do změny: dárek a bezplatné předplatné mají obsah daný jinde,
        // jednorázový box není předplatné, ukončené a nezaplacené-nové není co měnit
        if ($subscription->isGift()
            || $subscription->isComplimentary()
            || (int) $subscription->frequency_months === 0
            || ! in_array($subscription->status, ['active', 'paused', 'unpaid'], true)
            || $subscription->stripe_subscription_id) {
            return $deny('not_available', __('flash.subscription.config_change_not_allowed'), false);
        }

        if ($this->normalize($subscription->configuration) === null || $subscription->configured_price === null) {
            return $deny('unsupported', __('flash.subscription.config_change_contact_support'), true);
        }

        // Billing date už prošel a běží upomínání - "Zaplatit nyní" strhává částku
        // zmrazenou v pending_invoice_amount a retry jede pod stejným Stripe klíčem
        if ($subscription->isUnpaid()) {
            return $deny('unpaid', __('flash.subscription.config_change_unpaid'), true);
        }

        // Pauza po třech neúspěšných platbách je pořád nevyřešená platba - stejně jako
        // u unpaid se konfigurace měnit nesmí, dokud předplatné znovu neběží
        if ($subscription->isPaymentFailurePause()) {
            return $deny('payment_failure_pause', __('flash.subscription.config_change_payment_failure'), true);
        }

        if ($subscription->isAdminLocked()) {
            return $deny('admin_locked', __('flash.subscription.config_change_not_allowed'), true);
        }

        if ($subscription->isActive() && $this->shipmentService->isBillingInFlight($subscription)) {
            return $deny('billing_in_progress', __('flash.subscription.config_change_billing_in_progress'), true);
        }

        return [
            'visible' => true,
            'allowed' => true,
            'reason' => null,
            'message' => null,
            // Obnovení pauzy nezaplacený řádek smaže a datum přepočítá z živé frekvence,
            // takže by změna frekvence tiše posunula slíbený termín obnovení
            'frequency_locked' => $subscription->isPaused(),
        ];
    }

    /**
     * Všechno, co formulář změny potřebuje - předpočítané na serveru, aby JS jen vybíral
     * z hotových hodnot a nemusel znát měnu, ceník ani kalendář rozesílek.
     */
    public function formData(Subscription $subscription): array
    {
        $current = $this->normalize($subscription->configuration);
        $currency = SubscriptionPricing::currencyOf($subscription);
        $currentFrequency = (int) $subscription->frequency_months;
        $eligibility = $this->eligibility($subscription);
        $locked = $this->shipmentService->lockedShipment($subscription);
        $scheduled = $subscription->scheduledConfigurationChange()->first();
        $prices = $this->priceTable($subscription, $current, $currency);

        // Termín prvního boxu v nové konfiguraci a platby za něj pro každou frekvenci
        $baseDate = $this->currentNextUnpaidDate($subscription, $locked !== null);
        $baseBilling = $subscription->next_billing_date?->copy();
        $dates = [];

        foreach (self::FREQUENCIES as $frequency) {
            $replans = $subscription->isActive() && ($locked !== null || $frequency !== $currentFrequency);

            $boxDate = $replans
                ? ($this->shipmentService->previewReplannedShipmentDate($subscription, $frequency) ?? $baseDate)
                : $baseDate;
            $billingDate = $replans
                ? ($this->shipmentService->previewReplannedBillingDate($subscription, $frequency) ?? $baseBilling)
                : $baseBilling;

            $dates[$frequency] = [
                'box' => $boxDate?->format('j. n. Y'),
                'billing' => $subscription->isPaused() ? null : $billingDate?->format('j. n. Y'),
            ];
        }

        return [
            'eligibility' => $eligibility,
            'current' => $current,
            'current_frequency' => $currentFrequency,
            'currency' => $currency,
            'mode' => $locked ? 'scheduled' : 'immediate',
            'locked_shipment_date' => $locked?->shipment_date,
            'scheduled_change' => $scheduled,
            'discount' => $this->activeDiscount($subscription),
            'prices' => $prices,
            'list_prices' => collect(self::AMOUNTS)->mapWithKeys(fn ($amount) => [
                $amount => CurrencyHelper::formatByCurrency(BoxPricing::boxPrice($amount, $currency), $currency),
            ])->all(),
            'decaf_surcharge_formatted' => CurrencyHelper::formatByCurrency(BoxPricing::decafSurcharge($currency), $currency),
            'dates' => $dates,
            'frequency_blocked_by_addons' => $this->frequencyBlockedByAddons($subscription, $locked !== null),
        ];
    }

    /**
     * Co okno zrušení v účtu nabídne místo zrušení: frekvence o krok nižší nebo vyšší,
     * box o velikost menší nebo větší a M box. Null = nabídnout nejde (předplatné je už
     * na kraji škály, frekvence je zamčená, nebo se konfigurace teď měnit nesmí).
     *
     * Ceny jsou celková částka za zásilku (box − sleva + doprava), tedy to, co zákazník
     * skutečně platí a co vidí na kartě předplatného.
     *
     * @return array<string, array|null> Klíče viz CANCEL_ALTERNATIVES
     */
    public function cancellationAlternatives(Subscription $subscription): array
    {
        $alternatives = array_fill_keys(self::CANCEL_ALTERNATIVES, null);
        $eligibility = $this->eligibility($subscription);

        if (! $eligibility['allowed']) {
            return $alternatives;
        }

        $current = $this->normalize($subscription->configuration);
        $prices = $this->priceTable($subscription, $current, SubscriptionPricing::currencyOf($subscription));
        $decaf = (int) $current['isDecaf'];
        $currentPrice = $prices[$current['amount'].'-'.$decaf];

        $box = fn (int $amount) => [
            'amount' => $amount,
            'code' => BoxPricing::SIZES[$amount],
            'grams' => BoxPricing::grams($amount),
            'total' => $prices[$amount.'-'.$decaf]['total'],
            'total_formatted' => $prices[$amount.'-'.$decaf]['total_formatted'],
            'current_total_formatted' => $currentPrice['total_formatted'],
        ];

        $sizeIndex = array_search($current['amount'], self::AMOUNTS, true);

        if ($sizeIndex > 0) {
            $alternatives['smaller_box'] = $box(self::AMOUNTS[$sizeIndex - 1]);

            // "Je to drahé": nejmenší box, ale jen když opravdu vyjde levněji - starší
            // předplatné může mít za větší box cenu pod dnešním ceníkem
            $smallest = $box(self::AMOUNTS[0]);
            if ($smallest['total'] < $currentPrice['total']) {
                $alternatives['m_box'] = $smallest;
            }
        }

        if (isset(self::AMOUNTS[$sizeIndex + 1])) {
            $alternatives['bigger_box'] = $box(self::AMOUNTS[$sizeIndex + 1]);
        }

        $frequencyIndex = array_search((int) $subscription->frequency_months, self::FREQUENCIES, true);
        $frequencyChangeable = $frequencyIndex !== false
            && ! $eligibility['frequency_locked']
            && ! $this->frequencyBlockedByAddons(
                $subscription,
                $this->shipmentService->lockedShipment($subscription) !== null,
            );

        if ($frequencyChangeable) {
            // Delší interval = méně kávy
            if (isset(self::FREQUENCIES[$frequencyIndex + 1])) {
                $alternatives['lower_frequency'] = ['frequency' => self::FREQUENCIES[$frequencyIndex + 1]];
            }

            if ($frequencyIndex > 0) {
                $alternatives['higher_frequency'] = ['frequency' => self::FREQUENCIES[$frequencyIndex - 1]];
            }
        }

        return $alternatives;
    }

    /**
     * Ceny pro každou velikost × decaf podle stejného pravidla jako při uložení.
     *
     * @return array<string, array> Klíč "{amount}-{0|1 decaf}"
     */
    protected function priceTable(Subscription $subscription, ?array $current, string $currency): array
    {
        $prices = [];

        foreach (self::AMOUNTS as $amount) {
            foreach ([false, true] as $isDecaf) {
                $candidate = ['amount' => $amount, 'isDecaf' => $isDecaf];
                $newPrice = $this->priceFor($subscription, $current, $candidate);
                $box = $newPrice ?? (float) $subscription->configured_price;
                // Beze změny ceny se na slevu nesahá, takže platí ta uložená
                $discount = $newPrice === null
                    ? SubscriptionPricing::discount($subscription)
                    : ($this->discountFor($subscription, $box) ?? 0.0);
                $breakdown = SubscriptionPricing::fromValues(
                    $box,
                    $discount,
                    SubscriptionPricing::shippingCost($subscription),
                    $currency,
                );

                // Cena boxu po slevě z kupónu, bez dopravy - ukazuje se přímo u velikostí
                $discounted = SubscriptionPricing::roundFor(max(0.0, $breakdown->box - $breakdown->discount), $currency);

                $prices[$amount.'-'.(int) $isDecaf] = [
                    'box' => $breakdown->box,
                    'discount' => $breakdown->discount,
                    'discounted' => $discounted,
                    'shipping' => $breakdown->shipping,
                    'total' => $breakdown->total,
                    'box_formatted' => CurrencyHelper::formatByCurrency($breakdown->box, $currency),
                    'discount_formatted' => CurrencyHelper::formatByCurrency($breakdown->discount, $currency),
                    'discounted_formatted' => CurrencyHelper::formatByCurrency($discounted, $currency),
                    'shipping_formatted' => CurrencyHelper::formatByCurrency($breakdown->shipping, $currency),
                    'total_formatted' => CurrencyHelper::formatByCurrency($breakdown->total, $currency),
                ];
            }
        }

        return $prices;
    }

    /**
     * K nejbližšímu nezaplacenému boxu je přibalená objednávka a změna frekvence by ho
     * posunula - request() ji odmítne (addons_attached), proto se ani nenabízí.
     */
    protected function frequencyBlockedByAddons(Subscription $subscription, bool $hasLockedShipment): bool
    {
        return ! $hasLockedShipment
            && $subscription->isActive()
            && $this->shipmentService->nextUnpaidShipmentHasAddons($subscription);
    }

    /**
     * Zadá změnu konfigurace: použije ji hned, nebo ji naplánuje za zamčený box.
     *
     * @return array{success: bool, outcome: string, reason: ?string, message: string, change: ?SubscriptionConfigurationChange, subscription: Subscription}
     *                                                                                                                                                         outcome: applied | scheduled | unchanged | scheduled_cancelled | refused
     */
    public function request(
        Subscription $subscription,
        array $configuration,
        int $frequency,
        ?User $actor = null,
        string $source = SubscriptionConfigurationChange::SOURCE_CUSTOMER,
    ): array {
        $new = $this->normalize($configuration);

        if ($new === null || ! in_array($frequency, self::FREQUENCIES, true) || ! $this->mixIsValid($new)) {
            return $this->refuse($subscription, 'invalid', __('flash.subscription.config_validation_error'));
        }

        $refresh = [];

        $result = DB::transaction(function () use ($subscription, $new, $frequency, $actor, $source, &$refresh) {
            $sub = Subscription::whereKey($subscription->id)->lockForUpdate()->firstOrFail();

            $eligibility = $this->eligibility($sub);
            if (! $eligibility['allowed']) {
                return $this->refuse($sub, $eligibility['reason'], $eligibility['message']);
            }

            $current = $this->normalize($sub->configuration);
            $currentFrequency = (int) $sub->frequency_months;
            $contentChanged = $new != $current;
            $frequencyChanged = $frequency !== $currentFrequency;
            $scheduled = $sub->scheduledConfigurationChange()->first();

            if (! $contentChanged && ! $frequencyChanged) {
                // Návrat k tomu, co už platí = zrušení naplánované změny
                if ($scheduled) {
                    $this->markCancelled($scheduled, 'reverted');

                    return [
                        'success' => true,
                        'outcome' => 'scheduled_cancelled',
                        'reason' => null,
                        'message' => __('flash.subscription.config_change_scheduled_cancelled'),
                        'change' => $scheduled,
                        'subscription' => $sub,
                    ];
                }

                return [
                    'success' => true,
                    'outcome' => 'unchanged',
                    'reason' => null,
                    'message' => __('flash.subscription.config_unchanged'),
                    'change' => null,
                    'subscription' => $sub,
                ];
            }

            if ($frequencyChanged && $eligibility['frequency_locked']) {
                return $this->refuse($sub, 'frequency_paused', __('flash.subscription.config_change_frequency_paused'));
            }

            $locked = $this->shipmentService->lockedShipment($sub);

            if (! $locked) {
                // Přesun boxu s přibalenou objednávkou by ji osiřel - ať nejdřív odejde
                if ($frequencyChanged && $sub->isActive()
                    && $this->shipmentService->nextUnpaidShipmentHasAddons($sub)
                    && $this->wouldMoveNextUnpaidShipment($sub, $frequency)) {
                    return $this->refuse($sub, 'addons_attached', __('flash.subscription.config_change_addons_attached'));
                }

                $stock = $this->checkNearestShipmentStock($sub, $current, $new, $frequency);
                if (! $stock['available']) {
                    return $this->refuse($sub, 'out_of_stock', $this->outOfStockMessage($stock));
                }
            }

            if ($scheduled) {
                $scheduled->update([
                    'status' => SubscriptionConfigurationChange::STATUS_SUPERSEDED,
                    'cancelled_at' => now(),
                    'cancel_reason' => 'superseded',
                ]);
            }

            $change = new SubscriptionConfigurationChange([
                'subscription_id' => $sub->id,
                'requested_by_user_id' => $actor?->id,
                'source' => $source,
                'status' => SubscriptionConfigurationChange::STATUS_SCHEDULED,
                'old_configuration' => $current,
                'new_configuration' => $new,
                'old_frequency_months' => $currentFrequency,
                'new_frequency_months' => $frequency,
                'old_configured_price' => $sub->configured_price,
                'new_configured_price' => $this->priceFor($sub, $current, $new),
                'old_discount_amount' => $sub->discount_amount,
                'old_next_billing_date' => $sub->next_billing_date,
            ]);

            if ($locked) {
                // Platí až pro box za tím zaplaceným; přesné datum určí noční příkaz
                $change->effective_shipment_date = $sub->isActive()
                    ? ($this->shipmentService->previewReplannedShipmentDate($sub, $frequency)
                        ?? $this->currentNextUnpaidDate($sub, true))
                    : $this->currentNextUnpaidDate($sub, true);
                // Sleva z kupónu po změně - jen pro zobrazení (karta, admin). Při použití
                // změny se počítá znovu, do té doby mohla sleva doběhnout.
                $change->new_discount_amount = $change->new_configured_price !== null
                    ? $this->discountFor($sub, (float) $change->new_configured_price)
                    : null;
                $change->save();

                Log::info('Subscription configuration change scheduled', [
                    'subscription_id' => $sub->id,
                    'change_id' => $change->id,
                    'locked_shipment_id' => $locked->id,
                    'source' => $source,
                ]);

                return [
                    'success' => true,
                    'outcome' => 'scheduled',
                    'reason' => null,
                    'message' => $this->withEffectiveDate(
                        __('flash.subscription.config_change_scheduled'),
                        $change->effective_shipment_date,
                    ),
                    'change' => $change,
                    'subscription' => $sub,
                ];
            }

            $refresh = $this->applyChange($sub, $change, 'request');

            return [
                'success' => true,
                'outcome' => 'applied',
                'reason' => null,
                'message' => $this->withEffectiveDate(
                    __('flash.subscription.config_changed'),
                    $change->effective_shipment_date,
                ),
                'change' => $change,
                'subscription' => $sub,
            ];
        });

        // Až po transakci: přepočet rezervací si otevírá vlastní transakci a je těžký
        if ($result['outcome'] === 'applied') {
            $this->refreshReservations($result['subscription'], $refresh);
        }

        return $result;
    }

    /**
     * Zruší naplánovanou změnu (zákazník si to rozmyslel dřív, než zaplacený box odešel).
     */
    public function cancelScheduled(Subscription $subscription, string $reason = 'customer'): bool
    {
        return DB::transaction(function () use ($subscription, $reason) {
            $sub = Subscription::whereKey($subscription->id)->lockForUpdate()->firstOrFail();
            $scheduled = $sub->scheduledConfigurationChange()->first();

            if (! $scheduled) {
                return false;
            }

            $this->markCancelled($scheduled, $reason);

            return true;
        });
    }

    /**
     * Noční průchod: použije naplánované změny, u kterých už žádný zamčený box nezbývá.
     *
     * @return array{applied: array, waiting: array, cancelled: array, failed: array, stock_warnings: array}
     */
    public function applyDueScheduledChanges(?int $subscriptionId = null, bool $dryRun = false): array
    {
        $report = ['applied' => [], 'waiting' => [], 'cancelled' => [], 'failed' => [], 'stock_warnings' => []];

        $changes = SubscriptionConfigurationChange::scheduled()
            ->when($subscriptionId, fn ($q) => $q->where('subscription_id', $subscriptionId))
            ->orderBy('id')
            ->get();

        foreach ($changes as $pending) {
            $refresh = [];

            try {
                $outcome = DB::transaction(function () use ($pending, $dryRun, &$refresh, &$report) {
                    $change = SubscriptionConfigurationChange::whereKey($pending->id)->lockForUpdate()->first();

                    if (! $change || ! $change->isScheduled()) {
                        return null;
                    }

                    $sub = Subscription::whereKey($change->subscription_id)->lockForUpdate()->first();
                    $entry = [
                        'change_id' => $change->id,
                        'subscription_id' => $change->subscription_id,
                        'subscription_number' => $sub?->subscription_number,
                    ];

                    if (! $sub
                        || in_array($sub->status, ['cancelled', 'completed'], true)
                        || $sub->isGift()
                        || $sub->isComplimentary()) {
                        if (! $dryRun) {
                            $this->markCancelled($change, 'subscription_ended');
                        }

                        return ['bucket' => 'cancelled', 'entry' => $entry + ['reason' => 'subscription_ended']];
                    }

                    $wait = match (true) {
                        $this->shipmentService->lockedShipment($sub) !== null => 'locked_shipment',
                        $sub->isUnpaid() => 'unpaid',
                        // Dokud není platba vyřešená, platí totéž co pro zákazníka: beze změny
                        $sub->isPaymentFailurePause() => 'payment_failure_pause',
                        ! in_array($sub->status, ['active', 'paused'], true) => 'status_'.$sub->status,
                        $sub->isActive() && $this->shipmentService->isBillingInFlight($sub) => 'billing_in_flight',
                        default => null,
                    };

                    if ($wait) {
                        return ['bucket' => 'waiting', 'entry' => $entry + ['reason' => $wait]];
                    }

                    // Zákazník má změnu potvrzenou, takže se použije i při nedostatku kávy
                    // pro nejbližší rozesílku - admin se to ale musí dozvědět
                    $stock = $this->checkNearestShipmentStock(
                        $sub,
                        $this->normalize($sub->configuration),
                        $change->new_configuration,
                        (int) $change->new_frequency_months,
                    );

                    if (! $stock['available']) {
                        $report['stock_warnings'][] = $entry + [
                            'shipment_date' => $stock['schedule']?->shipment_date?->toDateString(),
                            'out_of_stock' => $stock['out_of_stock'],
                            'missing_slots' => $stock['missing_slots'],
                        ];
                    }

                    if ($dryRun) {
                        return ['bucket' => 'applied', 'entry' => $entry + ['dry_run' => true]];
                    }

                    $refresh = $this->applyChange($sub, $change, 'scheduled');

                    return ['bucket' => 'applied', 'entry' => $entry + [
                        'effective_shipment_date' => $change->effective_shipment_date?->toDateString(),
                        'subscription' => $sub,
                    ]];
                });

                if (! $outcome) {
                    continue;
                }

                if ($outcome['bucket'] === 'applied' && isset($outcome['entry']['subscription'])) {
                    $this->refreshReservations($outcome['entry']['subscription'], $refresh);
                    unset($outcome['entry']['subscription']);
                }

                $report[$outcome['bucket']][] = $outcome['entry'];
            } catch (\Throwable $e) {
                Log::error('Failed to apply scheduled subscription configuration change', [
                    'change_id' => $pending->id,
                    'subscription_id' => $pending->subscription_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $report['failed'][] = [
                    'change_id' => $pending->id,
                    'subscription_id' => $pending->subscription_id,
                    'reason' => $e->getMessage(),
                ];
            }
        }

        return $report;
    }

    /**
     * Nová cena boxu, nebo null, když se cena nemění.
     *
     * Mění ji jen velikost a decaf. Změna druhu, mixu nebo frekvence nechává
     * configured_price být, i kdyby se lišila od dnešního ceníku.
     */
    public function priceFor(Subscription $subscription, ?array $current, array $new): ?float
    {
        if ($current
            && (int) $current['amount'] === (int) $new['amount']
            && (bool) $current['isDecaf'] === (bool) $new['isDecaf']) {
            return null;
        }

        return BoxPricing::configuredPrice(
            (int) $new['amount'],
            (bool) $new['isDecaf'],
            SubscriptionPricing::currencyOf($subscription),
        );
    }

    /**
     * Sleva z běžícího kupónu pro novou cenu boxu, nebo null, když žádná sleva neběží
     * (pak se na ni nesahá).
     *
     * Běžící sleva platí i pro upravenou konfiguraci:
     *  - procentní kupón se spočítá z nové ceny,
     *  - fixní kupón dál dává svou částku, nejvýš do ceny boxu. Když byla sleva dřív
     *    zastropená levnějším boxem, u dražšího se vrátí až na hodnotu kupónu - níž než
     *    dosud ale nespadne (kupón mohl mezitím někdo v adminu změnit),
     *  - dárkový poukaz a smazaný kupón: dosavadní částka, nejvýš do ceny boxu.
     *
     * Počet zbývajících plateb se slevou (discount_months_remaining) se nemění.
     * Coupon::calculateSubscriptionDiscount se tu použít nedá, bere měnu ze session.
     */
    public function discountFor(Subscription $subscription, float $price): ?float
    {
        if (! SubscriptionPricing::hasActiveDiscount($subscription)) {
            return null;
        }

        $coupon = $subscription->coupon;
        $stored = (float) $subscription->discount_amount;

        if ($coupon && $coupon->discount_type_subscription === 'percentage') {
            return round($price * ((float) $coupon->discount_value_subscription / 100), 2);
        }

        if ($coupon && $coupon->discount_type_subscription === 'fixed' && ! $coupon->is_gift_voucher) {
            $couponValue = (float) (SubscriptionPricing::currencyOf($subscription) === 'EUR'
                ? $coupon->discount_value_subscription_eur
                : $coupon->discount_value_subscription);

            return min(max($stored, $couponValue), $price);
        }

        return min($stored, $price);
    }

    /**
     * Popis běžící slevy pro formulář změny, kartu předplatného a e-mail. Null = žádná
     * sleva neběží.
     *
     * @return array{code: ?string, months_remaining: ?int}|null
     */
    public function activeDiscount(Subscription $subscription): ?array
    {
        if (! SubscriptionPricing::hasActiveDiscount($subscription)) {
            return null;
        }

        return [
            'code' => $subscription->coupon_code ?: $subscription->coupon?->code,
            // null = sleva bez omezení, jinak počet plateb, na které ještě platí
            'months_remaining' => $subscription->discount_months_remaining === null
                ? null
                : (int) $subscription->discount_months_remaining,
        ];
    }

    /**
     * Rozpad ceny, kterou bude předplatné platit po dané změně - box, sleva z běžícího
     * kupónu, doprava a celkem. U naplánované změny se počítá z ní, protože na předplatném
     * ještě není; po jejím použití odpovídá SubscriptionPricing::forRecurringPayment().
     */
    public function breakdownAfter(Subscription $subscription, SubscriptionConfigurationChange $change): \App\Helpers\SubscriptionPriceBreakdown
    {
        if (! $change->isScheduled() || $change->new_configured_price === null) {
            return SubscriptionPricing::forRecurringPayment($subscription);
        }

        $box = (float) $change->new_configured_price;

        return SubscriptionPricing::fromValues(
            $box,
            $this->discountFor($subscription, $box) ?? 0.0,
            SubscriptionPricing::shippingCost($subscription),
            SubscriptionPricing::currencyOf($subscription),
        );
    }

    /**
     * Zapíše změnu do předplatného a ledgeru. Volá se uvnitř transakce se zamčeným řádkem
     * předplatného a jen tehdy, když žádný zamčený box nezbývá.
     *
     * Ukládá přes saveQuietly: SubscriptionStockObserver by rezervace přepočítal uprostřed
     * transakce (vlastní vnořená transakce, výjimky polyká) a ještě před zápisy do ledgeru.
     * Rezervace proto obnovuje volající až po commitu - viz refreshReservations().
     *
     * @return Carbon[] Data rozesílek, kterým se změnily rezervace
     */
    protected function applyChange(Subscription $sub, SubscriptionConfigurationChange $change, string $trigger): array
    {
        $new = $change->new_configuration;
        $frequency = (int) $change->new_frequency_months;
        $oldConfiguration = is_array($sub->configuration) ? $sub->configuration : [];
        $current = $this->normalize($sub->configuration);
        $frequencyChanged = $frequency !== (int) $sub->frequency_months;
        $amountChanged = ! $current || (int) $current['amount'] !== (int) $new['amount'];

        $oldNextDate = $this->shipmentService->nextUnpaidShipment($sub)?->shipment_date?->copy();
        $oldBillingDate = $sub->next_billing_date?->copy();
        // U naplánované změny se "před" bere až teď - mezi zadáním a použitím mohla
        // proběhnout platba, která slevu z kupónu odečetla na nulu
        $oldPrice = $sub->configured_price;
        $oldDiscount = $sub->discount_amount;

        // Ostatní klíče konfigurace zůstávají; mix se u ne-mixu nuluje stejně jako z konfigurátoru
        $sub->configuration = array_merge($oldConfiguration, $new, [
            'mix' => $new['mix'] ?? ['espresso' => 0, 'filter' => 0],
            'frequency' => $frequency,
        ]);
        $sub->frequency_months = $frequency;

        if ($change->new_configured_price !== null) {
            $price = (float) $change->new_configured_price;
            $discount = $this->discountFor($sub, $price);

            $sub->configured_price = $price;
            if ($discount !== null) {
                $sub->discount_amount = $discount;
            }
        }

        $sub->saveQuietly();

        // Přesun boxu jen u aktivního předplatného: pauza má termín obnovení daný a
        // resume si nezaplacený řádek stejně přepočítá po svém
        $replan = null;
        if ($frequencyChanged && $sub->isActive()) {
            $replan = $this->shipmentService->replanNextUnpaidShipment($sub, 'frequency_change');

            if ($replan) {
                // Musí sedět s řádkem: next_billing_date mimo billing date boxu rozhodí cyklus
                $sub->next_billing_date = $replan['billing_date'];
                $sub->saveQuietly();
            }
        }

        if ($amountChanged) {
            $this->shipmentService->syncPackageDimensionsToUnpaidShipments($sub);
        }

        $newNextDate = $replan['new_date']
            ?? $this->shipmentService->nextUnpaidShipment($sub)?->shipment_date?->copy();

        $change->fill([
            'status' => SubscriptionConfigurationChange::STATUS_APPLIED,
            'applied_at' => now(),
            'old_configuration' => $current,
            'old_configured_price' => $oldPrice,
            'old_discount_amount' => $oldDiscount,
            'new_discount_amount' => $sub->discount_amount,
            'effective_shipment_date' => $newNextDate ?? $change->effective_shipment_date,
            'old_next_shipment_date' => $oldNextDate,
            'new_next_shipment_date' => $newNextDate,
            'old_next_billing_date' => $oldBillingDate,
            'new_next_billing_date' => $sub->next_billing_date,
        ])->save();

        Log::info('Subscription configuration changed', [
            'subscription_id' => $sub->id,
            'subscription_number' => $sub->subscription_number,
            'change_id' => $change->id,
            'trigger' => $trigger,
            'source' => $change->source,
            'old_configuration' => $current,
            'new_configuration' => $new,
            'old_frequency_months' => $change->old_frequency_months,
            'new_frequency_months' => $frequency,
            'old_configured_price' => $change->old_configured_price,
            'new_configured_price' => $sub->configured_price,
            'old_next_shipment_date' => $oldNextDate?->toDateString(),
            'new_next_shipment_date' => $newNextDate?->toDateString(),
            'next_billing_date' => $sub->next_billing_date?->toDateString(),
        ]);

        return array_values(array_filter([$oldNextDate, $newNextDate]));
    }

    /**
     * Dostupnost káv pro NEJBLIŽŠÍ rozesílku otevřenou pro objednávky (stejný měsíc, do
     * kterého prodává konfigurátor). Pozdější rozesílky se nekontrolují - káva na ně se
     * teprve nakupuje.
     *
     * Kontroluje se jen tehdy, když box po změně do té rozesílky spadá a zároveň se pro
     * ni něco mění: jiný obsah boxu, nebo ho do ní změna frekvence nově přitáhla.
     *
     * @return array{available: bool, out_of_stock: array, missing_slots: array, schedule: ?ShipmentSchedule}
     */
    protected function checkNearestShipmentStock(Subscription $sub, ?array $current, array $new, int $frequency): array
    {
        $ok = ['available' => true, 'out_of_stock' => [], 'missing_slots' => [], 'schedule' => null];

        $targetMonth = SubscriptionHelper::getOrderingTargetMonth();
        $schedule = ShipmentSchedule::getForMonth($targetMonth->year, $targetMonth->month);

        if (! $schedule || ! $schedule->hasCoffeeSlotsConfigured()) {
            return $ok;
        }

        $currentDate = $this->currentNextUnpaidDate($sub, false);
        $frequencyChanged = $frequency !== (int) $sub->frequency_months;

        $effectiveDate = $frequencyChanged && $sub->isActive()
            ? ($this->shipmentService->previewReplannedShipmentDate($sub, $frequency) ?? $currentDate)
            : $currentDate;

        $inNearest = fn (?Carbon $date) => $date !== null && $date->isSameMonth($schedule->shipment_date);

        if (! $inNearest($effectiveDate)) {
            return $ok;
        }

        $alreadyThere = $inNearest($currentDate);

        if ($alreadyThere && $new == $current) {
            return $ok;
        }

        return $this->reservationService->checkAvailabilityForChange(
            $new,
            $alreadyThere ? $current : null,
            $schedule,
        ) + ['schedule' => $schedule];
    }

    /**
     * Datum nejbližšího nezaplaceného boxu při STÁVAJÍCÍ frekvenci.
     *
     * Se zamčeným boxem ještě řádek zpravidla neexistuje (vzniká až při odeslání), takže
     * se dopočítá od zamčeného boxu.
     */
    protected function currentNextUnpaidDate(Subscription $sub, bool $hasLockedShipment): ?Carbon
    {
        $nextUnpaid = $this->shipmentService->nextUnpaidShipment($sub);

        if ($nextUnpaid) {
            return $nextUnpaid->shipment_date->copy();
        }

        if ($hasLockedShipment || $sub->isActive()) {
            $date = $this->shipmentService->previewReplannedShipmentDate($sub, max(1, (int) $sub->frequency_months));

            if ($date) {
                return $date;
            }
        }

        return $this->shipmentService->calculateNextShipmentDate($sub);
    }

    /**
     * Posunula by změna frekvence nejbližší nezaplacený box do jiné rozesílky?
     */
    protected function wouldMoveNextUnpaidShipment(Subscription $sub, int $frequency): bool
    {
        $current = $this->shipmentService->nextUnpaidShipment($sub)?->shipment_date;
        $target = $this->shipmentService->previewReplannedShipmentDate($sub, $frequency);

        return $current !== null && $target !== null && ! $current->isSameMonth($target);
    }

    /**
     * Mix musí dát dohromady přesně velikost boxu a obsahovat obě kávy - stejné pravidlo
     * jako SubscriptionController::configureCheckout.
     */
    protected function mixIsValid(array $configuration): bool
    {
        if ($configuration['type'] !== 'mix') {
            return true;
        }

        $espresso = (int) ($configuration['mix']['espresso'] ?? 0);
        $filter = (int) ($configuration['mix']['filter'] ?? 0);

        return $espresso >= 1 && $filter >= 1 && $espresso + $filter === (int) $configuration['amount'];
    }

    protected function outOfStockMessage(array $stock): string
    {
        $date = $stock['schedule']?->shipment_date?->format('j. n. Y') ?? '';

        if (! empty($stock['out_of_stock'])) {
            return __('flash.subscription.config_change_out_of_stock', [
                'date' => $date,
                'coffees' => implode(', ', $stock['out_of_stock']),
            ]);
        }

        return __('flash.subscription.config_change_unavailable', ['date' => $date]);
    }

    protected function withEffectiveDate(string $message, ?Carbon $date): string
    {
        return $date
            ? $message.' '.__('flash.subscription.config_change_applies_from', ['date' => $date->format('j. n. Y')])
            : $message;
    }

    protected function markCancelled(SubscriptionConfigurationChange $change, string $reason): void
    {
        $change->update([
            'status' => SubscriptionConfigurationChange::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancel_reason' => $reason,
        ]);

        Log::info('Scheduled subscription configuration change cancelled', [
            'subscription_id' => $change->subscription_id,
            'change_id' => $change->id,
            'reason' => $reason,
        ]);
    }

    /**
     * Přepočet rezervací skladu pro dotčené rozesílky + tu nejbližší (obsah boxu se mohl
     * změnit i bez přesunu). Každá zvlášť v try/catch, stejně jako u pauzy a obnovení:
     * selhání rezervací nesmí shodit už zapsanou změnu.
     *
     * @param  Carbon[]  $dates
     */
    protected function refreshReservations(Subscription $subscription, array $dates): void
    {
        $schedules = collect($dates)
            ->map(fn (Carbon $date) => ShipmentSchedule::getForMonth($date->year, $date->month))
            ->push(ShipmentSchedule::getNextShipment())
            ->filter()
            ->unique('id');

        foreach ($schedules as $schedule) {
            if ($schedule->isPast()) {
                continue;
            }

            try {
                $this->reservationService->updateReservationsForSchedule($schedule);
            } catch (\Exception $e) {
                Log::error('Failed to update reservations after configuration change', [
                    'subscription_id' => $subscription->id,
                    'schedule_id' => $schedule->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function refuse(Subscription $subscription, ?string $reason, ?string $message): array
    {
        return [
            'success' => false,
            'outcome' => 'refused',
            'reason' => $reason,
            'message' => $message ?? __('flash.subscription.config_change_not_allowed'),
            'change' => null,
            'subscription' => $subscription,
        ];
    }
}
