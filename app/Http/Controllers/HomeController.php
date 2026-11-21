<?php

namespace App\Http\Controllers;

use App\Helpers\BoxPricing;
use App\Helpers\CorporateHelper;
use App\Helpers\CurrencyHelper;
use App\Helpers\FaqHelper;
use App\Helpers\GiftHelper;
use App\Helpers\ShipmentCycles;
use App\Services\CoffeeOfMonthService;
use App\Services\GoogleReviewsService;

class HomeController extends Controller
{
    public function index(GoogleReviewsService $googleReviews, CoffeeOfMonthService $coffeeOfMonth)
    {
        // Pražírny a kávy měsíce podle stejné uzávěrky, jakou mají objednávky
        $lineup = $coffeeOfMonth->lineup();

        // Řádky tarifů M / L / XL s cenou za gram
        $tiers = BoxPricing::tiers();

        // Google recenze do sekce "Co říkají naši zákazníci". Dokud není zdroj
        // nakonfigurovaný, vrátí prázdno a šablona ukáže prosbu o hodnocení.
        $testimonials = $googleReviews->latest(3);
        // Celkové hodnocení od Googlu (hero a recenze), bez dat se nezobrazí
        $rating = $googleReviews->summary();

        $cycle = ShipmentCycles::current();
        $giftsEnabled = GiftHelper::enabled();

        // Ceny "od" v sekci Darujte kávu: nejlevnější box × počet měsíců, nejnižší poukaz
        $currency = CurrencyHelper::code();
        $giftFrom = [
            'subscription' => BoxPricing::cheapest($currency),
            'voucher' => min(config('gifts.voucher_amounts.' . $currency, [0])),
        ];

        // Časté dotazy pro sekci na stránce i pro JSON-LD FAQPage. Otázka na dárky jen se
        // zapnutými dárky, otázka na firemní dárky jen na kavi.cz (FaqHelper).
        $faq = FaqHelper::items('home.faq', [
            'cutoff' => $cycle->cutoffShort(),
            'shipment' => $cycle->shipmentShort(),
            'min' => config('corporate.min_quantity', 10),
            'max' => CorporateHelper::formatNumber(CorporateHelper::maxDiscount()),
        ]);

        return view('home', compact('lineup', 'tiers', 'testimonials', 'rating', 'cycle', 'giftsEnabled', 'giftFrom', 'faq'));
    }
}
