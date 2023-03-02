<?php

namespace App\Extensions;

use Illuminate\Auth\EloquentUserProvider;
use App\Models\WebAuthnAuthenticatable;
use Laragear\WebAuthn\Auth\WebAuthnUserProvider;

class WebauthnTwoFAuthUserProvider extends WebAuthnUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|\App\Models\WebAuthnAuthenticatable|\App\Models\User  $user
     * @param  array  $credentials
     *
     * @return bool
     */
    public function validateCredentials($user, array $credentials): bool
    {
        if ($user instanceof WebAuthnAuthenticatable && $this->isSignedChallenge($credentials)) {
            return $this->validateWebAuthn();
        }

        // If the user disabled the fallback is enabled, we will validate the credential password.
        return $user->preferences['useWebauthnOnly'] == false && EloquentUserProvider::validateCredentials($user, $credentials);
    }
}
