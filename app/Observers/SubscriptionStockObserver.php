<?php

namespace App\Observers;

use App\Models\Subscription;
use App\Models\ShipmentSchedule;
use App\Services\StockReservationService;
use Illuminate\Support\Facades\Log;

class SubscriptionStockObserver
{
    /**
     * Handle the Subscription "created" event.
     */
    public function created(Subscription $subscription): void
    {
        $this->updateReservations($subscription, 'created');
    }

    /**
     * Handle the Subscription "updated" event.
     */
    public function updated(Subscription $subscription): void
    {
        // Only update if status or configuration changed
        if ($subscription->wasChanged(['status', 'configuration', 'frequency_months'])) {
            $this->updateReservations($subscription, 'updated');
        }
    }

    /**
     * Handle the Subscription "deleted" event.
     */
    public function deleted(Subscription $subscription): void
    {
        $this->updateReservations($subscription, 'deleted');
    }

    /**
     * Update stock reservations for relevant shipment schedules
     */
    private function updateReservations(Subscription $subscription, string $action): void
    {
        try {
            $reservationService = app(StockReservationService::class);

            // Get the next shipment schedule
            $nextShipment = ShipmentSchedule::getNextShipment();
            
            if ($nextShipment) {
                $reservationService->updateReservationsForSchedule($nextShipment);
            }

            // Also update for the month after
            $twoMonthsAhead = now()->addMonths(2);
            $futureSchedule = ShipmentSchedule::getForMonth($twoMonthsAhead->year, $twoMonthsAhead->month);
            
            if ($futureSchedule) {
                $reservationService->updateReservationsForSchedule($futureSchedule);
            }

            Log::info('Stock reservations updated after subscription change', [
                'subscription_id' => $subscription->id,
                'action' => $action,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update stock reservations in observer', [
                'subscription_id' => $subscription->id,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

