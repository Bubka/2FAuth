<?php

namespace App\Http;

use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CustomCreateFreshApiToken;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\ForceLogout;
use App\Http\Middleware\KickOutInactiveUser;
use App\Http\Middleware\LogUserLastSeen;
use App\Http\Middleware\PreventRequestForgery;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RejectIfAllUsersSharingScopeDisabled;
use App\Http\Middleware\RejectIfAuthenticated;
use App\Http\Middleware\RejectIfDemoMode;
use App\Http\Middleware\RejectIfReverseProxy;
use App\Http\Middleware\RejectIfShareDisabled;
use App\Http\Middleware\RejectIfSsoOnlyAndNotForAdmin;
use App\Http\Middleware\SetLanguage;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        TrustProxies::class,
        HandleCors::class,
        PreventRequestsDuringMaintenance::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        ForceJsonResponse::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            PreventRequestForgery::class,
            SubstituteBindings::class,
            SetLanguage::class,
            CustomCreateFreshApiToken::class,
        ],

        'behind-auth' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            // \Illuminate\Session\Middleware\StartSession::class,
            PreventRequestForgery::class,
            SubstituteBindings::class,
            Authenticate::class,
            KickOutInactiveUser::class,
            LogUserLastSeen::class,
            SetLanguage::class,
            CustomCreateFreshApiToken::class,
        ],

        'api.v1' => [
            ThrottleRequests::class . ':api',
            SubstituteBindings::class,
            KickOutInactiveUser::class,
            LogUserLastSeen::class,
            SetLanguage::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth'                                 => Authenticate::class,
        'admin'                                => AdminOnly::class,
        'rejectIfAuthenticated'                => RejectIfAuthenticated::class,
        'throttle'                             => ThrottleRequests::class,
        'rejectIfDemoMode'                     => RejectIfDemoMode::class,
        'rejectIfShareDisabled'                => RejectIfShareDisabled::class,
        'rejectIfAllUsersSharingScopeDisabled' => RejectIfAllUsersSharingScopeDisabled::class,
        'rejectIfReverseProxy'                 => RejectIfReverseProxy::class,
        'rejectIfSsoOnlyAndNotForAdmin'        => RejectIfSsoOnlyAndNotForAdmin::class,
        'cache.headers'                        => SetCacheHeaders::class,
        'kickOutInactiveUser'                  => KickOutInactiveUser::class,
        'forceLogout'                          => ForceLogout::class,
        'setLanguage'                          => SetLanguage::class,
        // 'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        // 'signed' => \App\Http\Middleware\ValidateSignature::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var string[]
     */
    protected $middlewarePriority = [
        StartSession::class,
        ShareErrorsFromSession::class,
        Authenticate::class,
        SetLanguage::class,
        AuthenticateSession::class,
        SubstituteBindings::class,
        Authorize::class,
    ];
}
