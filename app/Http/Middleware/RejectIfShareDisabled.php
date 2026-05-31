<?php

namespace App\Http\Middleware;

use App\Facades\Settings;
use Closure;
use Illuminate\Support\Facades\Log;

class RejectIfShareDisabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Settings::get('enableSharing')) {
            Log::info('Sharing feature is disabled. Rejecting request to sharing-related endpoint.');

            return response()->json([
                'message' => __('error.sharing_is_disabled'),
            ], 403);
        }

        return $next($request);
    }
}
