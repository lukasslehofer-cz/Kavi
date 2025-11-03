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
        // Debug: Log request info
        \Log::info('Update schedule request', [
            'has_promo_images' => $request->hasFile('promo_images'),
            'all_files' => $request->allFiles(),
            'schedules_keys' => array_keys($request->input('schedules', [])),
        ]);
        
        // Get all schedules data from request
        $schedulesData = $request->input('schedules', []);
        
        \Log::info('Processing schedules', [
            'count' => count($schedulesData),
            'first_schedule' => $schedulesData[0] ?? null,
        ]);
        
        foreach ($schedulesData as $index => $scheduleData) {
            \Log::info('Processing schedule at index ' . $index, [
                'has_id' => isset($scheduleData['id']),
                'id' => $scheduleData['id'] ?? 'missing',
                'data' => $scheduleData,
            ]);
            
            // Skip if no ID
            if (!isset($scheduleData['id'])) {
                \Log::warning('Skipping schedule without ID at index ' . $index);
                continue;
            }
            
            $schedule = ShipmentSchedule::find($scheduleData['id']);
            
            if ($schedule && !$schedule->isPast()) {
                // Helper function to convert empty string to null
                $toNullIfEmpty = function($value) {
                    return empty($value) ? null : $value;
                };
                
                $updateData = [
                    'billing_date' => $scheduleData['billing_date'],
                    'shipment_date' => $scheduleData['shipment_date'],
                    'notes' => $toNullIfEmpty($scheduleData['notes'] ?? null),
                    // Coffee slots - convert empty strings to null
                    'coffee_slot_e1' => $toNullIfEmpty($scheduleData['coffee_slot_e1'] ?? null),
                    'coffee_slot_e2' => $toNullIfEmpty($scheduleData['coffee_slot_e2'] ?? null),
                    'coffee_slot_e3' => $toNullIfEmpty($scheduleData['coffee_slot_e3'] ?? null),
                    'coffee_slot_f1' => $toNullIfEmpty($scheduleData['coffee_slot_f1'] ?? null),
                    'coffee_slot_f2' => $toNullIfEmpty($scheduleData['coffee_slot_f2'] ?? null),
                    'coffee_slot_f3' => $toNullIfEmpty($scheduleData['coffee_slot_f3'] ?? null),
                    'coffee_slot_d' => $toNullIfEmpty($scheduleData['coffee_slot_d'] ?? null),
                ];

                $schedule->update($updateData);
                
                // Update stock reservations only if coffee slots were changed and are configured
                $slotsChanged = $schedule->wasChanged([
                    'coffee_slot_e1', 'coffee_slot_e2', 'coffee_slot_e3',
                    'coffee_slot_f1', 'coffee_slot_f2', 'coffee_slot_f3', 'coffee_slot_d'
                ]);
                
                if ($slotsChanged && $schedule->hasCoffeeSlotsConfigured()) {
                    try {
                        $reservationService = app(\App\Services\StockReservationService::class);
                        $reservationService->updateReservationsForSchedule($schedule);
                        
                        \Log::info('Stock reservations updated after slot change', [
                            'schedule_id' => $schedule->id,
                            'month' => $schedule->month,
                            'year' => $schedule->year,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to update reservations after slot change', [
                            'schedule_id' => $schedule->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        }
        
        // Handle promo image uploads separately (indexed by schedule ID)
        if ($request->hasFile('promo_images')) {
            $promoImages = $request->file('promo_images');
            
            \Log::info('Processing promo images', [
                'count' => count($promoImages),
                'schedule_ids' => array_keys($promoImages),
            ]);
            
            foreach ($promoImages as $scheduleId => $file) {
                $schedule = ShipmentSchedule::find($scheduleId);
                
                if ($schedule && !$schedule->isPast() && $file->isValid()) {
                    \Log::info("Processing file for schedule {$scheduleId}", [
                        'month' => $schedule->month,
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                    ]);
                    
                    // Delete old image if exists
                    if ($schedule->promo_image && file_exists(public_path($schedule->promo_image))) {
                        unlink(public_path($schedule->promo_image));
                    }
                    
                    // Upload to public/images/promo-images (same as products)
                    $filename = time() . '_' . $scheduleId . '_' . $file->getClientOriginalName();
                    $file->move(public_path('images/promo-images'), $filename);
                    $path = 'images/promo-images/' . $filename;
                    
                    $schedule->update(['promo_image' => $path]);
                    
                    \Log::info("File stored at: {$path}");
                }
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

    /**
     * Update single schedule
     */
    public function updateSingleSchedule(Request $request, ShipmentSchedule $schedule)
    {
        if ($schedule->isPast()) {
            return redirect()->route('admin.subscription-config.index')
                ->with('error', 'Nelze editovat minulou rozesílku.');
        }

        $validated = $request->validate([
            'billing_date' => 'required|date',
            'shipment_date' => 'required|date',
            'notes' => 'nullable|string',
            'coffee_slot_e1' => 'nullable|exists:products,id',
            'coffee_slot_e2' => 'nullable|exists:products,id',
            'coffee_slot_e3' => 'nullable|exists:products,id',
            'coffee_slot_f1' => 'nullable|exists:products,id',
            'coffee_slot_f2' => 'nullable|exists:products,id',
            'coffee_slot_f3' => 'nullable|exists:products,id',
            'coffee_slot_d' => 'nullable|exists:products,id',
        ]);

        // Convert empty strings to null
        foreach (['coffee_slot_e1', 'coffee_slot_e2', 'coffee_slot_e3', 'coffee_slot_f1', 'coffee_slot_f2', 'coffee_slot_f3', 'coffee_slot_d', 'notes'] as $field) {
            if (isset($validated[$field]) && empty($validated[$field])) {
                $validated[$field] = null;
            }
        }

        // Handle promo image upload
        if ($request->hasFile('promo_image') && $request->file('promo_image')->isValid()) {
            $file = $request->file('promo_image');
            
            // Delete old image if exists
            if ($schedule->promo_image && file_exists(public_path($schedule->promo_image))) {
                unlink(public_path($schedule->promo_image));
            }
            
            // Upload to public/images/promo-images (same as products)
            $filename = time() . '_' . $schedule->id . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/promo-images'), $filename);
            $validated['promo_image'] = 'images/promo-images/' . $filename;
        }

        $schedule->update($validated);

        // Update stock reservations if coffee slots changed and are configured
        $slotsChanged = $schedule->wasChanged([
            'coffee_slot_e1', 'coffee_slot_e2', 'coffee_slot_e3',
            'coffee_slot_f1', 'coffee_slot_f2', 'coffee_slot_f3', 'coffee_slot_d'
        ]);

        if ($slotsChanged && $schedule->hasCoffeeSlotsConfigured()) {
            try {
                $reservationService = app(\App\Services\StockReservationService::class);
                $reservationService->updateReservationsForSchedule($schedule);
                
                \Log::info('Stock reservations updated after single schedule update', [
                    'schedule_id' => $schedule->id,
                    'month' => $schedule->month,
                    'year' => $schedule->year,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to update reservations after single schedule update', [
                    'schedule_id' => $schedule->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('admin.subscription-config.index')
            ->with('success', "Rozesílka pro {$schedule->month_name} {$schedule->year} byla úspěšně aktualizována.");
    }

    /**
     * Delete promo image from schedule
     */
    public function deletePromoImage(ShipmentSchedule $schedule)
    {
        if ($schedule->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Nelze smazat obrázek z minulé rozesílky.'
            ], 403);
        }

        if ($schedule->promo_image && file_exists(public_path($schedule->promo_image))) {
            unlink(public_path($schedule->promo_image));
        }

        $schedule->update(['promo_image' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Promo obrázek byl úspěšně smazán.'
        ]);
    }
}
