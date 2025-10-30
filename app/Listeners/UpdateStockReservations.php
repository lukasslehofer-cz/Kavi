<?php

namespace App\Listeners;

use App\Models\Subscription;
use App\Models\ShipmentSchedule;
use App\Services\StockReservationService;
use Illuminate\Support\Facades\Log;

class UpdateStockReservations
{
    public function __construct(
        private StockReservationService $reservationService
    ) {}

    /**
     * Handle subscription created/updated/deleted events
     */
    public function handle($event): void
    {
        try {
            // Get the subscription from the event
            $subscription = $this->getSubscriptionFromEvent($event);
            
            if (!$subscription) {
                return;
            }

            // Get the next shipment schedule
            $nextShipment = ShipmentSchedule::getNextShipment();
            
            if (!$nextShipment) {
                Log::warning('No next shipment schedule found when updating stock reservations');
                return;
            }

            // Update reservations for the next shipment
            $this->reservationService->updateReservationsForSchedule($nextShipment);

            // Also update for the month after if subscription is active
            if ($subscription->status === 'active') {
                $twoMonthsAhead = now()->addMonths(2);
                $futureSchedule = ShipmentSchedule::getForMonth($twoMonthsAhead->year, $twoMonthsAhead->month);
                
                if ($futureSchedule) {
                    $this->reservationService->updateReservationsForSchedule($futureSchedule);
                }
            }

            Log::info('Stock reservations updated after subscription change', [
                'subscription_id' => $subscription->id,
                'event' => class_basename($event),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update stock reservations', [
                'event' => class_basename($event),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Extract subscription from various event types
     */
    private function getSubscriptionFromEvent($event): ?Subscription
    {
        // For Eloquent events (created, updated, deleted, etc.)
        if (isset($event->model) && $event->model instanceof Subscription) {
            return $event->model;
        }

        // For custom events with subscription property
        if (isset($event->subscription) && $event->subscription instanceof Subscription) {
            return $event->subscription;
        }

        return null;
    }
}

