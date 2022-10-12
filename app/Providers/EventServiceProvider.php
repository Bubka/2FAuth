<?php

namespace App\Providers;

use App\Events\GroupDeleting;
use App\Events\TwoFAccountDeleted;
use App\Events\ScanForNewReleaseCalled;
use App\Listeners\ReleaseRadar;
use App\Listeners\CleanIconStorage;
use App\Listeners\DissociateTwofaccountFromGroup;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
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
        ScanForNewReleaseCalled::class => [
            ReleaseRadar::class,
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
}
