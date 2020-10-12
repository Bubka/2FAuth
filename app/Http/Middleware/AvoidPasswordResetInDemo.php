<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

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

        if( config('app.options.isDemoApp') ) {
            return response()->json(['requestFailed' => __('auth.forms.no_reset_password_in_demo')], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
