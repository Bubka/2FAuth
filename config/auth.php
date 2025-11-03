<?php

return [

    'throttle' => [
        'login' => env('LOGIN_THROTTLE', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', env('AUTHENTICATION_GUARD', 'web-guard')),
        'passwords' => 'users',
        // 'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Proxy Headers
    |--------------------------------------------------------------------------
    |
    | When using a reverse proxy for authentication this option controls the
    | default name of the headers sent by the proxy.
    |
    */

    'auth_proxy_headers' => [
        'user' => env('AUTH_PROXY_HEADER_FOR_USER', 'REMOTE_USER'),
        'email' => env('AUTH_PROXY_HEADER_FOR_EMAIL', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web-guard' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api-guard' => [
            'driver' => 'passport',
            'provider' => 'users',
            'hash' => false,
        ],

        'reverse-proxy-guard' => [
            'driver'   => 'reverse-proxy',
            'provider' => 'remote-user',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent-webauthn',
            'model' => App\Models\User::class,
            // 'model' => env('AUTH_MODEL', App\Models\User::class),
            // 'password_fallback' => true,
        ],
        'remote-user' => [
            'driver' => 'remote-user',
            'model'  => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            // 'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_resets'),
            'expire' => 60,
            'throttle' => 60,
        ],

        // for WebAuthn
        'webauthn' => [
            'provider' => 'users', // The user provider using WebAuthn.
            'table' => 'webauthn_recoveries', // The table to store the recoveries.
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
