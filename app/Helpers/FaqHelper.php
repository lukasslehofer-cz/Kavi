<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class FaqHelper
{
    /**
     * Otázky z lang souboru pro x-faq i JSON-LD FAQPage.
     *
     * Odkaz za odpovědí se v lang souboru zapisuje jako ['label' => '…', 'route' => 'gifts.index'].
     * Otázka s odkazem na stránku, která na této doméně není (vypnuté dárky, Pro firmy
     * mimo kavi.cz), se vynechá celá.
     *
     * @return array<int, array{q: string, a: string, link?: array{label: string, href: string}}>
     */
    public static function items(string $key, array $replace = []): array
    {
        $items = [];

        foreach (array_keys(__($key)) as $i) {
            $item = [
                'q' => __("$key.$i.q", $replace),
                'a' => __("$key.$i.a", $replace),
            ];

            $link = trans("$key.$i.link");
            if (is_array($link)) {
                if (! self::pageAvailable($link['route'])) {
                    continue;
                }
                $item['link'] = ['label' => $link['label'], 'href' => localizedRoute($link['route'])];
            }

            $items[] = $item;
        }

        return $items;
    }

    private static function pageAvailable(string $route): bool
    {
        return match ($route) {
            'gifts.index' => GiftHelper::enabled(),
            'corporate.index' => CorporateHelper::enabled(),
            default => Route::has($route),
        };
    }
}
