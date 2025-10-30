<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShipmentSchedule;
use App\Services\StockReservationService;
use Illuminate\Http\Request;

class SubscriptionAvailabilityController extends Controller
{
    public function __construct(
        private StockReservationService $reservationService
    ) {}

    /**
     * Check subscription type availability for next shipment
     */
    public function checkAvailability(Request $request)
    {
        // Get next shipment schedule
        $nextShipment = ShipmentSchedule::getNextShipment();

        if (!$nextShipment) {
            return response()->json([
                'available' => false,
                'espresso' => false,
                'filter' => false,
                'decaf' => false,
                'mix' => false,
                'message' => 'Žádná plánovaná rozesílka nenalezena',
            ]);
        }

        // Check if coffee slots are configured
        if (!$nextShipment->hasCoffeeSlotsConfigured()) {
            return response()->json([
                'available' => false,
                'espresso' => false,
                'filter' => false,
                'decaf' => false,
                'mix' => false,
                'message' => 'Kávy měsíce zatím nejsou nakonfigurovány',
            ]);
        }

        // Check availability for each type
        $availability = $this->reservationService->checkTypeAvailability($nextShipment);

        return response()->json([
            'available' => true,
            'espresso' => $availability['espresso'],
            'filter' => $availability['filter'],
            'decaf' => $availability['decaf'],
            'mix' => $availability['mix'],
            'shipment_date' => $nextShipment->shipment_date->format('d.m.Y'),
        ]);
    }
}

