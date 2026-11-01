<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\AccessToken;

class RejectPatIfNotAllowed
{
    /**
     * Reject requests authenticated with a Personal Access Token when the
     * 'manage-pat' gate denies PAT usage (i.e. SSO only without PAT bypass).
     *
     * Requests authenticated by the SPA (transient token) are not impacted.
     *
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()?->token();

        if ($token instanceof AccessToken && $token->client?->hasGrantType('personal_access') && Gate::denies('manage-pat')) {
            Log::notice(sprintf('Request to %s rejected, PAT usage is not allowed while authentication is restricted to SSO only', $request->getPathInfo()));

            return response()->json(['message' => __('error.unauthorized')], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
