<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\ReverseProxyGuard;
use App\Extensions\EloquentTwoFAuthProvider;
use App\Extensions\RemoteUserProvider;
use DarkGhostHunter\Larapass\WebAuthn\WebAuthnAssertValidator;
use Illuminate\Contracts\Hashing\Hasher;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     */
    // protected $policies = [
    //     'App\Models\Model' => 'App\Policies\ModelPolicy',
    // ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // We use our own user provider derived from the Larapass user provider.
        // The only difference between the 2 providers is that the custom one sets
        // the webauthn fallback setting with 2FAuth's 'useWebauthnOnly' option
        // value instead of the 'larapass.fallback' config value.
        // This way we can offer the user to change this setting from the 2FAuth UI
        // rather than from the .env file.
        Auth::provider(
            'eloquent-2fauth',
            static function ($app, $config) {
                return new EloquentTwoFAuthProvider(
                    $app['config'],
                    $app[WebAuthnAssertValidator::class],
                    $app[Hasher::class],
                    $config['model']
                );
            }
        );

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
