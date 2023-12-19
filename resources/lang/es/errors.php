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
    'security_device_already_registered' => 'Device already registered',
    'not_allowed_operation' => 'Operacion no permitida',
    'no_authenticator_support_specified_algorithms' => 'No authenticators support specified algorithms',
    'authenticator_missing_discoverable_credential_support' => 'Authenticator missing discoverable credential support',
    'authenticator_missing_user_verification_support' => 'Authenticator missing user verification support',
    'unknown_error' => 'Error desconocido',
    'security_error_check_rpid' => 'Error de seguridad<br/>Compruebe su variable de entorno WEBAUTHN_ID',
    '2fauth_has_not_a_valid_domain' => '2FAuth\'s domain is not a valid domain',
    'user_id_not_between_1_64' => 'User ID was not between 1 and 64 chars',
    'no_entry_was_of_type_public_key' => 'No entry was of type "public-key"',
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
    'unauthorized' => 'No autorizado',
    'unauthorized_legend' => 'No tiene permisos para ver este recurso o para realizar esta acción',
    'cannot_delete_the_only_admin' => 'No se puede eliminar la única cuenta de administrador',
    'error_during_data_fetching' => '💀 Something went wrong during data fetching',
    'check_failed_try_later' => 'Check failed, please retry later',
    'sso_disabled' => 'SSO is disabled',
    'sso_bad_provider_setup' => 'This SSO provider is not fully setup in your .env file',
    'sso_failed' => 'Authentication via SSO rejected',
    'sso_no_register' => 'Registrations are disabled',
    'sso_email_already_used' => 'A user account with the same email address already exists but it does not match your external account ID. Do not use SSO if you are already registered on 2FAuth with this email.',
    'account_managed_by_external_provider' => 'Account managed by an external provider',
];