<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

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
