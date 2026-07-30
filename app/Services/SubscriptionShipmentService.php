<?php

namespace App\Services;

use App\Models\ShipmentSchedule;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionShipment;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

/**
 * DTO: Complete shipment information for a subscription
 */
class SubscriptionShipmentInfo
{
    public function __construct(
        public Subscription $subscription,
        public ?SubscriptionShipment $lastSentShipment,
        public ?SubscriptionShipment $nextShipment,
        public ?PauseInfo $pauseInfo,
        public Collection $history,
    ) {}

    /**
     * Get the date of the last sent shipment
     */
    public function lastSentDate(): ?Carbon
    {
        return $this->lastSentShipment?->shipment_date;
    }

    /**
     * Get the date of the next scheduled shipment
     */
    public function nextShipmentDate(): ?Carbon
    {
        return $this->nextShipment?->shipment_date;
    }

    /**
     * Check if subscription is currently paused
     */
    public function isPaused(): bool
    {
        return $this->pauseInfo !== null;
    }

    /**
     * Check if there's an upcoming shipment
     */
    public function hasUpcomingShipment(): bool
    {
        return $this->nextShipment !== null;
    }

    /**
     * Get tracking URL if last shipment was sent
     */
    public function lastTrackingUrl(): ?string
    {
        return $this->lastSentShipment?->packeta_tracking_url;
    }

    /**
     * Check if next shipment is paid
     */
    public function isNextShipmentPaid(): bool
    {
        return $this->nextShipment?->subscription_payment_id !== null;
    }
}

/**
 * DTO: Pause information
 */
class PauseInfo
{
    public function __construct(
        public ?Carbon $pausedUntil,
        public int $skippedCount,
        public Collection $skippedDates,
        public ?Carbon $resumeDate,
    ) {}
}

/**
 * DTO: Náhled zrušení předplatného. Popisuje PŘESNĚ to, co cancelSubscription() udělá –
 * modal na dashboardu čerpá z téhož výpočtu, aby se informace nemohla rozejít s akcí.
 */
class CancellationPreview
{
    public function __construct(
        /** @var Collection<SubscriptionShipment> Zaplacené pending zásilky, které BUDOU ještě doručeny (dle data). */
        public Collection $paidShipments,
        /** Datum poslední zaplacené zásilky = budoucí ends_at (null když se ruší okamžitě). */
        public ?Carbon $lastPaidDate,
        public int $paidCount,
        /** Počet nezaplacených pending zásilek, které se zruší. */
        public int $unpaidToCancelCount,
        /** Počet addon objednávek, které se odpojí k ruční revizi. */
        public int $addonToDetachCount,
        /** True = žádné zaplacené pokrytí → předplatné skončí okamžitě. */
        public bool $endsImmediately,
    ) {}
}

/**
 * Central service for all subscription shipment operations
 * Uses subscription_shipments table as the single source of truth
 */
class SubscriptionShipmentService
{
    /**
     * Get complete shipment information for a subscription
     * Use this from dashboards, emails, admin panels
     */
    public function getShipmentInfo(Subscription $subscription): SubscriptionShipmentInfo
    {
        return new SubscriptionShipmentInfo(
            subscription: $subscription,
            lastSentShipment: $this->getLastSentShipment($subscription),
            nextShipment: $this->getNextShipment($subscription),
            pauseInfo: $this->getPauseInfo($subscription),
            history: $this->getShipmentHistory($subscription),
        );
    }

    /**
     * Get the last sent shipment
     */
    public function getLastSentShipment(Subscription $subscription): ?SubscriptionShipment
    {
        return $subscription->shipments()
            ->whereIn('status', ['sent', 'delivered'])
            ->orderBy('shipment_date', 'desc')
            ->first();
    }

    /**
     * Get the next scheduled shipment (creates one if needed)
     */
    public function getNextShipment(Subscription $subscription): ?SubscriptionShipment
    {
        // First look for existing pending shipment
        $pending = $subscription->shipments()
            ->where('status', 'pending')
            ->orderBy('shipment_date', 'asc')
            ->first();

        if ($pending) {
            return $pending;
        }

        // For cancelled/completed subscriptions, don't create new shipments
        if (in_array($subscription->status, ['cancelled', 'completed'])) {
            return null;
        }

        // Create pending shipment for active/paused subscriptions
        return $this->ensurePendingShipmentExists($subscription);
    }

    /**
     * Shortcut: Get just the next shipment date
     */
    public function getNextShipmentDate(Subscription $subscription): ?Carbon
    {
        return $this->getNextShipment($subscription)?->shipment_date;
    }

    /**
     * Get the first UNPAID shipment date (for pause modal)
     * Returns the first pending shipment without a linked payment
     */
    public function getFirstUnpaidShipmentDate(Subscription $subscription): ?Carbon
    {
        // Look for pending shipment without payment
        $unpaidPending = $subscription->shipments()
            ->where('status', 'pending')
            ->whereNull('subscription_payment_id')
            ->where('shipment_date', '>', now())
            ->orderBy('shipment_date', 'asc')
            ->first();

        if ($unpaidPending) {
            return $unpaidPending->shipment_date;
        }

        // All pending shipments are paid - calculate next date after last paid
        $lastPaidPending = $subscription->shipments()
            ->where('status', 'pending')
            ->whereNotNull('subscription_payment_id')
            ->where('shipment_date', '>', now())
            ->orderBy('shipment_date', 'desc')
            ->first();

        if ($lastPaidPending) {
            // Calculate the next shipment date after the last paid one
            $frequencyMonths = max(1, (int) ($subscription->frequency_months ?? 1));
            $nextDate = $lastPaidPending->shipment_date->copy()->addMonths($frequencyMonths);
            $schedule = ShipmentSchedule::getForMonth($nextDate->year, $nextDate->month);

            return $schedule?->shipment_date ?? $nextDate->copy()->day(20);
        }

        // Fallback to regular next shipment date
        return $this->getNextShipmentDate($subscription);
    }

