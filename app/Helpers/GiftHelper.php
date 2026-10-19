<?php

namespace App\Helpers;

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
}
