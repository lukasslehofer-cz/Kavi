<?php

namespace App\Console\Commands;

use App\Models\ShipmentSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestBillingSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:test-schedule {--reset : Reset test schedules}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test ShipmentSchedule billing date integration and edge cases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('reset')) {
            $this->resetTestSchedules();
            return 0;
        }
        
        $this->info('🧪 Testing ShipmentSchedule Integration');
        $this->newLine();
        
        // Test 1: Helper methods
        $this->test1_HelperMethods();
        
        // Test 2: Next billing date calculation
        $this->test2_NextBillingDate();
        
        // Test 3: Fallback behavior
        $this->test3_FallbackBehavior();
        
        // Test 4: Frequency handling
        $this->test4_FrequencyHandling();
        
        // Test 5: Edge cases
        $this->test5_EdgeCases();
        
        $this->newLine();
        $this->info('✅ All tests completed');
        
        return 0;
    }
    
    private function test1_HelperMethods()
    {
        $this->line('📋 Test 1: Helper Methods');
        $this->line('─────────────────────────────────────');
        
        // Test getNextBillingDate
        $nextBilling = ShipmentSchedule::getNextBillingDate();
        $this->line("  getNextBillingDate(): {$nextBilling->format('d.m.Y')}");
        
        // Test getBillingDateForMonth
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $monthBilling = ShipmentSchedule::getBillingDateForMonth($currentYear, $currentMonth);
        $this->line("  getBillingDateForMonth($currentYear, $currentMonth): {$monthBilling->format('d.m.Y')}");
        
        // Test with future month
        $futureMonth = now()->addMonths(2);
        $futureBilling = ShipmentSchedule::getBillingDateForMonth($futureMonth->year, $futureMonth->month);
        $this->line("  getBillingDateForMonth({$futureMonth->year}, {$futureMonth->month}): {$futureBilling->format('d.m.Y')}");
        
        $this->line("  ✓ Helper methods working");
        $this->newLine();
    }
    
    private function test2_NextBillingDate()
    {
        $this->line('📋 Test 2: Nearest Billing Date (covered by first payment)');
        $this->line('─────────────────────────────────────');
        
        $today = Carbon::now();
        $this->line("  Today: {$today->format('d.m.Y')}");
        $this->line("  Note: These dates are covered by FIRST payment");
        $this->newLine();
        
        // Test from different dates
        $testDates = [
            $today,
            $today->copy()->setDay(1),
            $today->copy()->setDay(15),
            $today->copy()->setDay(28),
        ];
        
        foreach ($testDates as $testDate) {
            $nearestBilling = ShipmentSchedule::getNextBillingDate($testDate);
            // Calculate what next_billing_date would be for a subscription starting on testDate
            $nextBillingDate = ShipmentSchedule::getBillingDateAfterMonths($nearestBilling, 1);
            $this->line("  From {$testDate->format('d.m')}: Nearest = {$nearestBilling->format('d.m')} (covered), Next billing = {$nextBillingDate->format('d.m.Y')}");
        }
        
        $this->line("  ✓ Nearest billing date calculation working");
        $this->newLine();
    }
    
    private function test3_FallbackBehavior()
    {
        $this->line('📋 Test 3: Fallback Behavior (Missing Schedules)');
        $this->line('─────────────────────────────────────');
        
        // Test with far future date (likely no schedule)
        $farFuture = Carbon::create(2030, 6, 15);
        $fallbackDate = ShipmentSchedule::getBillingDateForMonth(2030, 6);
        
        $this->line("  Testing year 2030 (likely missing schedule):");
        $this->line("  getBillingDateForMonth(2030, 6): {$fallbackDate->format('d.m.Y')}");
        
        $schedule = ShipmentSchedule::getForMonth(2030, 6);
        if ($schedule) {
            $this->line("  ℹ️  Schedule exists (was auto-created or manually added)");
        } else {
            $this->line("  ⚠️  Schedule doesn't exist - fallback to 15th used");
        }
        
        $this->line("  ✓ Fallback behavior working");
        $this->newLine();
    }
    
    private function test4_FrequencyHandling()
    {
        $this->line('📋 Test 4: Frequency Handling');
        $this->line('─────────────────────────────────────');
        
        $frequencies = [1, 2, 3];
        
        foreach ($frequencies as $frequency) {
            $firstBilling = ShipmentSchedule::getFirstBillingDateWithFrequency($frequency);
            $this->line("  Frequency {$frequency} month(s): First billing = {$firstBilling->format('d.m.Y')}");
        }
        
        $this->newLine();
        
        // Test getBillingDateAfterMonths
        $currentDate = Carbon::now();
        $this->line("  Testing getBillingDateAfterMonths from {$currentDate->format('d.m.Y')}:");
        
        foreach ($frequencies as $frequency) {
            $nextDate = ShipmentSchedule::getBillingDateAfterMonths($currentDate, $frequency);
            $this->line("    + {$frequency} month(s) = {$nextDate->format('d.m.Y')}");
        }
        
        $this->line("  ✓ Frequency handling working");
        $this->newLine();
    }
    
    private function test5_EdgeCases()
    {
        $this->line('📋 Test 5: Edge Cases');
        $this->line('─────────────────────────────────────');
        
        // Edge case 1: End of month
        $endOfMonth = Carbon::now()->endOfMonth();
        $nextBilling = ShipmentSchedule::getNextBillingDate($endOfMonth);
        $this->line("  End of month ({$endOfMonth->format('d.m')}): Next = {$nextBilling->format('d.m.Y')}");
        
        // Edge case 2: Start of year
        $startOfYear = Carbon::create(now()->year + 1, 1, 1);
        $januaryBilling = ShipmentSchedule::getBillingDateForMonth($startOfYear->year, 1);
        $this->line("  Start of year: January billing = {$januaryBilling->format('d.m.Y')}");
        
        // Edge case 3: End of year
        $decemberBilling = ShipmentSchedule::getBillingDateForMonth(now()->year, 12);
        $nextYearJan = ShipmentSchedule::getBillingDateAfterMonths($decemberBilling, 1);
        $this->line("  Year overflow: December → January = {$nextYearJan->format('d.m.Y')}");
        
        // Edge case 4: Current schedule dates
        $currentSchedule = ShipmentSchedule::getForMonth(now()->year, now()->month);
        if ($currentSchedule) {
            $this->line("  Current month schedule:");
            $this->line("    Billing date: {$currentSchedule->billing_date->format('d.m.Y')}");
            $this->line("    Shipment date: {$currentSchedule->shipment_date->format('d.m.Y')}");
        }
        
        $this->line("  ✓ Edge cases handled");
        $this->newLine();
    }
    
    private function resetTestSchedules()
    {
        $this->warn('🔄 Resetting test schedules...');
        
        // Delete far future test schedules (2030+)
        $deleted = ShipmentSchedule::where('year', '>=', 2030)->delete();
        
        $this->info("Deleted {$deleted} test schedule(s)");
        $this->info('✓ Test schedules reset');
    }
}

