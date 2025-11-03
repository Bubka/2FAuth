<?php

namespace App\Providers;

use App\Facades\Settings;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Limited to 191 to prevent index length issue with MyISAM and utf8mb4_unicode_ci
        // when using WAMP (WAMP uses MyISAM as default engine in place of INNOdb)
        Schema::defaultStringLength(191);

        JsonResource::withoutWrapping();

        $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);

        Gate::before(function (User $user, string $ability) {
            if ($user->isAdministrator()) {
                return true;
            }
        });

        Gate::define('manage-pat', function (User $user) {
            $useSsoOnly = Settings::get('useSsoOnly');

            return ($useSsoOnly && Settings::get('allowPatWhileSsoOnly')) || $useSsoOnly !== true;
        });

        Gate::define('manage-webauthn-credentials', function (User $user) {
            return ! Settings::get('useSsoOnly');
        });
    }
}
