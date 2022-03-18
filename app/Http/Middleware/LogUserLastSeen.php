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
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, ...$quards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Activity coming from a client authenticated with a personal access token is not logged
            if( Auth::guard($guard)->check() && !$request->bearerToken()) {
                Auth::guard($guard)->user()->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
                Auth::guard($guard)->user()->save();
                break;
            }
        }

        return $next($request);
    }
}
