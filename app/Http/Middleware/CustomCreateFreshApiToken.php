<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;

class CustomCreateFreshApiToken extends CreateFreshApiToken
{
    /**
     * Determine if the request should receive a fresh token.
     *
     * @codeCoverageIgnore
     */
    protected function requestShouldReceiveFreshToken(Request $request) : bool
    {
        return ! is_null($request->user($this->guard));
    }
}
