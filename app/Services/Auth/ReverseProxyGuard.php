<?php

// Largely inspired by Firefly III remote user implementation (https://github.com/firefly-iii)
// See https://github.com/firefly-iii/firefly-iii/blob/main/app/Support/Authentication/RemoteUserGuard.php

namespace App\Services\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;

class ReverseProxyGuard implements Guard
{
    use GuardHelpers;

    /**
     * The currently authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * Create a new authentication guard.
     *
     * @return void
     */
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        // Get the user identifier from $_SERVER or apache filtered headers
        $remoteUserHeader = config('auth.auth_proxy_headers.user');
        $remoteUserHeader = $remoteUserHeader ?: 'REMOTE_USER';
        $identifier       = [];

        try {
            $identifier['id'] = request()->server($remoteUserHeader) ?? apache_request_headers()[$remoteUserHeader] ?? null;
        } catch (\Throwable $e) {
            $identifier['id'] = null;
        }

        if (! $identifier['id'] || is_array($identifier['id'])) {
            Log::error(sprintf('Proxy remote-user header %s is empty or missing.', var_export($remoteUserHeader, true)));

            return $this->user = null;
        }

        // Get the email identifier from $_SERVER
        $remoteEmailHeader   = config('auth.auth_proxy_headers.email');
        $identifier['email'] = null;

        if ($remoteEmailHeader) {
            try {
                $remoteEmail = (string) (request()->server($remoteEmailHeader) ?? apache_request_headers()[$remoteEmailHeader] ?? null);
            } catch (\Throwable $e) {
                $remoteEmail = null;
            }

            if ($remoteEmail) {
                $identifier['email'] = $remoteEmail;
            }
        }

        return $this->user = $this->provider->retrieveById($identifier);
    }

    /**
     * Validate a user's credentials.
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function validate(array $credentials = [])
    {
        return $this->check();
    }
}
