<?php

// Part of Firefly III (https://github.com/firefly-iii)
// see https://github.com/firefly-iii/firefly-iii/blob/main/app/Support/Authentication/RemoteUserProvider.php

namespace App\Extensions;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;
use Exception;

class RemoteUserProvider implements UserProvider
{
    /**
     * @inheritDoc
     */
    public function retrieveById($identifier): User
    {
        $user = User::where('email', $identifier)->first();

        // if (null === $user) {
        //     $user = User::create(
        //         [
        //             'name' => $identifier,
        //             'email' => $identifier,
        //             'password' => bcrypt(Str::random(64)),
        //         ]
        //     );
        // }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function retrieveByToken($identifier, $token)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * @inheritDoc
     */
    public function retrieveByCredentials(array $credentials)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * @inheritDoc
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }
}