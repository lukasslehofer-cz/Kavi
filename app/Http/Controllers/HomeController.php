<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

        return view('home', compact('featuredProducts', 'subscriptionPlans'));
    }
}


