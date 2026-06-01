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
            // We build a space separated list of host sources to be allowed.
            Vite::useCspNonce();

            $authorizedAddresses = [];
            $appUrl              = (string) config('app.url');
            $appSource           = $this->buildCspHostSource($appUrl, true);

            if ($appSource !== null) {
                $authorizedAddresses[] = $appSource;
            }

            $authorizedAddresses[] = 'https://fastly.jsdelivr.net:*';

            // We add custom asset url if defined
            $assetUrl = (string) config('app.asset_url');

            if ($assetUrl && $assetUrl != $appUrl && $assetUrl != '/') {
                $assetSource = $this->buildCspHostSource($assetUrl, true);

                if ($assetSource !== null) {
                    $authorizedAddresses[] = $assetSource;
                }
            }

            // We add 'ws://' protocole and localhost ip address to avoid error with
            // Vite hot reload (when running 'npm run dev')
            // For the record: 127.0.0.1 is the only supported ip address as CSP
            // is intended to work with domain names -it's a www security mecanism)
            $isDevelopment = app()->environment(['local', 'development']);

            if ($isDevelopment && Vite::isRunningHot()) {
                $authorizedAddresses[] = 'ws://' . $request->getHost() . ':*';
                $authorizedAddresses[] = 'http://127.0.0.1:*';
                $authorizedAddresses[] = 'ws://127.0.0.1:*';
            }

            $authorizedAddresses = array_values(array_unique(array_filter($authorizedAddresses)));
            $authorizedAddresses = implode(' ', $authorizedAddresses);

            $directives['script-src']      = "script-src 'nonce-" . Vite::cspNonce() . "' 'wasm-unsafe-eval' 'strict-dynamic'";
            $directives['style-src']       = "style-src 'self' " . $authorizedAddresses . " 'unsafe-inline'";
            $directives['connect-src']     = "connect-src 'self' " . $authorizedAddresses;
            $directives['img-src']         = "img-src 'self' data: " . $authorizedAddresses;
            $directives['object-src']      = "object-src 'none'";
            $directives['default-src']     = "default-src 'self'";
            $directives['base-uri']        = "base-uri 'self'";
            $directives['frame-ancestors'] = "frame-ancestors 'none'";
            $directives['form-action']     = "form-action 'self'";

            // This one is to allow eval used by the vue devtools extension
            if ($isDevelopment) {
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

    /**
     * Build a CSP host source from a URL
     */
    private function buildCspHostSource(string $url, bool $allowAnyPort = false) : ?string
    {
        $parts = parse_url($url);

        if ($parts === false) {
            return null;
        }

        $scheme = $parts['scheme'] ?? null;
        $host   = $parts['host'] ?? null;

        if (! is_string($scheme) || $scheme === '' || ! is_string($host) || $host === '') {
            return null;
        }

        if (str_contains($host, ':') && ! str_starts_with($host, '[')) {
            $host = '[' . $host . ']';
        }

        $source = $scheme . '://' . $host;

        if ($allowAnyPort) {
            return $source . ':*';
        }

        if (isset($parts['port'])) {
            return $source . ':' . $parts['port'];
        }

        return $source;
    }
}
