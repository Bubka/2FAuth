<?php

namespace App\Http\Middleware;

use Laravel\Passport\Http\Middleware\CreateFreshApiToken as CreateFreshApiToken;

class CustomCreateFreshApiToken extends CreateFreshApiToken
{
   
    /**
     * Determine if the request should receive a fresh token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function requestShouldReceiveFreshToken($request)
    {
        return $request->user($this->guard);
    }
}