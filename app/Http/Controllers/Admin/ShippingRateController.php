<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use App\Services\PacketaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingRateController extends Controller
{
    public function __construct(private PacketaService $packetaService)
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of all shipping rates
     */
    public function index()
    {
        $rates = ShippingRate::orderBy('country_name')->get();
        
        return view('admin.shipping.index', compact('rates'));
    }

    /**
     * Show the form for editing a shipping rate
     */
    public function edit(ShippingRate $rate)
    {
        // Get available carriers for this country from Packeta API
        $carriers = $this->packetaService->getCarriersForCountry($rate->country_code);
        
        // If API fails, use default carriers
        if (empty($carriers)) {
            $defaultCarriers = PacketaService::getDefaultCarriers();
            $carriers = $defaultCarriers[$rate->country_code] ?? [];
        }
        
        return view('admin.shipping.edit', compact('rate', 'carriers'));
    }

    /**
     * Update the specified shipping rate
     */
    public function update(Request $request, ShippingRate $rate)
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
            'price_czk' => 'required|numeric|min:0',
            'price_eur' => 'required|numeric|min:0',
            'applies_to_subscriptions' => 'required|boolean',
            'free_shipping_threshold_czk' => 'nullable|numeric|min:0',
            'packeta_carrier_id' => 'nullable|string',
            'packeta_carrier_name' => 'nullable|string',
        ]);

        // Convert checkbox values
        $validated['enabled'] = $request->has('enabled') && $request->enabled == '1';
        $validated['applies_to_subscriptions'] = $request->has('applies_to_subscriptions') && $request->applies_to_subscriptions == '1';
        
        // Handle nullable fields
        if (empty($validated['free_shipping_threshold_czk'])) {
            $validated['free_shipping_threshold_czk'] = null;
        }
        
        if (empty($validated['packeta_carrier_id'])) {
            $validated['packeta_carrier_id'] = null;
            $validated['packeta_carrier_name'] = null;
        }

        $rate->update($validated);

        Log::info('Shipping rate updated', [
            'rate_id' => $rate->id,
            'country' => $rate->country_name,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.shipping.index')
            ->with('success', "Nastavení dopravy pro {$rate->country_name} bylo úspěšně aktualizováno.");
    }

    /**
     * AJAX: Get carriers for a country
     */
    public function getCarriers(Request $request)
    {
        $countryCode = $request->input('country_code');
        
        if (!$countryCode) {
            return response()->json(['error' => 'Country code is required'], 400);
        }

        $carriers = $this->packetaService->getCarriersForCountry($countryCode);
        
        // If API fails, use default carriers
        if (empty($carriers)) {
            $defaultCarriers = PacketaService::getDefaultCarriers();
            $carriers = $defaultCarriers[$countryCode] ?? [];
        }

        return response()->json(['carriers' => $carriers]);
    }
}

