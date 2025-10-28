<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RejectIfDemoMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('2fauth.config.isDemoApp')) {
            Log::info('Cannot request this action in Demo mode');

            return response()->json(['message' => __('message.disabled_in_demo')], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
