<?php

namespace App\Models;

use Laragear\WebAuthn\Contracts\WebAuthnAuthenticatable as Authenticatable;

interface WebAuthnAuthenticatable extends Authenticatable
{
    /**
     * Return the handle used to identify his credentials.
     */
    public function userHandle() : string;

    /**
     * Saves a new alias for a given WebAuthn credential.
     */
    public function renameCredential(string $id, string $alias) : bool;

    /**
     * Removes one or more credentials previously registered.
     *
     * @param  string|array  $id
     */
    public function flushCredential($id) : void;

    /**
     * Sends a webauthn recovery email to the user.
     */
    public function sendWebauthnRecoveryNotification(string $token) : void;
}
