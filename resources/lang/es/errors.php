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
    'security_device_already_registered' => 'Dispositivo ya registrado',
    'not_allowed_operation' => 'Operacion no permitida',
    'no_authenticator_support_specified_algorithms' => 'No existen autentificadores que soporten los algoritmos especificados',
    'authenticator_missing_discoverable_credential_support' => 'El autentificador no cuenta con soporte para credenciales detectables',
    'authenticator_missing_user_verification_support' => 'El autentificador no cuenta con soporte para la verificación de usuario',
    'unknown_error' => 'Error desconocido',
    'security_error_check_rpid' => 'Error de seguridad<br/>Compruebe su variable de entorno WEBAUTHN_ID',
    '2fauth_has_not_a_valid_domain' => 'El dominio de 2FAuth\'s no es un dominio válido',
    'user_id_not_between_1_64' => 'El ID de usuario no está entre 1 y 64 caracteres',
    'no_entry_was_of_type_public_key' => 'Ninguna entrada es del tipo "clave pública"',
    'unsupported_with_reverseproxy' => 'Not applicable when using an auth proxy or SSO',
    'unsupported_with_sso_only' => 'This authentication method is for administrators only. Users must log in with SSO.',
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
    'unauthorized' => 'No autorizado',
    'unauthorized_legend' => 'No tiene permisos para ver este recurso o para realizar esta acción',
    'cannot_delete_the_only_admin' => 'No se puede eliminar la única cuenta de administrador',
    'cannot_demote_the_only_admin' => 'Cannot demote the only admin account',
    'error_during_data_fetching' => '💀 Algo salió mal durante la obtención de datos',
    'check_failed_try_later' => 'Comprobación fallida, por favor inténtelo más tarde',
    'sso_disabled' => 'SSO está desactivado',
    'sso_bad_provider_setup' => 'Este proveedor SSO no está completamente configurado en su archivo .env',
    'sso_failed' => 'Autenticación mediante SSO rechazada',
    'sso_no_register' => 'Los registros están deshabilitados',
    'sso_email_already_used' => 'Ya existe una cuenta de usuario con la misma dirección de correo electrónico, pero no coincide con su ID de cuenta externa. No utilice SSO si ya se encuentra registrado en 2FAuth con este correo electrónico.',
    'account_managed_by_external_provider' => 'Cuenta administrada por un proveedor externo',
    'data_cannot_be_refreshed_from_server' => 'Los datos no se pueden actualizar desde el servidor',
    'no_pwd_reset_for_this_user_type' => 'Password reset unavailable for this user',
    'cannot_detect_qrcode_in_image' => 'Cannot detect a QR code in the image, try to crop the image',
    'cannot_decode_detected_qrcode' => 'Cannot decode detected QR code, try to crop or sharpen the image',
    'qrcode_has_invalid_checksum' => 'QR code has invalid checksum',
    'no_readable_qrcode' => 'No readable QR code',
];