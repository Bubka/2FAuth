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
    'preferences' => 'Preferences',
    'account' => 'Cuenta',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Opciones',
    'user_preferences' => 'User preferences',
    'admin_settings' => 'Admin settings',
    'confirm' => [

    ],
    'administration' => 'Administration',
    'administration_legend' => 'While previous settings are user settings (every user can set its own preferences), following settings are global and apply to all users. Only an administrator can view and edit those settings.',
    'you_are_administrator' => 'You are an administrator',
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
            'help' => 'Reemplaza carácteres de contraseñas generados con *** para asegurar confidencialidad. No afecta la función copiar/pegar.'
        ],
        'close_otp_on_copy' => [
            'label' => 'Cerrar <abbr title="One-Time Password">OTP</abbr> después de copiar',
            'help' => 'Haciendo clic en la contraseña generada para copiarla, la oculta automáticamente de la pantalla'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copiar <abbr title="One-Time Password">OTP</abbr> en pantalla',
            'help' => 'Copiar automáticamente la contraseña justo después de aparecer en pantalla. Debido a limitaciones en los navegadores, solo la primera contraseña <abbr title="Time-based One-Time Password">TOTP</abbr> será copiada, no las que roten'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Usar lector de código QR básico',
            'help' => 'Si experimenta problemas al capturar códigos QR habilite esta opción para cambiar a un lector de código QR más básico, pero más fiable'
        ],
        'display_mode' => [
            'label' => 'Modo de visualización',
            'help' => 'Elija si desea que las cuentas se muestren como una lista o como una cuadrícula'
        ],
        'password_format' => [
            'label' => 'Password formatting',
            'help' => 'Change how the passwords are displayed by grouping digits to ease readability and memorization'
        ],
        'pair' => 'by Pair',
        'pair_legend' => 'Group digits two by two',
        'trio_legend' => 'Group digits three by three',
        'half_legend' => 'Split digits into two equals groups',
        'trio' => 'by Trio',
        'half' => 'by Half',
        'grid' => 'Cuadrícula',
        'list' => 'Lista',
        'theme' => [
            'label' => 'Theme',
            'help' => 'Force a specific theme or apply the theme defined in your system/browser preferences'
        ],
        'light' => 'Light',
        'dark' => 'Dark',
        'automatic' => 'Auto',
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