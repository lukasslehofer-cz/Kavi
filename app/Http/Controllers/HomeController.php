<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\SubscriptionConfig;
use App\Models\SubscriptionPlan;
use App\Services\GoogleReviewsService;

class HomeController extends Controller
{
    public function index(GoogleReviewsService $googleReviews)
    {
        $featuredProducts = Product::forShop()
            ->withPriceInCurrentCurrency()
            ->featured()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $subscriptionPlans = SubscriptionPlan::active()
            ->orderBy('price')
            ->get();

        // Get subscription pricing configuration based on currency
        if (CurrencyHelper::isEur()) {
            $subscriptionPricing = [
                '2' => SubscriptionConfig::get('price_2_bags_eur', 20),
                '3' => SubscriptionConfig::get('price_3_bags_eur', 29),
                '4' => SubscriptionConfig::get('price_4_bags_eur', 37),
            ];
        } else {
            $subscriptionPricing = [
                '2' => SubscriptionConfig::get('price_2_bags', 500),
                '3' => SubscriptionConfig::get('price_3_bags', 720),
                '4' => SubscriptionConfig::get('price_4_bags', 920),
            ];
        }

        // Get roasteries and coffees of the month
        $roasteriesOfMonth = \App\Models\Roastery::getRoasteriesOfMonth();
        
        // Get coffees of month
        $today = now();
        $currentDay = $today->day;
        $targetMonth = ($currentDay >= 16) 
            ? $today->copy()->addMonthNoOverflow()->format('Y-m')
            : $today->format('Y-m');
        
        $coffeesOfMonth = Product::with('roastery')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', $targetMonth)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(7)
            ->get();

        // Google recenze do sekce "Co říkají naši zákazníci". Dokud není zdroj
        // nakonfigurovaný, vrátí prázdno a šablona ukáže prosbu o hodnocení.
        $testimonials = $googleReviews->latest(3);

        return view('home', compact('featuredProducts', 'subscriptionPlans', 'subscriptionPricing', 'roasteriesOfMonth', 'coffeesOfMonth', 'testimonials'));
    }
}



