<?php

namespace App\Providers;

use App\Api\v1\Controllers\ImportController;
use App\Contracts\MigrationService;
use App\Services\Migrators\GoogleAuthMigrator;
use App\Services\Migrators\AegisMigrator;
use App\Services\Migrators\PlainTextMigrator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class MigrationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {            
        $this->app->when(ImportController::class)
            ->needs(MigrationService::class)
            ->give(function () {
                switch (request()->route()->getName()) {
                    case 'import.googleAuth':
                        return $this->app->get(GoogleAuthMigrator::class);

                    case 'import.aegis':
                        return $this->app->get(AegisMigrator::class);
                    
                    default:
                        return $this->app->get(PlainTextMigrator::class);
                }
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            GoogleAuthMigrator::class,
            AegisMigrator::class,
            PlainTextMigrator::class,
        ];
    }
}
