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
        $inactiveFor = $now->diffInSeconds(Carbon::parse($user->last_seen_at));

        // Fetch all setting values
        $settings = Options::get();

        $kickUserAfterXSecond = intval($settings['kickUserAfter']) * 60;

        // If user has been inactive longer than the allowed inactivity period
        if ($kickUserAfterXSecond > 0 && $inactiveFor > $kickUserAfterXSecond) {
     
            $user->last_seen_at = $now->format('Y-m-d H:i:s');
            $user->save();

            $accessToken = $user->token();

            // phpunit does not generate token during tests, so we revoke it only if it exists
            // @codeCoverageIgnoreStart
            if( $accessToken ) {
                $accessToken->revoke();
            }
            // @codeCoverageIgnoreEnd
     
            return response()->json(['message' => 'unauthorised'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
