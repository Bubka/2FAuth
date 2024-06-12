<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * TrustProxies constructor.
     */
    public function __construct()
    {
        $this->proxies = (string) config('2fauth.config.trustedProxies');
    }
}
