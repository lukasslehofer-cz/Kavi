<?php

namespace App\Console\Commands;

use App\Models\ShipmentSchedule;
use App\Services\StockReservationService;
use Illuminate\Console\Command;

class UpdateStockReservationsManual extends Command
{
    protected $signature = 'stock:update-reservations-manual {year} {month}';
    protected $description = 'Manually update stock reservations for a specific month';

    public function handle(StockReservationService $reservationService): int
    {
        $year = $this->argument('year');
        $month = $this->argument('month');
        
        $this->info("Updating stock reservations for {$month}/{$year}...");
        
        $schedule = ShipmentSchedule::getForMonth($year, $month);
        
        if (!$schedule) {
            $this->error("No shipment schedule found for {$month}/{$year}");
            return Command::FAILURE;
        }
        
        $reservationService->updateReservationsForSchedule($schedule);
        
        $this->info("✓ Stock reservations updated successfully");
        
        return Command::SUCCESS;
    }
}

