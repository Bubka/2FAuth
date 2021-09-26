<?php

namespace App\Providers;

use App\Services\SettingServiceInterface;
use App\Services\AppstractOptionsService;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class TwoFAuthServiceProvider extends ServiceProvider
{

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register stuff.
     *
     */
    public function register() : void
    {
        $this->app->bind(SettingServiceInterface::class, AppstractOptionsService::class);
    }
}
