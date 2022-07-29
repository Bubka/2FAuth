<?php

namespace App\Providers;

use App\Services\LogoService;
use App\Services\QrCodeService;
use App\Services\SettingService;
use App\Services\GroupService;
use App\Services\TwoFAccountService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class TwoFAuthServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SettingService::class, function () {
            return new SettingService();
        });

        $this->app->singleton(GroupService::class, function ($app) {
            return new GroupService($app->make(SettingService::class));
        });

        $this->app->singleton(LogoService::class, function () {
            return new LogoService();
        });

        $this->app->singleton(QrCodeService::class, function () {
            return new QrCodeService();
        });

        $this->app->singleton(TwoFAccountService::class, function () {
            return new TwoFAccountService();
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
            GroupService::class,
            LogoService::class,
            QrCodeService::class,
            TwoFAccountService::class,
        ];
    }
}
