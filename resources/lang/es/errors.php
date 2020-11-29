<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'resource_not_found' => 'Recurso no encontrado',
    'error_occured' => 'Se ha producido un error:',
    'already_one_user_registered' => 'Ya hay un usuario registrado con este id.',
    'cannot_register_more_user' => 'No puedes registrar más de un usuario.',
    'refresh' => 'Actualizar',
    'no_valid_otp' => 'No hay un recurso OTP válido en este código QR',
    'something_wrong_with_server' => 'Algo va mal con tu servidor',
    'Unable_to_decrypt_uri' => 'No se puede descifrar uri',
    'not_a_supported_otp_type' => 'Este formato OTP no está soportado actualmente',
    'cannot_create_otp_without_secret' => 'No se puede crear un OTP sin un secreto',
    'cannot_create_otp_with_those_parameters' => 'No se puede crear un OTP con estos parámetros',
    'wrong_current_password' => 'Contraseña actual incorrecta, no ha cambiado nada',
    'error_during_encryption' => 'El cifrado falló, la base de datos permanece sin protección.',
    'error_during_decryption' => 'El descifrado falló, su base de datos todavía está protegida. Esto se debe principalmente a un problema de integridad de datos cifrados para una o más cuentas.',
    'qrcode_cannot_be_read' => 'Este código QR no se puede leer',
];