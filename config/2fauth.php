<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application infos
    |--------------------------------------------------------------------------
    |
    */

    'version' => '5.2.0',
    'repository' => 'https://github.com/Bubka/2FAuth',
    'latestReleaseUrl' => 'https://api.github.com/repos/Bubka/2FAuth/releases/latest',
    'installDocUrl' => 'https://docs.2fauth.app/getting-started/installation/self-hosted-server/',

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
        'outgoingProxy' => env('PROXY_FOR_OUTGOING_REQUESTS', ''),
        'proxyLogoutUrl' => env('PROXY_LOGOUT_URL', null),
        'appSubdirectory' => env('APP_SUBDIRECTORY', ''),
        'authLogRetentionTime' => envUnlessEmpty('AUTHENTICATION_LOG_RETENTION', 365),
    ],

    /*
    |--------------------------------------------------------------------------
    | Proxy headers
    |--------------------------------------------------------------------------
    |
    */
    
    'proxy_headers' => [
        'forIp' => env('PROXY_HEADER_FOR_IP', null),
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
        'ru',
        'ja',
        'hi',
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
        'disableRegistration' => false,
        'enableSso' => true,
        'restrictRegistration' => false,
        'keepSsoRegistrationEnabled' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default values for user preferences
    | These settings can be overloaded and persisted by each user
    |--------------------------------------------------------------------------
    |
    */

    'preferences' => [
        'showOtpAsDot' => false,
        'revealDottedOTP' => false,
        'closeOtpOnCopy' => false,
        'copyOtpOnDisplay' => false,
        'clearSearchOnCopy' => false,
        'useBasicQrcodeReader' => false,
        'displayMode' => 'list',
        'showAccountsIcons' => true,
        'kickUserAfter' => 15,
        'activeGroup' => 0,
        'rememberActiveGroup' => true,
        'viewDefaultGroupOnCopy' => false,
        'defaultGroup' => 0,
        'defaultCaptureMode' => 'livescan',
        'useDirectCapture' => false,
        'useWebauthnOnly' => false,
        'getOfficialIcons' => true,
        'theme' => 'system',
        'formatPassword' => true,
        'formatPasswordBy' => 0.5,
        'lang' => 'browser',
        'getOtpOnRequest' => true,
        'notifyOnNewAuthDevice' => false,
        'notifyOnFailedLogin' => false,
        'timezone' => env('APP_TIMEZONE', 'UTC'),
    ],

];
