<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    */

    'version' => '3.3.3',

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
        'de'
    ],

    /*
    |--------------------------------------------------------------------------
    | Application fallback for user options
    |--------------------------------------------------------------------------
    |
    */

    'options' => [
        'showTokenAsDot' => false,
        'closeOtpOnCopy' => false,
        'useBasicQrcodeReader' => false,
        'displayMode' => 'list',
        'showAccountsIcons' => true,
        'kickUserAfter' => '15',
        'activeGroup' => 0,
        'rememberActiveGroup' => true,
        'defaultGroup' => 0,
        'useEncryption' => false,
        'defaultCaptureMode' => 'livescan',
        'useDirectCapture' => false,
        'useWebauthnAsDefault' => false,
        'useWebauthnOnly' => false,
        'getOfficialIcons' => true,
    ],

];