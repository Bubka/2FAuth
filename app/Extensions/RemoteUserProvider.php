<?php

// Largely inspired by Firefly III remote user implementation (https://github.com/firefly-iii)
// see https://github.com/firefly-iii/firefly-iii/blob/main/app/Support/Authentication/RemoteUserProvider.php

namespace App\Extensions;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Arr;
use Exception;

class RemoteUserProvider implements UserProvider
{
    /**
     * @inheritDoc
     */
    public function retrieveById($identifier)
    {
        // 2FAuth is single user by design and domain data are not coupled to the user model.
        // So we provide a non-persisted user, dynamically instanciated using data
        // from the auth proxy.
        // This way no matter the user account used at proxy level, 2FAuth will always
        // authenticate a request from the proxy and will return domain data without restriction.
        //
        // The downside of this approach is that we have to be sure that no change that needs
        // to be persisted will be made to the user instance afterward (i.e through middlewares).

        $user = new User;
        $user->name = $identifier['user'];
        $user->email = Arr::has($identifier, 'email') ? $identifier['email'] : 'fake.email@do.not.use';

        return $user;
    }

    /**
     * @inheritDoc
     * 
     * @codeCoverageIgnore
     */
    public function retrieveByToken($identifier, $token)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * @inheritDoc
     * 
     * @codeCoverageIgnore
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * @inheritDoc
     * 
     * @codeCoverageIgnore
     */
    public function retrieveByCredentials(array $credentials)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }

    /**
     * @inheritDoc
     * 
     * @codeCoverageIgnore
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        throw new Exception(sprintf('No implementation for %s', __METHOD__));
    }
}