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
    'refresh' => 'Actualizar',
    'no_valid_otp' => 'No hay un recurso OTP válido en este código QR',
    'something_wrong_with_server' => 'Algo va mal con su servidor',
    'Unable_to_decrypt_uri' => 'No se puede descifrar uri',
    'not_a_supported_otp_type' => 'Este formato OTP no está soportado actualmente',
    'cannot_create_otp_without_secret' => 'No se puede crear un OTP sin una clave secreta',
    'data_of_qrcode_is_not_valid_URI' => 'Los datos de este código QR no son una URI OTP de Autenticación. El código QR contiene:',
    'wrong_current_password' => 'Contraseña actual incorrecta, no ha cambiado nada',
    'error_during_encryption' => 'El cifrado falló, la base de datos permanece sin protección.',
    'error_during_decryption' => 'El descifrado falló, su base de datos todavía está protegida. Esto se debe, principalmente, a un problema de integridad de datos cifrados para una o más cuentas.',
    'qrcode_cannot_be_read' => 'Este código QR no se puede leer',
    'too_many_ids' => 'demasiado ids fueron incluidos en los parámetros de consulta, máx. 100 permitidos',
    'delete_user_setting_only' => 'Sólo los ajustes creados por el usuario pueden ser eliminados',
    'indecipherable' => '*indescifrable*',
    'cannot_decipher_secret' => 'The clave secreta no puede ser descifrada. Esto es, principalmente, causado por una APP_KEY inválida en el archivo de configuración .env de 2FAuth, o datos corruptos almacenados en la base de datos.',
    'https_required' => 'Contexto HTTPS requerido',
    'browser_does_not_support_webauthn' => 'Su dispositivo no soporta WebAuthn. Intente de nuevo más tarde en un navegador más moderno',
    'aborted_by_user' => 'Abortado por el usuario',
    'security_device_unsupported' => 'Unsupported or in use device',
    'not_allowed_operation' => 'Operation not allowed',
    'unsupported_operation' => 'Unsupported operation',
    'unknown_error' => 'Unknown error',
    'security_error_check_rpid' => 'Security error<br/>Check your WEBAUTHN_ID env var',
    'unsupported_with_reverseproxy' => 'No aplicable cuando se utiliza un proxy de autenticación',
    'user_deletion_failed' => 'Error al borrar la cuenta de usuario, no se han eliminado datos',
    'auth_proxy_failed' => 'La autenticación proxy falló',
    'auth_proxy_failed_legend' => '2FAuth está configurado para ejecutarse detrás de un proxy de autenticación, pero, su proxy no devuelve el encabezado esperado. Compruebe su configuración e intente de nuevo.',
    'invalid_x_migration' => 'Inválido o ilegible: datos de ',
    'invalid_2fa_data' => 'Datos 2FA inválidos',
    'unsupported_migration' => 'Los datos no coinciden con ningún formato soportado',
    'unsupported_otp_type' => 'Tipo de OTP no soportada',
    'encrypted_migration' => 'Ilegíble, los datos parecen estar encriptados',
    'no_logo_found_for_x' => 'Logo no disponible para {service}',
    'file_upload_failed' => 'Fallo al subir el archivo',
    'unauthorized' => 'Unauthorized',
    'unauthorized_legend' => 'You do not have permissions to view this resource or to perform this action',
    'cannot_delete_the_only_admin' => 'Cannot delete the only admin account'
];