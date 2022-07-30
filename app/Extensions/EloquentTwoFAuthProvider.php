<?php

namespace App\Extensions;

use DarkGhostHunter\Larapass\Auth\EloquentWebAuthnProvider;
use DarkGhostHunter\Larapass\WebAuthn\WebAuthnAssertValidator;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use App\Facades\Settings;

class EloquentTwoFAuthProvider extends EloquentWebAuthnProvider
{
    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \DarkGhostHunter\Larapass\WebAuthn\WebAuthnAssertValidator  $validator
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     */
    public function __construct(
        ConfigContract $config,
        WebAuthnAssertValidator $validator,
        HasherContract $hasher,
        string $model
    ) {
        parent::__construct($config, $validator, $hasher, $model);

        $this->fallback = !Settings::get('useWebauthnOnly');
    }
}
