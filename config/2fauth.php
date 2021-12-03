<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 2FAuth config
    |--------------------------------------------------------------------------
    |
    */

    'config' => [
        'isDemoApp' => env('IS_DEMO_APP', false),
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
    ],

];