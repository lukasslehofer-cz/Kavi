<?php

namespace App\Http\Controllers;

use App\Models\Roastery;
use App\Models\Product;
use Illuminate\Http\Request;

class MonthlyFeatureController extends Controller
{
    /**
     * Show the roastery and coffees of the month
     */
    public function index()
    {
        // Get the target month based on current date
        $today = now();
        $currentDay = $today->day;
        
        // If today is 16th or later, show next month
        if ($currentDay >= 16) {
            $displayMonth = $today->copy()->addMonth();
        } else {
            $displayMonth = $today->copy();
        }
        
        $targetMonth = $displayMonth->format('Y-m');
        
        // Get month name in nominative case (Říjen, not října)
        $months = [
            1 => 'Leden', 2 => 'Únor', 3 => 'Březen', 4 => 'Duben',
            5 => 'Květen', 6 => 'Červen', 7 => 'Červenec', 8 => 'Srpen',
            9 => 'Září', 10 => 'Říjen', 11 => 'Listopad', 12 => 'Prosinec'
        ];
        $monthName = $months[$displayMonth->month];
        $displayYear = $displayMonth->year;
        $monthNameWithYear = $monthName . ' ' . $displayYear;
        
        // Get roasteries of the month
        $roasteries = Roastery::getRoasteriesOfMonth();
        
        // Get coffees of month with eager loaded roastery
        $coffees = Product::with('roastery')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', $targetMonth)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('monthly-feature.index', compact('roasteries', 'coffees', 'monthName', 'displayYear', 'monthNameWithYear'));
    }
}

