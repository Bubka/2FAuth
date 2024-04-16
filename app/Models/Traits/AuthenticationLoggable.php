<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable as TraitsAuthenticationLoggable;

trait AuthenticationLoggable 
{
    use TraitsAuthenticationLoggable;

    public function authentications()
    {
        return $this->morphMany(AuthenticationLog::class, 'authenticatable')->latest('id');
    }

    /**
     * Get authentications for the provided timespan (in month)
     */
    public function authenticationsByPeriod(int $period = 1)
    {
        $from = Carbon::now()->subMonths($period);

        return $this->authentications->filter(function (AuthenticationLog $authentication) use ($from) {
            return $authentication->login_at >= $from || $authentication->logout_at >= $from;
        });
    }
}
