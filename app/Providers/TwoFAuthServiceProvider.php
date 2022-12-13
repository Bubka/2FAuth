<?php

namespace App\Providers;

use App\Factories\MigratorFactoryInterface;
use App\Services\LogoService;
use App\Services\ReleaseRadarService;
use App\Services\SettingService;
use App\Services\TwoFAccountService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TwoFAuthServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TwoFAccountService::class, function ($app) {
            return new TwoFAccountService($app->make(MigratorFactoryInterface::class));
        });

        $this->app->singleton(SettingService::class, function () {
            return new SettingService();
        });

        $this->app->singleton(LogoService::class, function () {
            return new LogoService();
        });

        $this->app->singleton(ReleaseRadarService::class, function () {
            return new ReleaseRadarService();
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
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function provides()
    {
        return [
            LogoService::class,
            ReleaseRadarService::class,
        ];
    }
}
