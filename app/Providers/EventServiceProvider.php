<?php

namespace App\Providers;

use App\Events\GroupDeleted;
use App\Events\GroupDeleting;
use App\Events\ScanForNewReleaseCalled;
use App\Events\TwoFAccountDeleted;
use App\Listeners\CleanIconStorage;
use App\Listeners\DissociateTwofaccountFromGroup;
use App\Listeners\RegisterOpenId;
use App\Listeners\ReleaseRadar;
use App\Listeners\ResetUsersPreference;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TwoFAccountDeleted::class => [
            CleanIconStorage::class,
        ],
        GroupDeleting::class => [
            DissociateTwofaccountFromGroup::class,
        ],
        GroupDeleted::class => [
            ResetUsersPreference::class,
        ],
        ScanForNewReleaseCalled::class => [
            ReleaseRadar::class,
        ],
        SocialiteWasCalled::class => [
            RegisterOpenId::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents() : bool
    {
        return false;
    }
}
