<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutInactiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // We do not track activity of non-logged-in user or user authenticated against a bearer token
        if (!Auth::guard('api')->check() || $request->bearerToken()) {
            return $next($request);
        }
     
        $user = Auth::guard($guard)->user();
        $now = Carbon::now();
        $inactiveFor = $now->diffInSeconds(Carbon::parse($user->last_seen_at));

        // Fetch all setting values
        $settingService = resolve('App\Services\SettingServiceInterface');
        $kickUserAfterXSecond = intval($settingService->get('kickUserAfter')) * 60;

        // If user has been inactive longer than the allowed inactivity period
        if ($kickUserAfterXSecond > 0 && $inactiveFor > $kickUserAfterXSecond) {
     
            $user->last_seen_at = $now->format('Y-m-d H:i:s');
            $user->save();

            Auth::logout();
            Log::notice('Inactive user detected, authentication rejected');
     
            return response()->json(['message' => 'unauthorised'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}