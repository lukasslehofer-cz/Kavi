<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SubscriptionConfig;
use App\Models\SubscriptionPlan;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
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

        return view('home', compact('featuredProducts', 'subscriptionPlans', 'subscriptionPricing'));
    }
}



