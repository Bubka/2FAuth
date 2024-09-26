<?php

namespace App\Listeners\Authentication;

use App\Events\VisitedByProxyUser;
use App\Extensions\RemoteUserProvider;
use App\Notifications\SignedInWithNewDeviceNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use TypeError;

class VisitedByProxyUserListener extends AbstractAccessListener
{
    /**
     * Handle the event.
     */
    public function handle(mixed $event) : void
    {
        if (! $event instanceof VisitedByProxyUser) {
            throw new TypeError(self::class . '::handle(): Argument #1 ($event) must be of type ' . VisitedByProxyUser::class);
        }

        /**
         * @var \App\Models\User
         */
        $user      = $event->user;
        $ip        = config('2fauth.proxy_headers.forIp') ?? $this->request->ip();
        $userAgent = $this->request->userAgent();
        $guard     = config('auth.defaults.guard');
        $known     = $user->authentications()
            ->whereIpAddress($ip)
            ->whereUserAgent($userAgent)
            ->whereLoginSuccessful(true)
            ->whereGuard($guard)
            ->first();
        $newUser = Carbon::parse($user->{$user->getCreatedAtColumn()})->diffInMinutes(Carbon::now(), true) < 1;

        $log = $user->authentications()->create([
            'ip_address'       => $ip,
            'user_agent'       => $userAgent,
            'login_at'         => now(),
            'login_successful' => true,
            'guard'            => $guard,
            'login_method'     => null,
        ]);

        if (! $known && ! $newUser && Str::endsWith($user->email, RemoteUserProvider::FAKE_REMOTE_DOMAIN) && $user->preferences['notifyOnNewAuthDevice']) {
            $user->notify(new SignedInWithNewDeviceNotification($log));
        }
    }
}
