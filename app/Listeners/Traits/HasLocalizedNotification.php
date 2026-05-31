<?php

namespace App\Listeners\Traits;

use App\Models\User;
use Illuminate\Support\Facades\App;

trait HasLocalizedNotification
{
    /**
     * Get the user's locale
     */
    private function userLocale(User $user) : mixed
    {
        return $user->preferredLocale() == 'browser'
            ? App::currentLocale()
            : $user->preferredLocale();
    }
}
