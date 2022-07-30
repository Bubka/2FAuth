<?php

namespace App\Providers;

use App\Services\LogoService;
use App\Services\SettingService;
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

        $this->app->singleton(LogoService::class, function () {
            return new LogoService();
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
            LogoService::class,
        ];
    }
}
