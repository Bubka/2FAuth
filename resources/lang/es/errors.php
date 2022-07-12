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
    'cannot_register_more_user' => 'No puede registrar más de un usuario.',
    'refresh' => 'Actualizar',
    'no_valid_otp' => 'No hay un recurso OTP válido en este código QR',
    'something_wrong_with_server' => 'Algo va mal con su servidor',
    'Unable_to_decrypt_uri' => 'No se puede descifrar uri',
    'not_a_supported_otp_type' => 'Este formato OTP no está soportado actualmente',
    'cannot_create_otp_without_secret' => 'No se puede crear un OTP sin una clave secreta',
    'data_of_qrcode_is_not_valid_URI' => 'Los datos de este código QR no son una URI OTP de autenticación válida:',
    'wrong_current_password' => 'Contraseña actual incorrecta, no ha cambiado nada',
    'error_during_encryption' => 'El cifrado falló, la base de datos permanece sin protección.',
    'error_during_decryption' => 'El descifrado falló, su base de datos todavía está protegida. Esto se debe, principalmente, a un problema de integridad de datos cifrados para una o más cuentas.',
    'qrcode_cannot_be_read' => 'Este código QR no se puede leer',
    'too_many_ids' => 'demasiado ids fueron incluidos en los parámetros de consulta, máx. 100 permitidos',
    'delete_user_setting_only' => 'Sólo los ajustes creados por el usuario pueden ser eliminados',
    'indecipherable' => '*indescifrable*',
    'cannot_decipher_secret' => 'The clave secreta no puede ser descifrada. Esto es, principalmente, causado por una APP_KEY inválida en el archivo de configuración .env de 2FAuth, o datos corruptos almacenados en la base de datos.',
    'https_required' => 'Requerido HTTPS Contexto',
    'browser_does_not_support_webauthn' => 'Su dispositivo no soporta WebAuthn. Intente de nuevo más tarde en un navegador más moderno',
    'aborted_by_user' => 'Abortado por el usuario',
    'security_device_unsupported' => 'Dispositivo de seguridad no soportado',
    'unsupported_with_reverseproxy' => 'No aplicable cuando se utiliza un proxy de autenticación',
    'user_deletion_failed' => 'Error al borrar la cuenta de usuario, no se han eliminado datos',
    'auth_proxy_failed' => 'La autenticación proxy falló',
    'auth_proxy_failed_legend' => '2FAuth está configurado para ejecutarse detrás de un proxy de autenticación, pero, su proxy no devuelve el encabezado esperado. Compruebe su configuración e intente de nuevo.',
    'invalid_google_auth_migration' => 'Invalid or unreadable Google Authenticator data',
    'unsupported_otp_type' => 'Unsupported OTP type',
];