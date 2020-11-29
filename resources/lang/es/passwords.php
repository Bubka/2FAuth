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
    'reset' => '¡Tu contraseña ha sido restablecida!',
    'sent' => '¡Te hemos enviado por correo el enlace para restablecer tu contraseña!',
    'throttled' => 'Por favor espera antes de intentar de nuevo.',
    'token' => 'El token de recuperación de contraseña es inválido.',
    'user' => "No podemos encontrar ningún usuario con ese correo electrónico.",

    // 2FAuth
    'password' => 'La contraseña debe tener al menos ocho caracteres y coincidir con la confirmación de contraseña.',
    
];
