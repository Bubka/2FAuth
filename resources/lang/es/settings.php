<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'settings' => 'Ajustes',
    'account' => 'Cuenta',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Opciones',
    'user_options' => 'Opciones de usuario',
    'confirm' => [

    ],
    'general' => 'General',
    'security' => 'Seguridad',
    'profile' => 'Perfil',
    'change_password' => 'Cambiar contraseña',
    'personal_access_tokens' => 'Tokens de acceso personal',
    'token_legend' => 'Los Tokens de Acceso Personal, permiten a cualquier aplicación autenticarse con la API de 2FAuth. Debe especificar el token de acceso como Bearer Token en la cabecera de autorización de aplicaciones de tercero.',
    'generate_new_token' => 'Generar nuevo token',
    'revoke' => 'Revocar',
    'token_revoked' => 'Token revocado correctamente',
    'revoking_a_token_is_permanent' => 'Revocar un token es permanente',
    'confirm' => [
        'revoke' => '¿Está seguro que desea revocar este token?',
    ],
    'make_sure_copy_token' => 'Asegúrese de copiar su token de acceso personal ahora. ¡No podrá volver a verlo!',
    'data_input' => 'Introducción de datos',
    'forms' => [
        'edit_settings' => 'Modificar ajustes',
        'setting_saved' => 'Ajustes guardados',
        'new_token' => 'Nuevo token',
        'some_translation_are_missing' => '¿Faltan algunas traducciones utilizando el idioma preferido por el navegador?',
        'help_translate_2fauth' => 'Ayude a traducir 2FAuth',
        'language' => [
            'label' => 'Idioma',
            'help' => 'Idioma utilizado para traducir la interfaz de usuario de 2FAuth. Los idiomas listados están completos, establezca el idioma de su elección para reemplazar las preferencias de su navegador.'
        ],
        'show_otp_as_dot' => [
            'label' => 'Mostrar contraseñas generadas de un solo uso como punto',
            'help' => 'Replace generated password caracters with *** to ensure confidentiality. Do not affect the copy/paste feature'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close <abbr title="One-Time Password">OTP</abbr> after copy',
            'help' => 'Clicking a generated password to copy it automatically hide it from the screen'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copy <abbr title="One-Time Password">OTP</abbr> on display',
            'help' => 'Automatically copy a generated password right after it appears on screen. Due to browsers limitations, only the first <abbr title="Time-based One-Time Password">TOTP</abbr> password will be copied, not the rotating ones'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Usar lector de código QR básico',
            'help' => 'Si experimenta problemas al capturar códigos QR habilite esta opción para cambiar a un lector de código QR más básico, pero más fiable'
        ],
        'display_mode' => [
            'label' => 'Modo de visualización',
            'help' => 'Elija si desea que las cuentas se muestren como una lista o como una cuadrícula'
        ],
        'grid' => 'Cuadrícula',
        'list' => 'Lista',
        'show_accounts_icons' => [
            'label' => 'Mostrar iconos',
            'help' => 'Mostar iconos de aplicaciones en la vista principal'
        ],
        'get_official_icons' => [
            'label' => 'Obtener iconos oficiales',
            'help' => '(Intentar) Obtener el icono oficial del emisor 2FA al añadir una cuenta'
        ],
        'auto_lock' => [
            'label' => 'Bloqueo automático',
            'help' => 'Cerrar sesión del usuario automáticamente en caso de inactividad. No tiene efecto cuando la autenticación es manejada por un proxy, ni cuando ninguna url de cierre de sesión personalizada se especificada.'
        ],
        'use_encryption' => [
            'label' => 'Proteger los datos confidenciales',
            'help' => 'Los datos sensibles, las claves secretas y correos electrónicos de 2FA, se almacenan cifrados en la base de datos. Asegúrese de respaldar el valor de APP_KEY de su archivo .env (o el archivo entero), pues, sirve como clave de cifrado. No hay forma de descifrar datos encriptados sin esta clave.',
        ],
        'default_group' => [
            'label' => 'Grupo por defecto',
            'help' => 'El grupo al que las cuentas recién creadas están asociadas',
        ],
        'useDirectCapture' => [
            'label' => 'Entrada directa',
            'help' => 'Elija si desea que se le pida que elija un modo de entrada entre los disponibles o si desea utilizar directamente el modo de entrada por defecto',
        ],
        'defaultCaptureMode' => [
            'label' => 'Modo de entrada de datos por defecto',
            'help' => 'Modo de entrada predeterminado usado cuando la opción de entrada directa está encendida',
        ],
        'remember_active_group' => [
            'label' => 'Recordar filtro de grupo',
            'help' => 'Guardar el último filtro de grupo aplicado y restaurarlo en su próxima visita',
        ],
        'never' => 'Nunca',
        'on_otp_copy' => 'Al copiar código de seguridad',
        '1_minutes' => 'Después de 1 minuto',
        '5_minutes' => 'Después de 5 minutos',
        '10_minutes' => 'Después de 10 minutos',
        '15_minutes' => 'Después de 15 minutos',
        '30_minutes' => 'Después de 30 minutos',
        '1_hour' => 'Después de 1 hora',
        '1_day' => 'Después de 1 día',
        'livescan' => 'Escaneo código QR',
        'upload' => 'Subida de código QR',
        'advanced_form' => 'Formulario avanzado',
    ],

];