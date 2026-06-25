<?php

namespace App\Extensions;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Laragear\WebAuthn\Auth\WebAuthnUserProvider;

class WebauthnTwoFAuthUserProvider extends WebAuthnUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  User  $user
     */
    public function validateCredentials($user, array $credentials) : bool
    {
        if ($this->isSignedChallenge($credentials)) {
            return $this->validateWebAuthn($user, $credentials);
        }

        // If the user disabled the fallback, we will validate the credential password.
        return $user->preferences['useWebauthnOnly'] == false && EloquentUserProvider::validateCredentials($user, $credentials);
    }
}
