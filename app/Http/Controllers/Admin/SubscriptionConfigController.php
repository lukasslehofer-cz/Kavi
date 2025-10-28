<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShipmentSchedule;
use App\Models\SubscriptionConfig;
use Illuminate\Http\Request;

class SubscriptionConfigController extends Controller
{
    /**
     * Display subscription configurator settings
     */
    public function index()
    {
        // Use standard Eloquent all() to get all config records
        $configs = SubscriptionConfig::orderBy('key')->get();
        
        // Get shipment schedules
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;
        $previousYears = ShipmentSchedule::selectRaw('DISTINCT year')
            ->where('year', '<', $currentYear)
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        $currentYearSchedules = ShipmentSchedule::getForYear($currentYear);
        $nextYearSchedules = ShipmentSchedule::getForYear($nextYear);
        
        // Get all products marked as "coffee of the month"
        $coffeeProducts = Product::where('is_coffee_of_month', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.subscription-config.index', compact(
            'configs',
            'currentYear',
            'nextYear',
            'previousYears',
            'currentYearSchedules',
            'nextYearSchedules',
            'coffeeProducts'
        ));
    }

    /**
     * Update subscription configurator settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.key' => 'required|string',
            'configs.*.value' => 'required',
        ]);

        foreach ($validated['configs'] as $configData) {
            SubscriptionConfig::set($configData['key'], $configData['value']);
        }

        return redirect()->route('admin.subscription-config.index')
            ->with('success', 'Nastavení bylo úspěšně aktualizováno.');
    }

    /**
     * Update shipment schedule
     */
    public function updateSchedule(Request $request)
    {
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.id' => 'required|exists:shipment_schedules,id',
            'schedules.*.billing_date' => 'required|date',
            'schedules.*.shipment_date' => 'required|date',
            'schedules.*.coffee_product_id' => 'nullable|exists:products,id',
            'schedules.*.roastery_name' => 'nullable|string|max:255',
            'schedules.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['schedules'] as $scheduleData) {
            $schedule = ShipmentSchedule::find($scheduleData['id']);
            
            if ($schedule && !$schedule->isPast()) {
                $schedule->update([
                    'billing_date' => $scheduleData['billing_date'],
                    'shipment_date' => $scheduleData['shipment_date'],
                    'coffee_product_id' => $scheduleData['coffee_product_id'],
                    'roastery_name' => $scheduleData['roastery_name'],
                    'notes' => $scheduleData['notes'],
                ]);
            }
        }

        return redirect()->route('admin.subscription-config.index')
            ->with('success', 'Harmonogram rozesílek byl úspěšně aktualizován.');
    }

    /**
     * Get schedules for a specific year (for AJAX)
     */
    public function getYearSchedules(Request $request, int $year)
    {
        $schedules = ShipmentSchedule::getForYear($year);
        
        return response()->json([
            'schedules' => $schedules,
        ]);
    }

    /**
     * Create schedules for next year
     */
    public function createNextYearSchedules()
    {
        $nextYear = now()->year + 2;
        
        // Check if schedules already exist
        $existingCount = ShipmentSchedule::where('year', $nextYear)->count();
        
        if ($existingCount > 0) {
            return redirect()->route('admin.subscription-config.index')
                ->with('info', "Harmonogram pro rok {$nextYear} již existuje.");
        }
        
        // Create schedules with default dates (15th billing, 20th shipment)
        for ($month = 1; $month <= 12; $month++) {
            ShipmentSchedule::create([
                'year' => $nextYear,
                'month' => $month,
                'billing_date' => \Carbon\Carbon::create($nextYear, $month, 15),
                'shipment_date' => \Carbon\Carbon::create($nextYear, $month, 20),
            ]);
        }
        
        return redirect()->route('admin.subscription-config.index')
            ->with('success', "Harmonogram pro rok {$nextYear} byl úspěšně vytvořen.");
    }
}
