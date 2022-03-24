<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Arr;

class Authenticate extends Middleware
{
    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            // Will retreive the default guard
            $guards = [null];
        }
        else {
            // We inject the reserve-proxy guard to ensure it will be available for every routes
            // besides their declared guards. This way we ensure priority to declared guards and
            // a fallback to the reverse-proxy guard
            $proxyGuard = 'reverse-proxy-guard';

            if (config('auth.defaults.guard') === $proxyGuard && !Arr::has($guards, $proxyGuard)) {
                $guards[] = $proxyGuard;
            }
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        $this->unauthenticated($request, $guards);
    }

}