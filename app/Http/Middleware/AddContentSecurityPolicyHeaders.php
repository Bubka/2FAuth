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
            // Some CSP directives can be used with nonce but not all of them.
            // We build a space separated list of addresses to be allowed.
            Vite::useCspNonce();
            $authorizedAddresses[] = config('app.url') . ':*';
            $authorizedAddresses[] = 'https://fastly.jsdelivr.net:*';

            // We add custom asset url if defined
            if (config('app.asset_url') && config('app.asset_url') != config('app.url')) {
                $authorizedAddresses[] = config('app.asset_url') . ':*';
            }

            // We add 'ws://' protocole and localhost ip address to avoid error with
            // Vite hot reload (when running 'npm run dev')
            // For the record: 127.0.0.1 is the only supported ip address as CSP
            // is intended to work with domain names -it's a www security mecanism)
            if (config('app.env') === 'development' && Vite::isRunningHot()) {
                $authorizedAddresses[] = 'ws://' . $request->getHttpHost() . ':*';
                $authorizedAddresses[] = 'http://127.0.0.1:*';
                $authorizedAddresses[] = 'ws://127.0.0.1:*';
            }

            $authorizedAddresses = implode(' ', $authorizedAddresses);

            $directives['script-src']  = "script-src 'nonce-" . Vite::cspNonce() . "' 'strict-dynamic'";
            $directives['style-src']   = "style-src 'self' " . $authorizedAddresses . " 'unsafe-inline'";
            $directives['connect-src'] = "connect-src 'self' " . $authorizedAddresses;
            $directives['img-src']     = "img-src 'self' data: " . $authorizedAddresses;
            $directives['object-src']  = "object-src 'none'";
            $directives['default-src'] = "default-src 'self'";

            // This one is to allow eval used by the vue devtools extension
            if (config('app.env') === 'development') {
                $directives['script-src'] .= " 'unsafe-eval'";
            }

            $csp = implode('; ', $directives);

            /** @disregard Undefined function */
            /** @phpstan-ignore-next-line */
            return $next($request)->withHeaders([
                'Content-Security-Policy' => $csp,
            ]);
        }

        return $next($request);
    }
}
