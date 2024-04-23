<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2024 Bubka
 * Copyright (c) 2024 Anthony Rappa
 * Copyright (c) 2017 Yaakov Dahan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace App\Models\Traits;

use App\Models\AuthLog;
use Illuminate\Support\Carbon;

trait HasAuthenticationLog
{
    /**
     * Get all user's authentications from the auth log
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<AuthLog>
     */
    public function authentications()
    {
        return $this->morphMany(AuthLog::class, 'authenticatable')->latest('id');
    }

    /**
     * Get authentications for the provided timespan (in month)
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, AuthLog>
     */
    public function authenticationsByPeriod(int $period = 1)
    {
        $from = Carbon::now()->subMonths($period);

        return $this->authentications->filter(function (AuthLog $authentication) use ($from) {
            return $authentication->login_at >= $from || $authentication->logout_at >= $from;
        });
    }

    /**
     * Get the user's latest authentication
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<AuthLog>
     */
    public function latestAuthentication()
    {
        return $this->morphOne(AuthLog::class, 'authenticatable')->latestOfMany('login_at');
    }

    /**
     * Get the user's latest authentication datetime
     */
    public function lastLoginAt() : ?Carbon
    {
        return $this->authentications()->first()?->login_at;
    }

    /**
     * Get the user's latest successful login datetime
     */
    public function lastSuccessfulLoginAt() : ?Carbon
    {
        return $this->authentications()->whereLoginSuccessful(true)->first()?->login_at;
    }

    /**
     * Get the ip address of user's latest login
     */
    public function lastLoginIp() : ?string
    {
        return $this->authentications()->first()?->ip_address;
    }

    /**
     * Get the ip address of user's latest successful login
     */
    public function lastSuccessfulLoginIp() : ?string
    {
        return $this->authentications()->whereLoginSuccessful(true)->first()?->ip_address;
    }

    /**
     * Get the user's previous login datetime
     */
    public function previousLoginAt() : ?Carbon
    {
        return $this->authentications()->skip(1)->first()?->login_at;
    }

    /**
     * Get the ip address of user's previous login
     */
    public function previousLoginIp() : ?string
    {
        return $this->authentications()->skip(1)->first()?->ip_address;
    }

    /**
     * The notification channels to be used for notifications
     */
    public function notifyAuthLogVia() : array
    {
        return ['mail'];
    }
}
