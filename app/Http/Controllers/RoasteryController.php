<?php

namespace App\Http\Controllers;

use App\Models\Roastery;
use Illuminate\Http\Request;

class RoasteryController extends Controller
{
    public function index(Request $request)
    {
        $query = Roastery::active()->orderBy('sort_order')->orderBy('name');
        
        // Filter by country if provided
        if ($request->has('country') && $request->country) {
            $query->where('country', $request->country);
        }
        
        $roasteries = $query->get();
        
        // Get all unique countries for filter
        $countries = Roastery::active()
            ->select('country', 'country_flag')
            ->groupBy('country', 'country_flag')
            ->orderBy('country')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->country => $item->country_flag];
            });
        
        $selectedCountry = $request->country;
        
        return view('roasteries.index', compact('roasteries', 'countries', 'selectedCountry'));
    }

    public function show(Roastery $roastery)
    {
        // Load products with proper ordering
        $coffeeOfMonthProducts = $roastery->products()
            ->where('is_coffee_of_month', true)
            ->orderBy('coffee_of_month_date', 'desc')
            ->get();
        
        $activeProducts = $roastery->products()
            ->where('is_active', true)
            ->where('is_coffee_of_month', false)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        $historicalProducts = $roastery->products()
            ->where('is_active', false)
            ->where('is_coffee_of_month', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('roasteries.show', compact(
            'roastery',
            'coffeeOfMonthProducts',
            'activeProducts',
            'historicalProducts'
        ));
    }
}

