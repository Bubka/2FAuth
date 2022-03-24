<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController as PassportPersonalAccessTokenController;

class PersonalAccessTokenController extends PassportPersonalAccessTokenController
{
    /**
     * Get all of the personal access tokens for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser(Request $request)
    {
        // WebAuthn is useless when authentication is handle by
        // a reverse proxy so we return a 202 response to tell the
        // client nothing more will happen
        if (config('auth.defaults.guard') === 'reverse-proxy-guard') {
            return response()->json([
                'message' => 'no personal access token with reverse proxy'], 202);
        }

        parent::forUser($request);
    }

}