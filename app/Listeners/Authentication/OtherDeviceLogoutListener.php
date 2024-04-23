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

namespace App\Listeners\Authentication;

use App\Models\AuthLog;
use Illuminate\Auth\Events\OtherDeviceLogout;

class OtherDeviceLogoutListener extends AbstractAccessListener
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(mixed $event) : void
    {
        if (! $event instanceof OtherDeviceLogout) {
            return;
        }

        /**
         * @var \App\Models\User
         */
        $user      = $event->user;
        $ip        = config('2fauth.proxy_headers.forIp') ?? $this->request->ip();
        $userAgent = $this->request->userAgent();
        $authLog   = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();
        $guard     = $event->guard;

        if (! $authLog) {
            $authLog = new AuthLog([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'guard'      => $guard,
            ]);
        }

        foreach ($user->authentications()->whereLoginSuccessful(true)->whereNull('logout_at')->get() as $log) {
            if ($log->id !== $authLog->id) {
                $log->update([
                    'cleared_by_user' => true,
                    'logout_at'       => now(),
                ]);
            }
        }
    }
}
