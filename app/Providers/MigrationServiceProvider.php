<?php

namespace App\Providers;

use App\Factories\MigratorFactory;
use App\Factories\MigratorFactoryInterface;
use App\Services\Migrators\AegisMigrator;
use App\Services\Migrators\GoogleAuthMigrator;
use App\Services\Migrators\PlainTextMigrator;
use App\Services\Migrators\TwoFASMigrator;
use Illuminate\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MigratorFactoryInterface::class, MigratorFactory::class);

        $this->app->singleton(GoogleAuthMigrator::class, function () {
            return new GoogleAuthMigrator();
        });

        $this->app->singleton(AegisMigrator::class, function () {
            return new AegisMigrator();
        });

        $this->app->singleton(TwoFASMigrator::class, function () {
            return new TwoFASMigrator();
        });

        $this->app->singleton(PlainTextMigrator::class, function () {
            return new PlainTextMigrator();
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
}
