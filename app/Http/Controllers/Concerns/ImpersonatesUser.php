<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;

trait ImpersonatesUser
{
    /**
     * Get the user to display (either authenticated user or user being viewed by admin via ?view_as=).
     */
    protected function getViewingUser()
    {
        // Check if admin is viewing another user's dashboard
        if (request()->has('view_as') && auth()->user()?->is_admin) {
            $user = User::find(request()->get('view_as'));

            if ($user) {
                return $user;
            }
        }

        return auth()->user();
    }
}
