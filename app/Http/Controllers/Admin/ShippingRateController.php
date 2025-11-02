<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PacketaCarrier;
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
        // Get ALL carriers from database (not filtered by country)
        // Admin can choose any carrier for any country
        $carriers = PacketaCarrier::getAllActive();
        
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
            'packeta_carrier_ids' => 'nullable|array',
            'packeta_carrier_ids.*' => 'exists:packeta_carriers,id',
        ]);

        // Convert checkbox values
        $validated['enabled'] = $request->has('enabled') && $request->enabled == '1';
        $validated['applies_to_subscriptions'] = $request->has('applies_to_subscriptions') && $request->applies_to_subscriptions == '1';
        
        // Handle nullable fields
        if (empty($validated['free_shipping_threshold_czk'])) {
            $validated['free_shipping_threshold_czk'] = null;
        }
        
        // Remove packeta_carrier_ids from validated data (it's handled separately)
        $carrierIds = $validated['packeta_carrier_ids'] ?? [];
        unset($validated['packeta_carrier_ids']);

        $rate->update($validated);

        // Sync many-to-many relationship with carriers
        // This will add new carriers, remove unselected ones, and keep existing ones
        $rate->packetaCarriers()->sync($carrierIds);

        Log::info('Shipping rate updated', [
            'rate_id' => $rate->id,
            'country' => $rate->country_name,
            'carriers_count' => count($carrierIds),
            'admin_id' => auth()->id(),
        ]);

        $carrierCount = count($carrierIds);
        $message = "Nastavení dopravy pro {$rate->country_name} bylo úspěšně aktualizováno.";
        if ($carrierCount > 0) {
            $message .= " Vybráno {$carrierCount} " . ($carrierCount === 1 ? 'dopravce' : ($carrierCount < 5 ? 'dopravci' : 'dopravců')) . ".";
        }

        return redirect()->route('admin.shipping.index')
            ->with('success', $message);
    }

    /**
     * AJAX: Get carriers for a country
     */
    public function getCarriers(Request $request)
    {
        // Return ALL carriers (not filtered by country)
        // Admin can choose any carrier for any country
        $carriers = PacketaCarrier::getAllActive();

        return response()->json(['carriers' => $carriers]);
    }
}

