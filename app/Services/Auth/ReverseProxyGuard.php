<?php

// Largely inspired by Firefly III remote user implementation (https://github.com/firefly-iii)
// See https://github.com/firefly-iii/firefly-iii/blob/main/app/Support/Authentication/RemoteUserGuard.php

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
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
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
        $remoteUserHeader = config('auth.auth_proxy_headers.user');
        $remoteUserHeader = $remoteUserHeader ?: 'REMOTE_USER';
        $identifier = array();

        try {
            $identifier['user'] = request()->server($remoteUserHeader) ?? apache_request_headers()[$remoteUserHeader] ?? null;
        }
        catch (\Throwable $e) {
            $identifier['user'] = null;
        }

        if (!$identifier['user'] || is_array($identifier['user'])) {
            Log::error(sprintf('Proxy remote-user header "%s" is empty or missing.', $remoteUserHeader));
            return $this->user = null;
        }

        // Get the email identifier from $_SERVER
        $remoteEmailHeader = config('auth.auth_proxy_headers.email');

        if ($remoteEmailHeader) {
            try {
                $remoteEmail = (string)(request()->server($remoteEmailHeader) ?? apache_request_headers()[$remoteEmailHeader] ?? null);
            }
            catch (\Throwable $e) {
                $remoteEmail = null;
            }

            if ($remoteEmail) {
                $identifier['email'] = $remoteEmail;
            }
        }

        $user = $this->provider->retrieveById($identifier);

        return $this->user = $user;
    }

    /**
     * @inheritDoc
     * 
     * @codeCoverageIgnore
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
     * 
     * @codeCoverageIgnore
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
