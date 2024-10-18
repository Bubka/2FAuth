<?php

namespace App\Providers;

use App\Events\GroupDeleted;
use App\Events\GroupDeleting;
use App\Events\storeIconsInDatabaseSettingChanged;
use App\Events\ScanForNewReleaseCalled;
use App\Events\TwoFAccountDeleted;
use App\Events\VisitedByProxyUser;
use App\Listeners\Authentication\FailedLoginListener;
use App\Listeners\Authentication\LoginListener;
use App\Listeners\Authentication\LogoutListener;
use App\Listeners\Authentication\VisitedByProxyUserListener;
use App\Listeners\CleanIconStorage;
use App\Listeners\DissociateTwofaccountFromGroup;
use App\Listeners\LogNotificationListener;
use App\Listeners\ToggleIconReplicationToDatabase;
use App\Listeners\RegisterOpenId;
use App\Listeners\ReleaseRadar;
use App\Listeners\ResetUsersPreference;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;
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
        NotificationSent::class => [
            LogNotificationListener::class,
        ],
        Login::class => [
            LoginListener::class,
        ],
        Failed::class => [
            FailedLoginListener::class,
        ],
        Logout::class => [
            LogoutListener::class,
        ],
        VisitedByProxyUser::class => [
            VisitedByProxyUserListener::class,
        ],
        storeIconsInDatabaseSettingChanged::class => [
            ToggleIconReplicationToDatabase::class,
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array<string, string|object|array<int, string|object>>
     */
    // TODO: bind the observer using the ObservedBy attribute (https://laravel.com/docs/11.x/eloquent#defining-observers)
    protected $observers = [
        User::class => [UserObserver::class],
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
