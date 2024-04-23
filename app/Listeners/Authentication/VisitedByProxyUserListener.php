<?php

namespace App\Authentication\Listeners;

use App\Events\VisitedByProxyUser;
use App\Listeners\Authentication\AbstractAccessListener;
use App\Notifications\SignedInWithNewDevice;
use Illuminate\Support\Carbon;

class VisitedByProxyUserListener extends AbstractAccessListener
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(mixed $event): void
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
        $newUser   = Carbon::parse($user->{$user->getCreatedAtColumn()})->diffInMinutes(Carbon::now()) < 1;

        $log = $user->authentications()->create([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'login_at' => now(),
            'login_successful' => true,
        ]);

        if (! $known && ! $newUser && $user->preferences['notifyOnNewAuthDevice']) {
            $user->notify(new SignedInWithNewDevice($log));
        }
    }
}