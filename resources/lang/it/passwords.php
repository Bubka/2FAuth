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
    'reset' => 'La password è stata reimpostata!',
    'sent' => 'Ti abbiamo inviato una email con il link per il reset della password!',
    'throttled' => 'Per favore, attendi prima di riprovare.',
    'token' => 'Questo token di reset della password non è valido.',
    'user' => "Non riusciamo a trovare un utente con questo indirizzo email.",

    // 2FAuth
    'password' => 'Passwords must be at least eight characters and match the confirmation.',
    
];
