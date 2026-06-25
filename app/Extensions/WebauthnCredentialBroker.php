<?php

namespace App\Extensions;

use App\Models\WebAuthnAuthenticatable;
use Closure;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Log;

class WebauthnCredentialBroker extends PasswordBroker
{
    /**
     * Send a password reset link to a user.
     */
    public function sendResetLink(#[\SensitiveParameter] array $credentials, ?Closure $callback = null) : string
    {
        return $this->timebox->call(function () use ($credentials, $callback) {
            $user = $this->getUser($credentials);

            if (is_null($user) || ! $user instanceof WebAuthnAuthenticatable) {
                return static::INVALID_USER;
            }

            if ($this->tokens->recentlyCreatedToken($user)) {
                return static::RESET_THROTTLED;
            }

            $token = $this->tokens->create($user);

            if ($callback) {
                $callback($user, $token); // @codeCoverageIgnore
            } else {
                $user->sendWebauthnRecoveryNotification($token);
            }

            Log::notice(sprintf('Webauthn recovery email sent to user ID #%s', $user->id)); /** @phpstan-ignore-line */

            return static::RESET_LINK_SENT;
        }, $this->timeboxDuration);
    }

    /**
     * Reset the password for the given token.
     *
     * @return CanResetPassword|string
     */
    public function reset(#[\SensitiveParameter] array $credentials, Closure $callback)
    {
        $user = $this->validateReset($credentials);

        if (! $user instanceof CanResetPasswordContract || ! $user instanceof WebAuthnAuthenticatable) {
            return $user;
        }

        $callback($user);

        $this->tokens->delete($user);

        return static::PASSWORD_RESET;
    }
}
