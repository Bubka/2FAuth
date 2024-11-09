<?php

namespace App\Providers;

use App\Factories\MigratorFactoryInterface;
use App\Services\IconService;
use App\Services\IconStoreService;
use App\Services\LogoService;
use App\Services\ReleaseRadarService;
use App\Services\SettingService;
use App\Services\TwoFAccountService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Zxing\QrReader;

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
            return new SettingService;
        });

        $this->app->singleton(IconStoreService::class, function () {
            return new IconStoreService;
        });

        $this->app->singleton(LogoService::class, function ($app) {
            return new LogoService;
        });

        $this->app->singleton(IconService::class, function ($app) {
            return new IconService;
        });

        $this->app->singleton(ReleaseRadarService::class, function () {
            return new ReleaseRadarService;
        });

        $this->app->bind(QrReader::class, function ($app, array $parameters) {
            return new QrReader($parameters['imgSource'], $parameters['sourceType']);
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
            IconService::class,
            IconStoreService::class,
            LogoService::class,
            QrReader::class,
            ReleaseRadarService::class,
        ];
    }
}
