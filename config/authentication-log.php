<?php

return [
    // The database table name
    // You can change this if the database keys get too long for your driver
    'table_name' => 'authentication_log',

    // The database connection where the authentication_log table resides. Leave empty to use the default
    'db_connection' => null,

    // The events the package listens for to log
    'events' => [
        'login' => \Illuminate\Auth\Events\Login::class,
        'failed' => \Illuminate\Auth\Events\Failed::class,
        'logout' => \Illuminate\Auth\Events\Logout::class,
        'logout-other-devices' => \Illuminate\Auth\Events\OtherDeviceLogout::class,
    ],

    'listeners' => [
        'login' => \Rappasoft\LaravelAuthenticationLog\Listeners\LoginListener::class,
        'failed' => \Rappasoft\LaravelAuthenticationLog\Listeners\FailedLoginListener::class,
        'logout' => \Rappasoft\LaravelAuthenticationLog\Listeners\LogoutListener::class,
        'logout-other-devices' => \Rappasoft\LaravelAuthenticationLog\Listeners\OtherDeviceLogoutListener::class,
    ],

    'notifications' => [
        'new-device' => [
            // Send the NewDevice notification
            'enabled' => env('NEW_DEVICE_NOTIFICATION', true),

            // Use torann/geoip to attempt to get a location
            'location' => false,

            // The Notification class to send
            'template' => \App\Notifications\SignedInWithNewDevice::class,
        ],
        'failed-login' => [
            // Send the FailedLogin notification
            'enabled' => env('FAILED_LOGIN_NOTIFICATION', false),

            // Use torann/geoip to attempt to get a location
            'location' => false,

            // The Notification class to send
            'template' => \Rappasoft\LaravelAuthenticationLog\Notifications\FailedLogin::class,
        ],
    ],

    // When the clean-up command is run, delete old logs greater than `purge` days
    // Don't schedule the clean-up command if you want to keep logs forever.
    'purge' => 365,

    // If you are behind an CDN proxy, set 'behind_cdn.http_header_field' to the corresponding http header field of your cdn
    // For cloudflare you can have look at: https://developers.cloudflare.com/fundamentals/get-started/reference/http-request-headers/
//    'behind_cdn' => [
//        'http_header_field' => 'HTTP_CF_CONNECTING_IP' // used by Cloudflare
//    ],

    // If you are not a cdn user, use false
    'behind_cdn' => false,
];
