<?php

namespace App\Models\Traits;

use Illuminate\Support\Carbon;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable as TraitsAuthenticationLoggable;

trait AuthenticationLoggable 
{
    use TraitsAuthenticationLoggable;

    public function authentications(int $period = 1)
    {
        $from = Carbon::now()->subMonths($period);

        return $this->morphMany(AuthenticationLog::class, 'authenticatable')
            ->where('login_at', '>=', $from)
            ->orWhere('logout_at', '>=', $from)
            ->orderByDesc('id');
    }
}
