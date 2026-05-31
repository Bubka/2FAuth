<?php

namespace App\Http\Middleware;

use App\Facades\Settings;
use Closure;
use Illuminate\Support\Facades\Log;

class RejectIfAllUsersSharingScopeDisabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Settings::get('enableAllUsersSharingScope')) {
            Log::info('All users sharing scope is disabled. Rejecting request to all-users sharing endpoint.');

            return response()->json([
                'message' => __('error.all_users_sharing_scope_is_disabled'),
            ], 403);
        }

        return $next($request);
    }
}