<?php

namespace App\Models\Traits;

use App\Notifications\WebauthnRecoveryNotification;
use Illuminate\Database\Eloquent\Collection;

/**
 * @see \App\Models\WebAuthnAuthenticatable
 * @see \Laragear\WebAuthn\Models\WebAuthnCredential
 */
trait WebAuthnManageCredentials
{
    /**
     * Saves a new alias for a given WebAuthn credential.
     */
    public function renameCredential(string $id, string $alias) : bool
    {
        return boolval($this->webAuthnCredentials()->whereKey($id)->update(['alias' => $alias]));
    }

    /**
     * Removes one or more credentials previously registered.
     *
     * @param  string|array  $id
     */
    public function flushCredential($id) : void
    {
        if (! $this->relationLoaded('webAuthnCredentials')) {
            $this->webAuthnCredentials()->whereKey($id)->delete();

            return;
        }

        if ($this->webAuthnCredentials instanceof Collection && $this->webAuthnCredentials->isNotEmpty()) {
            $this->webAuthnCredentials->whereIn('id', $id)->each->delete();

            $this->setRelation('webAuthnCredentials', $this->webAuthnCredentials->whereNotIn('id', $id));
        }
    }

    /**
     * Sends a webauthn recovery email to the user.
     */
    public function sendWebauthnRecoveryNotification(string $token) : void
    {
        // $accountRecoveryNotification = new WebauthnRecoveryNotification($token);
        // $accountRecoveryNotification->toMailUsing(null);

        // $accountRecoveryNotification->createUrlUsing(function(mixed $notifiable, string $token) {
        //     $url = url(
        //         route(
        //             'webauthn.recover',
        //             [
        //                 'token' => $token,
        //                 'email' => $notifiable->getEmailForPasswordReset(),
        //             ],
        //             false
        //         )
        //     );

        //     return $url;
        // });

        $this->notify(new WebauthnRecoveryNotification($token));
    }
}
