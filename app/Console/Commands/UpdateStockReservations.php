<?php

namespace App\Console\Commands;

use App\Services\StockReservationService;
use Illuminate\Console\Command;

class UpdateStockReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:update-reservations
                          {--force : Force update even if not 16th of month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock reservations for upcoming subscription shipments';

    /**
     * Execute the console command.
     */
    public function handle(StockReservationService $reservationService): int
    {
        $this->info('Starting stock reservation update...');

        // Check if today is 16th (or force flag is set)
        $today = now();
        if (!$this->option('force') && $today->day != 16) {
            $this->warn('Today is not the 16th of the month. Use --force to run anyway.');
            $this->info('Next scheduled run: ' . $today->copy()->day(16)->addMonthNoOverflow()->format('Y-m-d'));
            return Command::SUCCESS;
        }

        try {
            // Update all upcoming reservations
            $reservationService->updateAllUpcomingReservations();
            
            $this->info('✓ Stock reservations updated successfully!');
            $this->info('Updated at: ' . now()->format('Y-m-d H:i:s'));
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to update stock reservations: ' . $e->getMessage());
            \Log::error('Stock reservation update failed via command', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return Command::FAILURE;
        }
    }
}

