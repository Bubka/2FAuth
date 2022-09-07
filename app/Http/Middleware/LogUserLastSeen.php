<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LogUserLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // We do not track activity of:
            // - Guest
            // - User authenticated against a bearer token
            // - User authenticated via a reverse-proxy
            if (Auth::guard($guard)->check() && !$request->bearerToken() && config('auth.defaults.guard') !== 'reverse-proxy-guard') {
                Auth::guard($guard)->user()->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
                Auth::guard($guard)->user()->save();
                break;
            }
        }

        return $next($request);
    }
}
