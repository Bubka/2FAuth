<?php

namespace App\Listeners\Authentication;

use App\Events\VisitedByProxyUser;
use App\Extensions\RemoteUserProvider;
use App\Notifications\SignedInWithNewDevice;
use Illuminate\Support\Carbon;

class VisitedByProxyUserListener extends AbstractAccessListener
{
    /**
     * Handle the event.
     */
    public function handle(mixed $event) : void
    {
        if (! $event instanceof VisitedByProxyUser) {
            return;
        }

        /**
         * @var \App\Models\User
         */
        $user      = $event->user;
        $ip        = config('2fauth.proxy_headers.forIp') ?? $this->request->ip();
        $userAgent = $this->request->userAgent();
        $known     = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->whereLoginSuccessful(true)->first();
        $newUser   = Carbon::parse($user->{$user->getCreatedAtColumn()})->diffInMinutes(Carbon::now(), true) < 1;
        $guard     = config('auth.defaults.guard');

        $log = $user->authentications()->create([
            'ip_address'       => $ip,
            'user_agent'       => $userAgent,
            'login_at'         => now(),
            'login_successful' => true,
            'guard'            => $guard,
        ]);

        if (! $known && ! $newUser && ! str_ends_with($user->email, RemoteUserProvider::FAKE_REMOTE_DOMAIN) && $user->preferences['notifyOnNewAuthDevice']) {
            $user->notify(new SignedInWithNewDevice($log));
        }
    }
}
