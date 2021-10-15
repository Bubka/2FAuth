<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AvoidPasswordResetInDemo
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

        if( config('2fauth.config.isDemoApp') ) {
            Log::notice('Cannot request a password reset in Demo mode');

            return response()->json(['message' => __('auth.forms.no_reset_password_in_demo')], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
