<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class RejectIfReverseProxy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('auth.defaults.guard') === 'reverse-proxy-guard') {
            Log::info('Cannot request this action in reverse proxy mode');

            return response()->json([
                'message' => __('error.unsupported_with_reverseproxy'),
            ], 405);
        }

        return $next($request);
    }
}
