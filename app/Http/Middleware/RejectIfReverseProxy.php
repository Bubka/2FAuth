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
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('auth.defaults.guard') === 'reverse-proxy-guard') {
            Log::notice('Cannot request this action in Demo mode');

            return response()->json([
                'message' => __('errors.unsupported_with_reverseproxy')], 400);
        }

        return $next($request);
    }
}
