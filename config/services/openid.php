<?php

return [
    'token_url' => env('OPENID_TOKEN_URL'),
    'authorize_url' => env('OPENID_AUTHORIZE_URL'),
    'userinfo_url' => env('OPENID_USERINFO_URL'),

    'client_id' => env('OPENID_CLIENT_ID'),
    'client_secret' => env('OPENID_CLIENT_SECRET'),
    'redirect' => '/socialite/callback/openid',
];
