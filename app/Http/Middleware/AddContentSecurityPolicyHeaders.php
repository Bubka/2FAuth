<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class AddContentSecurityPolicyHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        if (config('2fauth.config.contentSecurityPolicy')) {
            Vite::useCspNonce();

            return $next($request)->withHeaders([
                'Content-Security-Policy' => "script-src 'nonce-" . Vite::cspNonce() . "';style-src 'self' 'unsafe-inline';connect-src 'self';img-src 'self' data:;object-src 'none';base-uri 'none';",
            ]);
        }

        return $next($request);
    }
}
