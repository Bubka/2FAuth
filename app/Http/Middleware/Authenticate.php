<?php

namespace App\Http\Middleware;

use App\Events\VisitedByProxyUser;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

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
        $proxyGuard = 'reverse-proxy-guard';

        if (empty($guards)) {
            // Will retreive the default guard
            $guards = [null];
        } else {
            // We replace routes guard by the reverse proxy guard if necessary
            if (config('auth.defaults.guard') === $proxyGuard) {
                $guards = [$proxyGuard];
            }
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);

                // We now have an authenticated user so we override the locale already set
                // by the SetLanguage global middleware
                $user = $this->auth->guard()->user();
                $lang = $user->preferences['lang'];

                if (in_array($lang, config('2fauth.locales')) && ! App::isLocale($lang)) {
                    App::setLocale($lang);
                }

                // Unlike the SessionGuard, the reverse-proxy-guard does not implement an attempt()
                // method when it comes to log the user in. So auth events (Login, FailedLogin, etc..) are not
                // fired by the guard, they are not even relevant.
                // So when using the reverse-proxy-guard, we fire a VisitedByProxyUser event from here, but only
                // if the user last request is older than 15 minutes to avoid too many dispatchs
                if ($guard === $proxyGuard && (! $user->last_seen_at || Carbon::parse($user->last_seen_at) < Carbon::now()->subMinutes(15))) {
                    event(new VisitedByProxyUser($user));
                }

                return;
            }
        }

        $this->unauthenticated($request, $guards);
    }
}
