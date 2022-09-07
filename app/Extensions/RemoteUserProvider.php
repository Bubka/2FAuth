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
    // 2FAuth is single user by design and domain data are not coupled to the user model.
    // So the RemoteUserProvider provides a non-persisted user, dynamically instanciated using data
    // from the auth proxy.
    //
    // This way no matter the user data set at proxy level, 2FAuth will always
    // authenticate a request from the proxy and will return domain data without restriction.
    //
    // The downside of this approach is that we have to be sure that no change that needs
    // to be persisted will be made to the user instance afterward (i.e through middlewares).


    /**
     * The currently authenticated user.
     *
     * @var \App\Models\User|null
     */
    protected $user;


    /**
     * Get the In-memory user
     * 
     * @return \App\Models\User
     */
    protected function getInMemoryUser()
    {
        if (is_null($this->user)) {
            $this->user = new User;
            $this->user->name = 'Remote User';
            $this->user->email = 'fake.email@do.not.use';
        }
        
        return $this->user;
    }


    /**
     * @inheritDoc
     */
    public function retrieveById($identifier)
    {
        $user = $this->getInMemoryUser();

        if (Arr::has($identifier, 'user')) {
            $user->name = $identifier['user'];
        }
        if (Arr::has($identifier, 'email')) {
            $user->email = $identifier['email'];
        }

        return $user;
    }

    /**
     * @inheritDoc
     * 
     * @codeCoverageIgnore
     */
    public function retrieveByToken($identifier, $token)
    {
        return $this->retrieveById($identifier);
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
        return $this->getInMemoryUser();
    }

    /**
     * @inheritDoc
     * 
     * @codeCoverageIgnore
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }
}