<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application infos
    |--------------------------------------------------------------------------
    |
    */

    'version' => '4.0.0-beta.1',
    'repository' => 'https://github.com/Bubka/2FAuth',
    'latestReleaseUrl' => 'https://api.github.com/repos/Bubka/2FAuth/releases/latest',

    /*
    |--------------------------------------------------------------------------
    | 2FAuth config
    |--------------------------------------------------------------------------
    |
    */

    'config' => [
        'isDemoApp' => env('IS_DEMO_APP', false),
        'isTestingApp' => env('IS_TESTING_APP', false),
        'trustedProxies' => env('TRUSTED_PROXIES', null),
        'proxyLogoutUrl' => env('PROXY_LOGOUT_URL', null),
        'appSubdirectory' => env('APP_SUBDIRECTORY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | 2FAuth API config
    |--------------------------------------------------------------------------
    |
    */

    'api' => [
        'throttle' => env('THROTTLE_API', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | 2FAuth available translations
    |--------------------------------------------------------------------------
    |
    */

    'locales' => [
        'en',
        'fr',
        'de',
        'zh',
        'es',
        'bg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default values for app (global) settings
    | These settings can be overloaded and persisted using the SettingService
    |--------------------------------------------------------------------------
    |
    */

    'settings' => [
        'useEncryption' => false,
        'checkForUpdate' => true,
        'lastRadarScan' => 0,
        'latestRelease' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default values for user preferences
    | These settings can be overloaded and persisted by each user
    |--------------------------------------------------------------------------
    |
    */

    'preferences' => [
        'showTokenAsDot' => false,
        'closeOtpOnCopy' => false,
        'copyOtpOnDisplay' => false,
        'useBasicQrcodeReader' => false,
        'displayMode' => 'list',
        'showAccountsIcons' => true,
        'kickUserAfter' => 15,
        'activeGroup' => 0,
        'rememberActiveGroup' => true,
        'defaultGroup' => 0,
        'defaultCaptureMode' => 'livescan',
        'useDirectCapture' => false,
        'useWebauthnOnly' => false,
        'getOfficialIcons' => true,
        'theme' => 'system',
        'formatPassword' => true,
        'formatPasswordBy' => 0.5,
        'lang' => 'browser',
    ],

];