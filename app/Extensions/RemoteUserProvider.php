<?php

// Largely inspired by Firefly III remote user implementation (https://github.com/firefly-iii)
// see https://github.com/firefly-iii/firefly-iii/blob/main/app/Support/Authentication/RemoteUserProvider.php

namespace App\Extensions;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RemoteUserProvider implements UserProvider
{
    const FAKE_REMOTE_DOMAIN = '@remote';

    /**
     * The currently authenticated user.
     *
     * @var \App\Models\User|null
     */
    protected $user;

    /**
     * {@inheritDoc}
     */
    public function retrieveById($identifier)
    {
        // We don't know the id length so we trim it to prevent to long strings in DB
        $name  = substr($identifier['id'], 0, 191);
        $email = null;

        $user = User::where('name', $name)->first();

        // We use the passed email only if it is valid and no account is using it.
        if ($identifier['email']) {
            try {
                $validated = Validator::validate([
                    'email' => $identifier['email'],
                ], [
                    'email' => 'email',
                ]);

                if (User::where('id', '<>', $user->id ?? 0)->where('email', $identifier['email'])->count() == 0) {
                    $email = strtolower($identifier['email']);
                }
            } catch (ValidationException $e) {
                // do nothing
            }
        }

        if (is_null($user)) {
            $email = $email ?? $this->fakeRemoteEmail($identifier['id']);

            $user = User::create([
                'name'     => $name,
                'email'    => strtolower($email),
                'password' => Hash::make(Str::random(64)),
            ]);

            Log::info(sprintf('Remote user %s created with email address %s', var_export($user->name, true), var_export($user->email, true)));

            if (User::count() === 1) {
                $user->promoteToAdministrator();
                $user->save();
            }
        } else {
            // Here we keep the account's email sync-ed
            if ($email && $user->email != $email) {
                $user->email = $email;
                $user->save();
            }
        }

        return $user;
    }

    /**
     * Set a fake email address
     *
     * @param  $id  mixed
     * @return string
     */
    protected function fakeRemoteEmail(mixed $id)
    {
        return substr($id, 0, 184) . self::FAKE_REMOTE_DOMAIN;
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function retrieveByToken($identifier, $token)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function retrieveByCredentials(array $credentials)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }
}
