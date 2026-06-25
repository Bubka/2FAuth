<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  string  $guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::user() != null) {
            Auth::logoutCurrentDevice();
        }

        return $next($request);
    }
}
