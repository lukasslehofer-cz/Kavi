<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class GiftHelper
{
    /**
     * Jsou dárky zapnuté a existuje stránka pro jejich nákup?
     * Podle toho se zobrazují všechny vstupy na stránku dárků.
     */
    public static function enabled(): bool
    {
        return (bool) config('gifts.enabled') && Route::has('gifts.index');
    }

    /**
     * Zobrazit dočasný odkaz na dárky v CTA pod blogovými články?
     * Platí do data gifts.blog_cta_until včetně.
     */
    public static function blogCtaVisible(): bool
    {
        $until = config('gifts.blog_cta_until');

        if (! self::enabled() || ! $until) {
            return false;
        }

        return now()->lte(Carbon::parse($until)->endOfDay());
    }
}
