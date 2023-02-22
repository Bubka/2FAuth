<?php

namespace App\Providers;

use App\Extensions\RemoteUserProvider;
use App\Extensions\WebauthnCredentialBroker;
use App\Facades\Settings;
use App\Models\TwoFAccount;
use App\Policies\TwoFAccountPolicy;
use App\Services\Auth\ReverseProxyGuard;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RuntimeException;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        TwoFAccount::class => TwoFAccountPolicy::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register() : void
    {
        $this->app->singleton(
            WebauthnCredentialBroker::class,
            static function ($app) {
                if (! $config = $app['config']['auth.passwords.webauthn']) {
                    throw new RuntimeException('You must set the [webauthn] key broker in [auth] config.');
                }

                $key = $app['config']['app.key'];

                if (Str::startsWith($key, 'base64:')) {
                    $key = base64_decode(substr($key, 7));
                }

                return new WebauthnCredentialBroker(
                    new DatabaseTokenRepository(
                        $app['db']->connection($config['connection'] ?? null),
                        $app['hash'],
                        $config['table'],
                        $key,
                        $config['expire'],
                        $config['throttle'] ?? 0
                    ),
                    $app['auth']->createUserProvider($config['provider'] ?? null)
                );
            }
        );
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Register a custom provider for reverse-proxy authentication
        Auth::provider('remote-user', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...

            return new RemoteUserProvider;
        });

        // Register a custom driver for reverse-proxy authentication
        Auth::extend('reverse-proxy', function ($app, string $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...

            return new ReverseProxyGuard(Auth::createUserProvider($config['provider']));
        });

        // Previously we were using a custom user provider derived from the Larapass user provider
        // in order to honor the "useWebauthnOnly" user option.
        // Since Laragear\WebAuthn now replaces DarkGhostHunter\Larapass, the new approach is
        // simplier: We overload the 'eloquent-webauthn' registration from Laragear\WebAuthn\WebAuthnServiceProvider
        // with a custom closure that uses the "useWebauthnOnly" user option
        Auth::provider(
            'eloquent-webauthn',
            static function (\Illuminate\Contracts\Foundation\Application $app, array $config) : \Laragear\WebAuthn\Auth\WebAuthnUserProvider {
                return new \Laragear\WebAuthn\Auth\WebAuthnUserProvider(
                    $app->make('hash'),
                    $config['model'],
                    $app->make(\Laragear\WebAuthn\Assertion\Validator\AssertionValidator::class),
                    Settings::get('useWebauthnOnly') ? false : true
                );
            }
        );

        // Normally we should set the Passport routes here using Passport::routes().
        // If so the passport routes would be set for both 'web' and 'api' middlewares without
        // possibility to exclude the web middleware (we can only pass additional middlewares to Passport::routes())
        //
        // The problem is that 2Fauth front-end uses the Laravel FreshApiToken to consum its API as a first party app.
        // So we have a laravel_token cookie added to each response to perform the authentication.
        //
        // Don't know why but when passing through the web middleware the requests to Personal Access Tokens management routes return
        // responses with inconsistent cookies that make the next request unauthorized.
        // To avoid this the Passport routes for PAT management are set in the /routes/api.php file
    }
}
