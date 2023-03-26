<?php

namespace App\Models\Traits;

use App\Notifications\WebauthnRecoveryNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * @see \App\Models\WebAuthnAuthenticatable
 * @see \Laragear\WebAuthn\Models\WebAuthnCredential
 */
trait WebAuthnManageCredentials
{
    /**
     * Return the handle used to identify his credentials.
     */
    public function userHandle() : string
    {
        // Laragear\WebAuthn uses Ramsey\Uuid\Uuid::fromString()->getHex()->toString()
        // to obtain a UUID v4 with dashes removed and uses it as user_id (aka userHandle)
        // see https://github.com/ramsey/uuid/blob/4.x/src/Uuid.php#L379
        // and Laragear\WebAuthn\Assertion\Validator\Pipes\CheckCredentialIsForUser::validateId()

        return $this->webAuthnCredentials()->value('user_id')
            ?? str_replace('-', '', Str::uuid()->toString());
    }

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
