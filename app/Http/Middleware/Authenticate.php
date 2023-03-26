<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\App;

class Authenticate extends Middleware
{
    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            // Will retreive the default guard
            $guards = [null];
        } else {
            // We replace routes guard by the reverse proxy guard if necessary
            $proxyGuard = 'reverse-proxy-guard';

            if (config('auth.defaults.guard') === $proxyGuard) {
                $guards = [$proxyGuard];
            }
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);

                // We now have an authenticated user so we override the locale already set
                // by the SetLanguage global middleware
                $lang = $this->auth->guard()->user()->preferences['lang'];

                if (in_array($lang, config('2fauth.locales')) && ! App::isLocale($lang)) {
                    App::setLocale($lang);
                }

                return;
            }
        }

        $this->unauthenticated($request, $guards);
    }
}
