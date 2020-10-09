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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if( Auth::guard('api')->check() ) {
            Auth::guard('api')->user()->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
            Auth::guard('api')->user()->save();
        }

        return $next($request);
    }
}
