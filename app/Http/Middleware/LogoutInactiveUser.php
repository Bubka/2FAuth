<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Carbon\Carbon;
use App\Classes\Options;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LogoutInactiveUser
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

        // Not a logged in user
        if (!Auth::guard('api')->check()) {
            return $next($request);
        }
     
        $user = Auth::guard('api')->user();

        $now = Carbon::now();
        $last_seen = Carbon::parse($user->last_seen_at);
        $inactiveFor = $now->diffInMinutes($last_seen);

        // Fetch all setting values
        $settings = Options::get();
     
        // If user has been inactivity longer than the allowed inactivity period
        if ($settings['kickUserAfter'] > 0 && $inactiveFor > $settings['kickUserAfter']) {

            $user->last_seen_at = $now->format('Y-m-d H:i:s');
            $user->save();
     
            $accessToken = Auth::user()->token();
            $accessToken->revoke();
     
            return response()->json(['message' => 'unauthorised'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
