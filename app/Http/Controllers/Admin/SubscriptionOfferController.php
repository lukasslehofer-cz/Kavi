<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionOfferService;

class SubscriptionOfferController extends Controller
{
    /**
     * Nabídka předplatného v e-mailu o doručení objednávky: kolik kupců na
     * předplatné do 60 dnů přešlo. Výpočet je v SubscriptionOfferService::stats().
     */
    public function index(SubscriptionOfferService $offers)
    {
        return view('admin.subscription-offer.index', [
            'stats' => $offers->stats(),
        ]);
    }
}
