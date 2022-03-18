<?php

namespace App\Services\Auth;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;

class ReverseProxyGuard implements Guard
{
    /**
     * The currently authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * The user provider implementation.
     *
     * @var \Illuminate\Contracts\Auth\UserProvider
     */
    protected $provider;

    /**
     * Create a new authentication guard.
     *
     * @param Illuminate\Contracts\Auth\UserProvider $provider
     * @return void
     */
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        return !is_null($this->user());
    }

    /**
     * @inheritDoc
     */
    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        // Get the user identifier from $_SERVER or apache filtered headers
        $header = config('auth.guard_header', 'REMOTE_USER');
        $userID = request()->server($header) ?? apache_request_headers()[$header] ?? null;

        if (null === $userID) {
            Log::error(sprintf('No user in header "%s".', $header));
            return $this->user = null;
            // throw new Exception('The guard header was unexpectedly empty. See the logs.');
        }

        $user = $this->provider->retrieveById($userID);

        return $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function id()
    {
        return $this->user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return Exception
     */
    public function validate(array $credentials = [])
    {
        throw new Exception('No implementation for RemoteUserGuard::validate()');
    }

    /**
     * @inheritDoc
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}
