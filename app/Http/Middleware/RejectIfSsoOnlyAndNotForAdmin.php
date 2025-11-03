<?php

namespace App\Http\Middleware;

use App\Facades\Settings;
use App\Models\User;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RejectIfSsoOnlyAndNotForAdmin
{
    /**
     * Reject the request when it aims to modify or impact a user account in those 2 conditions:
     * - The impacted account does not have the Administrator role
     * - Authentication is restricted to SSO only
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Settings::get('useSsoOnly')) {
            if ($email = $request->input('email', null)) {
                $user = User::whereEmail($email)->first();
            } else {
                $user = Auth::user();
            }

            if ($user?->isAdministrator()) {
                return $next($request);
            }

            Log::notice(sprintf('Request to %s rejected, only Admins can request it while authentication is restricted to SSO only', $request->getPathInfo()));

            return response()->json(['message' => __('error.unsupported_with_sso_only')], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        return $next($request);
    }
}