    /**
     * Get pause information for a subscription
     */
    public function getPauseInfo(Subscription $subscription): ?PauseInfo
    {
        if ($subscription->status !== 'paused') {
            return null;
        }

        $skippedShipments = $subscription->shipments()
            ->where('status', 'skipped')
            ->orderBy('shipment_date', 'asc')
            ->get();

        $firstAfterPause = $subscription->shipments()
            ->where('status', 'pending')
            ->orderBy('shipment_date', 'asc')
            ->first();

        return new PauseInfo(
            pausedUntil: $subscription->paused_until_date,
            skippedCount: $skippedShipments->count(),
            skippedDates: $skippedShipments->pluck('shipment_date'),
            resumeDate: $firstAfterPause?->shipment_date,
        );
    }

    /**
     * Get shipment history for display
     */
    public function getShipmentHistory(Subscription $subscription): Collection
    {
        return $subscription->shipments()
            ->with('payment')
            ->orderBy('shipment_date', 'desc')
            ->get();
    }

    /**
     * Ensure a pending shipment exists for the next scheduled date
     * Creates one if missing
     */
    public function ensurePendingShipmentExists(Subscription $subscription): ?SubscriptionShipment
    {
        $nextDate = $this->calculateNextShipmentDate($subscription);

        if (! $nextDate) {
            return null;
        }

        $schedule = ShipmentSchedule::getOrCreateForMonth($nextDate->year, $nextDate->month);
        $payment = $this->findPaymentForShipment($subscription, $schedule->shipment_date);

        return $this->getOrCreateForSchedule($subscription, $schedule, [
            'subscription_payment_id' => $payment?->id,
        ]);
    }

    /**
     * Calculate the next shipment date based on history and frequency
     * This is the core logic that replaces SubscriptionHelper::calculateNextShipmentDate
     */
    public function calculateNextShipmentDate(Subscription $subscription): ?Carbon
    {
        $frequencyMonths = max(1, (int) ($subscription->frequency_months ?? 1));

        // One-time boxes (frequency = 0) ship once
        if ($subscription->frequency_months == 0) {
            if ($subscription->last_shipment_date) {
                return null; // Already shipped
            }

            return $this->getFirstShipmentDate($subscription);
        }

        // Prefer the first future 'pending' shipment if one exists.
        // The subscription_shipments table is the single source of truth and already
        // reflects pauses (skipped rows) and post-pause planning (pending rows).
        // Without this, last_sent + frequency can point at an already-skipped month
        // and downstream availability checks then fail against a month nobody will ship.
        $firstPending = $subscription->shipments()
            ->where('status', 'pending')
            ->where('shipment_date', '>=', today()->startOfDay())
            ->orderBy('shipment_date', 'asc')
            ->first();

        if ($firstPending) {
            return $firstPending->shipment_date->copy()->startOfDay();
        }

        // Get last sent shipment from history
        $lastSent = $this->getLastSentShipment($subscription);

        if (! $lastSent) {
            // No shipments yet - calculate first shipment date
            return $this->getFirstShipmentDate($subscription);
        }

        // Calculate next based on last shipment + frequency
        $nextMonth = $lastSent->shipment_date->copy()->addMonths($frequencyMonths);
        $schedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);

        if ($schedule) {
            return $schedule->shipment_date->copy()->startOfDay();
        }

