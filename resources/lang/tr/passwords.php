<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    // Laravel
    'reset' => 'Şifreniz sıfırlandı!',
    'sent' => 'E-posta adresinize şifre sıfırlama bağlantısı gönderdik!',
    'throttled' => 'Please wait before retrying.',
    'token' => 'This password reset token is invalid.',
    'user' => "We can't find a user with that email address.",

    // 2FAuth
    'password' => 'Passwords must be at least eight characters and match the confirmation.',
    
];
