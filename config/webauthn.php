<?php

return [

    'user_verification' => envUnlessEmpty('WEBAUTHN_USER_VERIFICATION', 'preferred'),

    /*
    |--------------------------------------------------------------------------
    | Relaying Party
    |--------------------------------------------------------------------------
    |
    | We will use your application information to inform the device who is the
    | relying party. While only the name is enough, you can further set
    | a custom domain as ID and even an icon image data encoded as BASE64.
    |
    */

    'relying_party' => [
        'name' => envUnlessEmpty('WEBAUTHN_NAME', envUnlessEmpty('APP_NAME', '2FAuth')),
        'id'   => envUnlessEmpty('WEBAUTHN_ID', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Challenge configuration
    |--------------------------------------------------------------------------
    |
    | When making challenges your application needs to push at least 16 bytes
    | of randomness. Since we need to later check them, we'll also store the
    | bytes for a small amount of time inside this current request session.
    |
    */

    'challenge' => [
        'bytes' => 16,
        'timeout' => 60,
        'key' => '_webauthn',
    ],
];
