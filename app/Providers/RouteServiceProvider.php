<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('settingName', '[a-zA-Z]+');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiVersionOneRoutes();

        // $this->mapApiVersionTwoRoutes();
        
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "v1 api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiVersionOneRoutes()
    {
        Route::prefix('api/v1')
             ->middleware('api.v1')
             ->namespace($this->getApiNamespace(1))
             ->group(base_path('routes/api/v1.php'));
    }

    /**
     * Define the "v2 api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    // protected function mapApiVersionTwoRoutes()
    // {
    //     Route::prefix('api/v2')
    //          ->middleware('api.v2')
    //          ->namespace($this->getApiNamespace(2))
    //          ->group(base_path('routes/api/v2.php'));
    // }

    /**
     * Build Api namespace based on provided version
     *
     * @return string The Api namespace
     */
    private function getApiNamespace($version)
    {
        return 'App\Api\v' . $version . '\Controllers';
    }
}
