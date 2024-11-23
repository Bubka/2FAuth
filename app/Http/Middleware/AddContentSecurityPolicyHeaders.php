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

            $assetUrl = config('app.asset_url') != config('app.url') ? config('app.asset_url') : '';

            $directives['script-src']      = "script-src 'nonce-" . Vite::cspNonce() . "' " . $assetUrl . ';';
            $directives['script-src-elem'] = "script-src-elem 'nonce-" . Vite::cspNonce() . "' " . $assetUrl . " 'strict-dynamic';";
            $directives['style-src']       = "style-src 'self' " . $assetUrl . " 'unsafe-inline';";
            $directives['connect-src']     = "connect-src 'self';";
            $directives['img-src']         = "img-src 'self' data: " . $assetUrl . ';';
            $directives['object-src']      = "object-src 'none';";

            $csp = implode(' ', $directives);

            /** @disregard Undefined function */
            /** @phpstan-ignore-next-line */
            return $next($request)->withHeaders([
                'Content-Security-Policy' => $csp,
            ]);
        }

        return $next($request);
    }
}