        // Fallback to 20th
        return $nextMonth->day(20)->startOfDay();
    }

    /**
     * Calculate first shipment date for new subscription
     */
    protected function getFirstShipmentDate(Subscription $subscription): Carbon
    {
        $createdAt = $subscription->starts_at ?? $subscription->created_at;
        $year = $createdAt->year;
        $month = $createdAt->month;

        $schedule = ShipmentSchedule::getForMonth($year, $month);

        // If created before or on billing date, ship this month
        if ($schedule && $createdAt->lte($schedule->billing_date)) {
            return $schedule->shipment_date->copy()->startOfDay();
        }

        // Otherwise, ship next month
        $nextMonth = $createdAt->copy()->addMonthNoOverflow();
        $nextSchedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);

        if ($nextSchedule) {
            return $nextSchedule->shipment_date->copy()->startOfDay();
        }

        // Fallback
        if ($createdAt->day > 15) {
            return $createdAt->copy()->addMonthNoOverflow()->day(20)->startOfDay();
        }

        return $createdAt->copy()->day(20)->startOfDay();
    }

    /**
     * Pause subscription for N iterations
     * Respects already paid shipments - pause starts from first UNPAID shipment
     */
    public function pauseSubscription(Subscription $subscription, int $iterations, string $reason = 'user_request'): void
    {
        $frequencyMonths = max(1, (int) ($subscription->frequency_months ?? 1));

        // 1. Find all paid pending shipments - these should NOT be affected
        $paidPendingShipments = $subscription->shipments()
            ->where('status', 'pending')
            ->whereNotNull('subscription_payment_id')
            ->where('shipment_date', '>=', today())
            ->orderBy('shipment_date', 'asc')
            ->get();

        \Log::info('Paid pending shipments found', [
            'subscription_id' => $subscription->id,
            'count' => $paidPendingShipments->count(),
            'dates' => $paidPendingShipments->pluck('shipment_date')->map->toDateString(),
        ]);

        // 2. Calculate upcoming dates starting from AFTER any paid shipments
        $lastPaidDate = $paidPendingShipments->last()?->shipment_date;
        $startDate = $lastPaidDate
            ? $lastPaidDate->copy()->addMonths($frequencyMonths)
            : $this->calculateNextShipmentDate($subscription);

        if (! $startDate) {
            $startDate = $this->getFirstShipmentDate($subscription);
        }

        // Ensure we start from future date
        $today = Carbon::now()->startOfDay();
        while ($startDate->lt($today)) {
            $startDate = $startDate->copy()->addMonths($frequencyMonths);
        }

        // 3. Generate dates for skipped shipments (starting from first unpaid)
        $skippedDates = collect();
        $candidate = $startDate->copy();

        for ($i = 0; $i < $iterations; $i++) {
            $schedule = ShipmentSchedule::getForMonth($candidate->year, $candidate->month);
            $shipmentDate = $schedule?->shipment_date ?? $candidate->copy()->day(20);
            $skippedDates->push($shipmentDate->copy()->startOfDay());
            $candidate = $candidate->copy()->addMonths($frequencyMonths);
        }

        // 4. Mark these dates as skipped (but preserve already paid shipments)
        foreach ($skippedDates as $date) {
            $schedule = ShipmentSchedule::getOrCreateForMonth($date->year, $date->month);

            // Check if this shipment already has a payment - if so, don't skip it
            $existingShipment = $subscription->shipments()
                ->where('shipment_schedule_id', $schedule->id)
                ->first();

            if ($existingShipment && $existingShipment->subscription_payment_id) {
                \Log::info('Preserving paid shipment during pause', [
                    'subscription_id' => $subscription->id,
                    'shipment_id' => $existingShipment->id,
                    'shipment_date' => $date->toDateString(),
                    'payment_id' => $existingShipment->subscription_payment_id,
                ]);

                continue; // Skip this one, it's already paid
            }

            $skippedShipment = $this->updateOrCreateForSchedule($subscription, $schedule, [
                'status' => 'skipped',
                'notes' => 'Paused by user: '.$reason,
            ]);

            // KROK 10: addon objednávky přeskočeného boxu odpojit k ruční revizi.
            $this->detachAddonOrders($skippedShipment, 'pause_skip');
        }

        // 5. Create pending shipment for date after pause
        $resumeSchedule = ShipmentSchedule::getOrCreateForMonth($candidate->year, $candidate->month);
        $resumeDate = $resumeSchedule->shipment_date;

        $this->updateOrCreateForSchedule($subscription, $resumeSchedule, [
            'status' => 'pending',
        ]);

        // 6. Update subscription status
        // Use day after last skipped shipment so resume doesn't collide with shipment date (20th -> 21st)
        $pausedUntilDate = $skippedDates->last()?->copy()->addDay();
        $subscription->update([
            'status' => 'paused',
            'paused_iterations' => $iterations,
            'paused_until_date' => $pausedUntilDate?->endOfDay(),
            'pause_reason' => $reason,
        ]);

        // 7. Update coffee reservations for all skipped months (release reserved stock)
        $reservationService = app(StockReservationService::class);
        $updatedSchedules = collect();

        foreach ($skippedDates as $skippedDate) {
            $schedule = ShipmentSchedule::getForMonth($skippedDate->year, $skippedDate->month);
            if ($schedule && ! $updatedSchedules->contains($schedule->id)) {
                try {
                    $reservationService->updateReservationsForSchedule($schedule);
                    $updatedSchedules->push($schedule->id);
                } catch (\Exception $e) {
                    \Log::error('Failed to update reservations after pause', [
                        'subscription_id' => $subscription->id,
                        'schedule_id' => $schedule->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        \Log::info('Subscription paused', [
            'subscription_id' => $subscription->id,
            'iterations' => $iterations,
            'paid_shipments_preserved' => $paidPendingShipments->pluck('shipment_date')->map->toDateString(),
            'skipped_dates' => $skippedDates->map->toDateString(),
            'resume_date' => $resumeDate?->toDateString(),
            'reservations_updated_for_schedules' => $updatedSchedules->toArray(),
        ]);
    }

    /**
     * Resume subscription from pause
     * Handles early resume by cleaning up future skipped/pending records
     * and creating a new pending record for the nearest available shipment
     *
     * @return array ['success' => bool, 'message' => string, 'next_shipment' => ?Carbon]
     */
    public function resumeSubscription(Subscription $subscription): array
    {
        // 1. Calculate what would be the next shipment date
        $nextShipmentDate = $this->calculateNextShipmentDate($subscription);

        if (! $nextShipmentDate) {
            return [
                'success' => false,
                'message' => __('flash.subscription.resume_no_date'),
                'next_shipment' => null,
            ];
        }

        // 2. Check if the next shipment is already paid - if so, skip availability check
        $existingPaidShipment = $subscription->shipments()
            ->whereDate('shipment_date', $nextShipmentDate->toDateString())
            ->where('status', 'pending')
            ->whereNotNull('subscription_payment_id')
            ->first();

        if ($existingPaidShipment) {
            \Log::info('Skipping availability check - shipment already paid', [
                'subscription_id' => $subscription->id,
                'shipment_id' => $existingPaidShipment->id,
                'shipment_date' => $nextShipmentDate->toDateString(),
            ]);
        } else {
            // 3. Check coffee availability for this shipment month (only for unpaid shipments)
            $schedule = ShipmentSchedule::getForMonth($nextShipmentDate->year, $nextShipmentDate->month);

            if ($schedule && $schedule->hasCoffeeSlotsConfigured()) {
                $config = is_string($subscription->configuration)
                    ? json_decode($subscription->configuration, true)
                    : $subscription->configuration;

                if ($config) {
                    $subscriptionType = $config['type'] ?? 'espresso';
                    $isDecaf = $config['isDecaf'] ?? false;

                    // Use checkTypeAvailability to check only relevant coffee slots
                    $reservationService = app(\App\Services\StockReservationService::class);
                    $typeAvailability = $reservationService->checkTypeAvailability($schedule);

                    // Determine if the subscription's type is available
                    $typeAvailable = match ($subscriptionType) {
                        'espresso' => $typeAvailability['espresso'],
                        'filter' => $typeAvailability['filter'],
                        'mix' => $typeAvailability['mix'],
                        default => $typeAvailability['espresso'],
                    };

                    // Also check decaf if needed
                    if ($isDecaf && ! $typeAvailability['decaf']) {
                        $typeAvailable = false;
                    }

                    if (! $typeAvailable) {
                        $typeName = match ($subscriptionType) {
                            'espresso' => __('subscriptions.coffee_types.espresso'),
                            'filter' => __('subscriptions.coffee_types.filter'),
                            'mix' => __('subscriptions.coffee_types.mix'),
                            default => $subscriptionType,
                        };

                        \Log::warning('Cannot resume subscription - coffee type out of stock', [
                            'subscription_id' => $subscription->id,
                            'next_shipment' => $nextShipmentDate->toDateString(),
                            'type' => $subscriptionType,
                            'is_decaf' => $isDecaf,
                            'type_availability' => $typeAvailability,
                        ]);

                        return [
                            'success' => false,
                            'message' => __('flash.subscription.resume_out_of_stock', [
                                'month' => $nextShipmentDate->translatedFormat('F Y'),
                                'coffees' => $typeName.($isDecaf ? ' + decaf' : ''),
                            ]),
                            'next_shipment' => $nextShipmentDate,
                        ];
                    }
                }
            }
        }

        // 4. Delete all future skipped shipments (those that haven't happened yet)
        $deletedSkipped = $subscription->shipments()
            ->where('status', 'skipped')
            ->where('shipment_date', '>=', today()->startOfDay())
            ->delete();

        // 5. Delete all future pending shipments (created when pause was set up)
        // BUT preserve paid pending shipments
        $deletedPending = $subscription->shipments()
            ->where('status', 'pending')
            ->where('shipment_date', '>', now())
            ->whereNull('subscription_payment_id') // Don't delete paid shipments
            ->delete();

        \Log::info('Cleaned up future shipments on resume', [
            'subscription_id' => $subscription->id,
            'deleted_skipped' => $deletedSkipped,
            'deleted_pending' => $deletedPending,
        ]);

        // 6. Clear pause-related fields
        $subscription->update([
            'status' => 'active',
            'paused_iterations' => null,
            'paused_until_date' => null,
            'pause_reason' => null,
        ]);

        // 7. Calculate next shipment date and ensure pending shipment exists
        $frequencyMonths = max(1, (int) ($subscription->frequency_months ?? 1));
        $nextShipmentDate = $this->calculateNextShipmentDate($subscription);

        if (! $nextShipmentDate) {
            $nextShipmentDate = $this->getFirstShipmentDate($subscription);
        }

        // Check what exists for this date
        $existingShipment = $subscription->shipments()
            ->whereDate('shipment_date', $nextShipmentDate->toDateString())
            ->first();

        // Get billing date for this shipment
        $schedule = ShipmentSchedule::getForMonth($nextShipmentDate->year, $nextShipmentDate->month);
        $billingDate = $schedule?->billing_date ?? $nextShipmentDate->copy()->day(15);

        // Skip forward until we find a month with a future billing date and no skipped shipment
        $skippedMonths = 0;
        while (
            ($existingShipment && $existingShipment->status === 'skipped')
            || $billingDate->lte(today())
        ) {
            $nextMonth = $nextShipmentDate->copy()->addMonths($frequencyMonths);
            $schedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);
            $nextShipmentDate = $schedule?->shipment_date ?? $nextMonth->copy()->day(20);
            $billingDate = $schedule?->billing_date ?? $nextShipmentDate->copy()->day(15);

            $existingShipment = $subscription->shipments()
                ->whereDate('shipment_date', $nextShipmentDate->toDateString())
                ->first();

            $skippedMonths++;
            if ($skippedMonths > 12) {
                \Log::error('Resume skip loop exceeded 12 months, breaking', [
                    'subscription_id' => $subscription->id,
                ]);
                break;
            }
        }

        if ($skippedMonths > 0) {
            \Log::info('Skipped months after resume to find future billing date', [
                'subscription_id' => $subscription->id,
                'skipped_months' => $skippedMonths,
                'new_shipment_date' => $nextShipmentDate->toDateString(),
                'new_billing_date' => $billingDate->toDateString(),
            ]);
        }

        // Ensure pending shipment exists for the correct date (idempotentní, klíč = schedule)
        $resumeSchedule = ShipmentSchedule::getOrCreateForMonth($nextShipmentDate->year, $nextShipmentDate->month);
        $existingForNewDate = $subscription->shipments()
            ->where('shipment_schedule_id', $resumeSchedule->id)
            ->first();

        $nextPending = null;
        if (! $existingForNewDate) {
            $nextPending = $this->getOrCreateForSchedule($subscription, $resumeSchedule, [
                'status' => 'pending',
            ]);

            \Log::info('Created pending shipment after resume', [
                'subscription_id' => $subscription->id,
                'shipment_date' => $nextShipmentDate->toDateString(),
            ]);
        } elseif ($existingForNewDate->status === 'pending') {
            $nextPending = $existingForNewDate;
        }

        // 8. Set next_billing_date
        $subscription->update([
            'next_billing_date' => $billingDate,
        ]);

        // 9. Update coffee reservations for this schedule
        if ($schedule) {
            try {
                $reservationService = app(\App\Services\StockReservationService::class);
                $reservationService->updateReservationsForSchedule($schedule);
            } catch (\Exception $e) {
                \Log::error('Failed to update reservations after resume', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        \Log::info('Subscription resumed', [
            'subscription_id' => $subscription->id,
            'next_shipment' => $nextShipmentDate?->toDateString(),
            'next_billing_date' => $billingDate?->toDateString(),
        ]);

        return [
            'success' => true,
            'message' => __('flash.subscription.resumed'),
            'next_shipment' => $nextShipmentDate,
        ];
    }

    /**
     * Náhled zrušení – jediný zdroj pravdy o tom, co zrušení udělá. Použije se pro
     * informativní modal na dashboardu i pro samotné cancelSubscription().
     */
    public function getCancellationPreview(Subscription $subscription): CancellationPreview
    {
        // Zaplacené pending zásilky (payment.status='paid') = budou ještě doručeny.
        // reorder() zruší default desc řazení z relace shipments(), ať ->last() = nejzazší box.
        $paidShipments = $subscription->shipments()
            ->where('status', 'pending')
            ->whereHas('payment', fn ($q) => $q->where('status', 'paid'))
            ->reorder('shipment_date', 'asc')
            ->get();

        // Nezaplacené pending = zruší se.
        $unpaid = $subscription->shipments()
            ->where('status', 'pending')
            ->whereDoesntHave('payment', fn ($q) => $q->where('status', 'paid'))
            ->get();

        // Addon objednávky navázané na rušené (nezaplacené) boxy = odpojí se.
        $addonToDetach = 0;
        foreach ($unpaid as $ship) {
            $addonToDetach += $ship->addonOrders()
                ->whereNotIn('status', ['shipped', 'delivered', 'cancelled'])
                ->count();
        }

        $lastPaidDate = $paidShipments->last()?->shipment_date;

        return new CancellationPreview(
            paidShipments: $paidShipments,
            lastPaidDate: $lastPaidDate,
            paidCount: $paidShipments->count(),
            unpaidToCancelCount: $unpaid->count(),
            addonToDetachCount: $addonToDetach,
            endsImmediately: $paidShipments->isEmpty(),
        );
    }

    /**
     * Cancel subscription – ke konci zaplaceného období. Konzumuje getCancellationPreview(),
     * takže se nemůže rozejít s tím, co bylo uživateli ukázáno v modalu.
     */
    public function cancelSubscription(Subscription $subscription): void
    {
        $preview = $this->getCancellationPreview($subscription);

        // Nezaplacené pending zásilky → cancelled (a odpojit jejich addon objednávky).
        $unpaid = $subscription->shipments()
            ->where('status', 'pending')
            ->whereDoesntHave('payment', fn ($q) => $q->where('status', 'paid'))
            ->get();

        // KROK 10: addon objednávky rušených boxů odpojit k ruční revizi.
        foreach ($unpaid as $ship) {
            $this->detachAddonOrders($ship, 'subscription_cancelled');
        }

        $subscription->shipments()
            ->whereIn('id', $unpaid->pluck('id'))
            ->update(['status' => 'cancelled', 'notes' => 'Subscription cancelled']);

        // ends_at = poslední zaplacená zásilka (doběhne do konce období), jinak teď.
        $endsAt = $preview->lastPaidDate ?? now();

        $subscription->update([
            'status' => 'cancelled',
            'ends_at' => $endsAt,
        ]);

        \Log::info('Subscription cancelled', [
            'subscription_id' => $subscription->id,
            'ends_at' => $endsAt->toDateString(),
            'last_paid_shipment' => $preview->lastPaidDate?->toDateString(),
            'ends_immediately' => $preview->endsImmediately,
        ]);
    }

    /**
     * Link a payment to its corresponding shipment (or create one)
     * Called after successful payment to ensure shipment record exists and is linked
     */
    public function linkPaymentToShipment(SubscriptionPayment $payment, Subscription $subscription): ?SubscriptionShipment
    {
        // Get expected shipment date for this payment
        $shipmentDate = $payment->expected_shipment_date;

        if (! $shipmentDate) {
            \Log::warning('Cannot link payment to shipment - no expected shipment date', [
                'payment_id' => $payment->id,
                'subscription_id' => $subscription->id,
            ]);

            return null;
        }

        // Jediný idempotentní creator, klíčovaný na měsíc (schedule). Unique index
        // zaručí, že se trefíme do existujícího řádku místo vytvoření duplicitního.
        $schedule = ShipmentSchedule::getOrCreateForMonth($shipmentDate->year, $shipmentDate->month);
        $shipment = $this->getOrCreateForSchedule($subscription, $schedule, [
            'subscription_payment_id' => $payment->id,
            'notes' => 'Created after payment',
        ]);

        // Navázat platbu, pokud řádek existoval bez ní. Zaplacený box "oživí"
        // případný přeskočený/zrušený měsíc.
        if (! $shipment->subscription_payment_id) {
            $updates = ['subscription_payment_id' => $payment->id];
            if (in_array($shipment->status, ['skipped', 'cancelled'], true)) {
                $updates['status'] = 'pending';
            }
            $shipment->update($updates);

            \Log::info('Linked existing shipment to payment', [
                'shipment_id' => $shipment->id,
                'payment_id' => $payment->id,
                'shipment_date' => optional($shipment->shipment_date)->toDateString(),
                'reactivated' => isset($updates['status']),
            ]);
        }

        return $shipment;
    }

    /**
     * KROK 6: Explicitní navázání platby na KONKRÉTNÍ řádek zásilky (bez odvozování
     * z period_end / expected_shipment_date). Volá se z billing cronu, který zásilku
     * vybral jako due. Zaplacený box "oživí" případný přeskočený/zrušený měsíc.
     */
    public function linkPaymentToShipmentRow(SubscriptionPayment $payment, SubscriptionShipment $shipment): SubscriptionShipment
    {
        if ($shipment->subscription_payment_id === $payment->id) {
            return $shipment;
        }

        $updates = ['subscription_payment_id' => $payment->id];
        if (in_array($shipment->status, ['skipped', 'cancelled'], true)) {
            $updates['status'] = 'pending';
        }

        $shipment->update($updates);

        \Log::info('Linked payment to explicit shipment row', [
            'shipment_id' => $shipment->id,
            'payment_id' => $payment->id,
            'reactivated' => isset($updates['status']),
        ]);

        return $shipment;
    }

    /**
     * Mark shipment as shipped (called from admin when sending to Packeta or manually)
     */
    public function markAsShipped(
        SubscriptionShipment $shipment,
        ?string $packetId = null,
        ?string $trackingUrl = null
    ): void {
        $updateData = [
            'status' => 'sent',
            'sent_at' => now(),
            // KROK 8: zmrazit skutečně použitého dopravce/výdejní místo z AKTUÁLNÍ
            // konfigurace předplatného v čase odeslání (ne ze zastaralé cache na řádku).
            'carrier_id' => $shipment->subscription->carrier_id,
            'carrier_pickup_point' => $shipment->subscription->carrier_pickup_point,
        ];

        if ($packetId !== null) {
            $updateData['packeta_packet_id'] = $packetId;
        }
        if ($trackingUrl !== null) {
            $updateData['packeta_tracking_url'] = $trackingUrl;
        }

        $shipment->update($updateData);

        // KROK 9: last_shipment_date se už neukládá – odvozuje se z ledgeru
        // (accessor Subscription::last_shipment_date = MAX sent/delivered).

        // For one-time boxes, mark as completed
        if ($shipment->subscription->frequency_months == 0) {
            $shipment->subscription->update(['status' => 'completed']);
        } else {
            // For recurring subscriptions, ensure next pending shipment exists
            $subscription = $shipment->subscription->fresh();
            $this->ensurePendingShipmentExists($subscription);
        }

        \Log::info('Shipment marked as sent', [
            'shipment_id' => $shipment->id,
            'subscription_id' => $shipment->subscription_id,
            'packeta_packet_id' => $packetId,
        ]);
    }

    /**
     * KROK 10: Odpojí addon objednávky navázané na zásilku a podrží je k RUČNÍMU řešení
     * (dle rozhodnutí – žádné auto-přepojení). Volá se, když se box přeskočí/zruší, aby
     * addon nezůstal tiše navázaný na měsíc, který se neodešle.
     */
    public function detachAddonOrders(SubscriptionShipment $shipment, string $reason): int
    {
        $orders = $shipment->addonOrders()
            ->whereNotIn('status', ['shipped', 'delivered', 'cancelled'])
            ->get();

        foreach ($orders as $order) {
            $order->update([
                'subscription_shipment_id' => null,
                'admin_notes' => trim(($order->admin_notes ? $order->admin_notes."\n" : '')
                    .'['.now()->toDateString().'] Addon odpojen od zásilky #'.$shipment->id.' ('.$reason.') – k ruční revizi.'),
            ]);

            \Log::warning('Addon order detached from shipment for manual handling', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'shipment_id' => $shipment->id,
                'reason' => $reason,
            ]);
        }

        return $orders->count();
    }

    /**
     * KROK 8: Jediný zápis stavu "doručeno". Nastaví status/delivered_at (a doplní sent_at,
     * pokud chybí). Observer na základě změny delivered_at odešle e-mail o doručení boxu.
     */
    public function markAsDelivered(SubscriptionShipment $shipment, ?Carbon $deliveredAt = null): void
    {
        $when = $deliveredAt ?? now();

        $shipment->update([
            'status' => 'delivered',
            'delivered_at' => $when,
            'sent_at' => $shipment->sent_at ?? $when,
        ]);

        \Log::info('Shipment marked as delivered', [
            'shipment_id' => $shipment->id,
            'subscription_id' => $shipment->subscription_id,
        ]);
    }

    /**
     * Get subscriptions that should ship on a specific date
     * Used by admin shipments page and cron jobs
     */
    public function getSubscriptionsForShipmentDate(Carbon $date): Collection
    {
        return SubscriptionShipment::with(['subscription.user', 'subscription.plan', 'payment'])
            ->whereDate('shipment_date', $date->toDateString())
            ->where('status', 'pending')
            // Never ship an unpaid subscription (no successful payment yet)
            ->whereHas('subscription', fn ($q) => $q->where('status', '!=', 'unpaid'))
            ->get()
            ->map(fn ($shipment) => $shipment->subscription)
            ->filter() // Remove nulls
            ->unique('id');
    }

    /**
     * KROK 5: Jediný idempotentní creator zásilek, klíčovaný na (subscription, schedule).
     *
     * Díky unique(subscription_id, shipment_schedule_id) nemůže vzniknout duplicita:
     * pro daný měsíc téhož předplatného existuje vždy nejvýš jeden řádek. shipment_date
     * je denormalizovaná kopie z rozvrhu (nikdy match-klíč). $createAttributes se použijí
     * jen při vytvoření nového řádku; existující se vrací beze změny.
     */
    public function getOrCreateForSchedule(Subscription $subscription, ShipmentSchedule $schedule, array $createAttributes = []): SubscriptionShipment
    {
        try {
            return $subscription->shipments()->firstOrCreate(
                ['shipment_schedule_id' => $schedule->id],
                array_merge([
                    'shipment_date' => $schedule->shipment_date->copy()->startOfDay(),
                    'status' => 'pending',
                    ...$this->getPackageDimensions($subscription),
                ], $createAttributes)
            );
        } catch (QueryException $e) {
            // Souběh: řádek vznikl mezi SELECT a INSERT (chytí unique index) – dohledej ho.
            $existing = $subscription->shipments()
                ->where('shipment_schedule_id', $schedule->id)
                ->first();

            if ($existing) {
                return $existing;
            }

            throw $e;
        }
    }

    /**
     * KROK 5: get-or-create klíčovaný na schedule + aktualizace existujícího řádku danými
     * hodnotami (např. status 'skipped'/'pending' při pauze/resume).
     */
    public function updateOrCreateForSchedule(Subscription $subscription, ShipmentSchedule $schedule, array $values = []): SubscriptionShipment
    {
        $shipment = $this->getOrCreateForSchedule($subscription, $schedule, $values);

        // Při vytvoření už jsou $values aplikované přes firstOrCreate; na existující je doplň.
        if (! $shipment->wasRecentlyCreated && ! empty($values)) {
            $shipment->fill($values);
            if ($shipment->isDirty()) {
                $shipment->save();
            }
        }

        return $shipment;
    }

    /**
     * KROK 5: convenience – vyřeší schedule z měsíce data a deleguje na getOrCreateForSchedule.
     * Datum řádku se vždy srovná na schedule.shipment_date.
     */
    public function getOrCreateForDate(Subscription $subscription, Carbon $date, array $createAttributes = []): SubscriptionShipment
    {
        $schedule = ShipmentSchedule::getOrCreateForMonth($date->year, $date->month);

        return $this->getOrCreateForSchedule($subscription, $schedule, $createAttributes);
    }

    /**
     * Get or create shipment record for a subscription and date
     * Used when preparing shipments in admin
     */
    public function getOrCreateShipment(Subscription $subscription, Carbon $shipmentDate): SubscriptionShipment
    {
        $schedule = ShipmentSchedule::getOrCreateForMonth($shipmentDate->year, $shipmentDate->month);
        $shipment = $this->getOrCreateForSchedule($subscription, $schedule);

        // Admin příprava očekává přepočítání vazby platby dle aktuální logiky.
        $payment = $this->findPaymentForShipment($subscription, $schedule->shipment_date);
        if ($shipment->subscription_payment_id !== $payment?->id) {
            \Log::info('Updated shipment payment link', [
                'shipment_id' => $shipment->id,
                'old_payment_id' => $shipment->subscription_payment_id,
                'new_payment_id' => $payment?->id,
            ]);
            $shipment->update(['subscription_payment_id' => $payment?->id]);
        }

        return $shipment;
    }

    /**
     * Calculate upcoming shipment dates for a subscription
     */
    protected function calculateUpcomingShipmentDates(Subscription $subscription, int $count): Collection
    {
        $frequencyMonths = max(1, (int) ($subscription->frequency_months ?? 1));
        $dates = collect();

        // Start from next unpaid shipment
        $candidate = $this->calculateNextShipmentDate($subscription);

        if (! $candidate) {
            $candidate = $this->getFirstShipmentDate($subscription);
        }

        // Ensure we start from future date
        $today = Carbon::now()->startOfDay();
        while ($candidate->lt($today)) {
            $candidate = $candidate->copy()->addMonths($frequencyMonths);
        }

        // Collect N dates
        $guard = 0;
        while ($dates->count() < $count && $guard < 24) {
            $schedule = ShipmentSchedule::getForMonth($candidate->year, $candidate->month);
            $shipmentDate = $schedule?->shipment_date ?? $candidate->copy()->day(20);

            $dates->push($shipmentDate->copy()->startOfDay());
            $candidate = $candidate->copy()->addMonths($frequencyMonths);
            $guard++;
        }

        return $dates;
    }

    /**
     * Find payment that covers a specific shipment date
     */
    protected function findPaymentForShipment(Subscription $subscription, Carbon $shipmentDate): ?SubscriptionPayment
    {
        // Check for initial payment (first shipment)
        if (! $subscription->last_shipment_date && ! $subscription->shipments()->where('status', 'sent')->exists()) {
            // First shipment - check if initial checkout payment exists
            $initialPayment = $subscription->payments()
                ->where('status', 'paid')
                ->orderBy('paid_at', 'asc')
                ->first();

            if ($initialPayment) {
                return $initialPayment;
            }
        }

        // Find payment whose period covers this shipment date
        // period_end is EXCLUSIVE (it's the next billing date), so use > not >=
        return $subscription->payments()
            ->where('status', 'paid')
            ->whereDate('period_start', '<=', $shipmentDate->toDateString())
            ->whereDate('period_end', '>', $shipmentDate->toDateString())
            ->first();
    }

    /**
     * Check if a subscription should ship on a specific date
     * Used for coffee reservation calculations
     *
     * Includes:
     * - Subscriptions with existing pending shipment for this date
     * - Active/complimentary subscriptions matching cadence for this date
     * - Paused subscriptions whose pause ends ON or BEFORE the billing_date
     * - Cancelled subscriptions with paid coverage for this date
     */
    public function shouldShipOn(Subscription $subscription, Carbon $date): bool
    {
        // 1. Check for existing shipment record (pending or skipped)
        $shipment = $subscription->shipments()
            ->whereDate('shipment_date', $date->toDateString())
            ->whereIn('status', ['pending', 'skipped'])
            ->first();

        if ($shipment) {
            // If skipped, don't ship. If pending, ship.
            return $shipment->status === 'pending';
        }

        // 2. For paused subscriptions: check if pause ends before/on billing_date
        // If so, the subscription WILL be active when billing occurs
        if ($subscription->status === 'paused' && $subscription->paused_until_date) {
            $billingDate = ShipmentSchedule::getBillingDateForMonth($date->year, $date->month);

            // paused_until_date <= billing_date → subscription will be active for this shipment
            if ($subscription->paused_until_date->lte($billingDate)) {
                return $this->wouldShipOnDateIfActive($subscription, $date);
            }

            return false;
        }

        // 3. For active/complimentary subscriptions, check cadence
        if (in_array($subscription->status, ['active', 'complimentary'])) {
            return $this->wouldShipOnDateIfActive($subscription, $date);
        }

        // 4. For cancelled subscriptions with paid coverage
        if ($subscription->status === 'cancelled') {
            if ($this->hasPaidCoverageForDate($subscription, $date)) {
                return $this->wouldShipOnDateIfActive($subscription, $date);
            }
        }

        return false;
    }

    /**
     * Check if subscription WOULD ship on date if it were active
     * Used to determine if paused subscription should be counted in reservations
     *
     * Takes into account pending shipments before the target date to correctly
     * calculate cadence for future shipments.
     */
    protected function wouldShipOnDateIfActive(Subscription $subscription, Carbon $date): bool
    {
        // One-time boxes
        if ($subscription->frequency_months == 0) {
            if ($subscription->last_shipment_date) {
                return false;
            }
            // Check if there's already a shipment record for any date
            $existingShipment = $subscription->shipments()
                ->whereIn('status', ['pending', 'sent', 'delivered'])
                ->first();
            if ($existingShipment) {
                // Only match if the existing shipment is for this exact date
                return $existingShipment->shipment_date->isSameDay($date);
            }
            // No shipment exists yet - calculate where it should go
            $firstDate = $this->getFirstShipmentDate($subscription);

            return $firstDate->isSameDay($date);
        }

        // Recurring subscription - check frequency alignment
        $frequencyMonths = max(1, (int) ($subscription->frequency_months ?? 1));

        // Find pending shipment BEFORE target date (more recent than last_shipment_date)
        // This ensures we calculate cadence from the most recent planned shipment
        $pendingBeforeTarget = $subscription->shipments()
            ->where('status', 'pending')
            ->whereDate('shipment_date', '<', $date->toDateString())
            ->orderBy('shipment_date', 'desc')
            ->first();

        if ($pendingBeforeTarget) {
            $lastShipment = $pendingBeforeTarget->shipment_date;
        } else {
            $lastShipment = $subscription->last_shipment_date
                ?? $subscription->starts_at
                ?? $subscription->created_at;
        }

        // Calculate expected next shipment dates from the base
        $candidate = $lastShipment->copy();

        for ($i = 0; $i < 24; $i++) { // Max 2 years
            $candidate = $candidate->copy()->addMonths($frequencyMonths);
            $schedule = ShipmentSchedule::getForMonth($candidate->year, $candidate->month);
            $shipDate = $schedule?->shipment_date ?? $candidate->copy()->day(20);

            // Found matching date
            if ($shipDate->isSameDay($date)) {
                return true;
            }

            // Past the target date
            if ($shipDate->gt($date)) {
                return false;
            }
        }

        return false;
    }

    /**
     * Check if subscription has paid coverage for a specific date
     * Legacy method for backward compatibility
     */
    public function hasPaidCoverageForDate(Subscription $subscription, Carbon $date): bool
    {
        // 1) AUTORITATIVNĚ: má zásilka pro tento měsíc (schedule) navázanou zaplacenou platbu?
        //    Ledger je jediný zdroj pravdy o zaplacení konkrétního boxu.
        $schedule = ShipmentSchedule::getForMonth($date->year, $date->month);
        if ($schedule) {
            $shipment = $subscription->shipments()
                ->where('shipment_schedule_id', $schedule->id)
                ->first();

            if ($shipment && $shipment->subscription_payment_id) {
                return optional($shipment->payment)->status === 'paid';
            }
        }

        // 2) První zásilka je krytá aktivační platbou i bez navázaného řádku.
        if ($this->isInitialShipmentCovered($subscription, $date)) {
            return true;
        }

        // 3) Přechodný fallback pro řádky ještě bez FK: zaplacené období pokrývající datum.
        //    Dřívější zkratka `date < next_billing_date` byla odstraněna – rozcházela se
        //    s reálným zaplacením konkrétního boxu a byla zdrojem chyb.
        // period_end is EXCLUSIVE (it's the next billing date), so use > not >=
        return $subscription->payments()
            ->where('status', 'paid')
            ->whereDate('period_start', '<=', $date->toDateString())
            ->whereDate('period_end', '>', $date->toDateString())
            ->exists();
    }

    /**
     * Check if date is the initial shipment covered by first payment
     */
    public function isInitialShipmentCovered(Subscription $subscription, Carbon $date): bool
    {
        // Has shipments sent - not initial
        if ($subscription->shipments()->whereIn('status', ['sent', 'delivered'])->exists()) {
            return false;
        }

        $firstScheduled = $this->getFirstShipmentDate($subscription);

        return $firstScheduled->isSameDay($date);
    }

    /**
     * Get package dimensions based on subscription configuration
     */
    protected function getPackageDimensions(Subscription $subscription): array
    {
        $config = is_string($subscription->configuration)
            ? json_decode($subscription->configuration, true)
            : $subscription->configuration;

        $amount = $config['amount'] ?? 2;

        return [
            'package_weight' => \App\Models\SubscriptionConfig::get("package_{$amount}_weight", $amount * 0.25),
            'package_length' => (int) \App\Models\SubscriptionConfig::get("package_{$amount}_length", 30),
            'package_width' => (int) \App\Models\SubscriptionConfig::get("package_{$amount}_width", 20),
            'package_height' => (int) \App\Models\SubscriptionConfig::get("package_{$amount}_height", 10),
            'carrier_id' => $subscription->carrier_id,
            'carrier_pickup_point' => $subscription->carrier_pickup_point,
        ];
    }
}
