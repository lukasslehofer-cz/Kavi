<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SubscriptionConfig;
use App\Models\SubscriptionPlan;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::forShop()
            ->featured()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $subscriptionPlans = SubscriptionPlan::active()
            ->orderBy('price')
            ->get();

        // Get subscription pricing configuration
        $subscriptionPricing = [
            '2' => SubscriptionConfig::get('price_2_bags', 500),
            '3' => SubscriptionConfig::get('price_3_bags', 720),
            '4' => SubscriptionConfig::get('price_4_bags', 920),
        ];

        // Get roasteries and coffees of the month
        $roasteriesOfMonth = \App\Models\Roastery::getRoasteriesOfMonth();
        
        // Get coffees of month
        $today = now();
        $currentDay = $today->day;
        $targetMonth = ($currentDay >= 16) 
            ? $today->copy()->addMonth()->format('Y-m')
            : $today->format('Y-m');
        
        $coffeesOfMonth = Product::with('roastery')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', $targetMonth)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(7)
            ->get();

        return view('home', compact('featuredProducts', 'subscriptionPlans', 'subscriptionPricing', 'roasteriesOfMonth', 'coffeesOfMonth'));
    }
}



