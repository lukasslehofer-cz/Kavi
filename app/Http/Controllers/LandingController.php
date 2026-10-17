<?php

namespace App\Http\Controllers;

use App\Helpers\BoxPricing;
use App\Helpers\CurrencyHelper;
use App\Helpers\ShipmentCycles;
use App\Services\CoffeeOfMonthService;
use App\Services\GoogleReviewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Landing page z reklamy (noindex). Ceny, data uzávěrky a rozesílky i Káva měsíce
 * jdou z databáze, UTM parametry z reklamy se přenášejí do všech odkazů na konfigurátor.
 */
class LandingController extends Controller
{
    /** Parametry z reklamy, které se mají dostat až do konfigurátoru */
    private const TRACKING_PARAMS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'fbclid'];

    public function europe(Request $request, CoffeeOfMonthService $coffeeOfMonth, GoogleReviewsService $googleReviews)
    {
        $currency = CurrencyHelper::code();
        $cycle = ShipmentCycles::current();
        $lineup = $coffeeOfMonth->lineup();
        $tiers = BoxPricing::tiers($currency);
        $reviews = $googleReviews->latest(3);
        $rating = $googleReviews->summary();

        // Odkazy do konfigurátoru s UTM z reklamy
        $tracking = array_filter(Arr::only($request->query(), self::TRACKING_PARAMS), 'is_string');
        $boxUrl = fn (array $params = []) => localizedRoute('subscriptions.index') . (($query = http_build_query($params + $tracking)) ? '?' . $query : '');

        // Jednotková cena: v Kč za gram, v eurech za 100 g (viz BoxPricing::formatUnitPrice)
        $unit = function (int $bags) use ($currency) {
            if ($currency === 'EUR') {
                return CurrencyHelper::formatByCurrency(round(BoxPricing::boxPrice($bags, $currency) / BoxPricing::grams($bags) * 100, 2), 'EUR', 2);
            }

            return CurrencyHelper::formatByCurrency(BoxPricing::pricePerGram($bags, $currency), 'CZK', 2);
        };

        $replace = [
            'cutoff' => $cycle->cutoffShort(),
            'shipment' => $cycle->shipmentShort(),
            'day' => $cycle->shipmentDate->day,
            'unit_m' => $unit(2),
            'unit_l' => $unit(3),
            'unit_xl' => $unit(4),
            'surcharge' => CurrencyHelper::formatByCurrency(BoxPricing::oneTimeSurcharge($currency), $currency, 0),
        ];

        $faq = [];
        foreach (array_keys(__('landing.europe.faq.items')) as $i) {
            $faq[] = [
                'q' => __("landing.europe.faq.items.$i.q"),
                'a' => __("landing.europe.faq.items.$i.a", $replace),
            ];
        }

        return view('landing.europe', [
            'cycle' => $cycle,
            'lineup' => $lineup,
            'tiers' => $tiers,
            'reviews' => $reviews,
            'rating' => $rating,
            'faq' => $faq,
            'replace' => $replace,
            'boxUrl' => $boxUrl,
            'fromPrice' => CurrencyHelper::formatByCurrency(BoxPricing::cheapest($currency), $currency, 0),
        ]);
    }
}
