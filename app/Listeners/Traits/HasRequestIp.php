<?php

namespace App\Listeners\Traits;

use Illuminate\Http\Request;

trait HasRequestIp
{
    /**
     * Get the request user's IP address
     */
    private function getRequestIp(Request $request) : mixed
    {
        return config('2fauth.proxy_headers.forIp')
            ? $request->header(config('2fauth.proxy_headers.forIp'), $request->ip())
            : $request->ip();
    }
}
