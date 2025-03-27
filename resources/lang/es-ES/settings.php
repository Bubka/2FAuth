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
    'preferences' => 'Preferencias',
    'account' => 'Cuenta',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Opciones',
    'user_preferences' => 'Preferencias de usuario',
    'admin_settings' => 'Ajustes de administración',
    'confirm' => [

    ],
    'you_are_administrator' => 'Usted es un administrador',
    'account_linked_to_sso_x_provider' => 'Ha iniciado sesión a través de SSO usando su cuenta :provider. Su información no se puede cambiar aquí, sino en :provider.',
    'general' => 'General',
    'security' => 'Seguridad',
    'notifications' => 'Notifications',
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
    'settings_managed_by_administrator' => 'Some settings are being managed by your administrator',
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
        'timezone' => [
            'label' => 'Time zone',
            'help' => 'The time zone applied to all dates and times displayed in the application'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated OTP as dot',
            'help' => 'Replace generated password characters with *** to ensure confidentiality. Does not affect the copy/paste feature'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured OTP',
            'help' => 'Activa la capacidad de revelar temporalmente las contraseñas ocultas'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close OTP after copy',
            'help' => 'Click on a generated password to copy it automatically hides it from the screen'
        ],
        'show_next_otp' => [
            'label' => 'Show next OTP',
            'help' => 'Preview the next password, i.e. the password that will replace the current password when it expires. Preferences set for the current OTP also apply to the next one (formatting, show as dot)'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close OTP',
            'help' => 'Automatically hide on-screen password after a timeout. This avoids unnecessary requests for fresh passwords if you forget to close the password view.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Clear Search on copy',
            'help' => 'Empty the Search box right after a code has been copied to the clipboard'
        ],
        'sort_case_sensitive' => [
            'label' => 'Sort case sensitive',
            'help' => 'When invoked, force the Sort function to sort accounts on a case-sensitive basis'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copy OTP on display',
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
            'label' => 'Formato de contraseñas',
            'help' => 'Cambiar cómo se muestran las contraseñas agrupando dígitos para facilitar la legibilidad y la memorización'
        ],
        'pair' => 'por parejas',
        'pair_legend' => 'Grupo de dígitos dos por dos',
        'trio_legend' => 'Grupo de dígitos tres por tres',
        'half_legend' => 'Dividir dígitos en dos grupos iguales',
        'trio' => 'por tríos',
        'half' => 'por la mitad',
        'grid' => 'Cuadrícula',
        'list' => 'Lista',
        'theme' => [
            'label' => 'Tema',
            'help' => 'Forzar un tema específico o aplicar el tema definido en sus preferencias de sistema/navegador'
        ],
        'light' => 'Claro',
        'dark' => 'Oscuro',
        'automatic' => 'Automático',
        'show_accounts_icons' => [
            'label' => 'Mostrar iconos',
            'help' => 'Show account icons in the main view'
        ],
        'get_official_icons' => [
            'label' => 'Obtener iconos oficiales',
            'help' => '(Intentar) Obtener el icono oficial del emisor 2FA al añadir una cuenta'
        ],
        'auto_lock' => [
            'label' => 'Bloqueo automático',
            'help' => 'Cerrar sesión del usuario automáticamente en caso de inactividad. No tiene efecto cuando la autenticación es manejada por un proxy, ni cuando ninguna url de cierre de sesión personalizada se especificada.'
        ],
        'default_group' => [
            'label' => 'Grupo por defecto',
            'help' => 'El grupo al que las cuentas recién creadas están asociadas',
        ],
        'view_default_group_on_copy' => [
            'label' => 'View default group on copy',
            'help' => 'Always return to the default group when an OTP is copied',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Auto-save accounts',
            'help' => 'New accounts are automatically registered after scanning or uploading a QR code, no need to click a Save button',
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
        'otp_generation' => [
            'label' => 'Mostrar contraseña',
            'help' => 'Establezca cómo y cuándo se muestran las <abbr title="Contraseñas de un solo uso">OTP</abbr>.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'On new device',
            'help' => 'Get an email when a new device connects to your 2FAuth account for the first time'
        ],
        'notify_on_failed_login' => [
            'label' => 'On failed login',
            'help' => 'Get an email each time an attempt to connect to your 2FAuth account fails'
        ],
        'show_email_in_footer' => [
            'label' => 'Show email in footer',
            'help' => 'Display the logged-in user\'s email in the footer instead of direct navigation links. The links are then available in a menu behind a click/tap on the email address.'
        ],
        'otp_generation_on_request' => 'Después de un clic/toque',
        'otp_generation_on_request_legend' => 'Solo, en su propia vista',
        'otp_generation_on_request_title' => 'Haga clic en una cuenta para obtener una contraseña en una vista dedicada',
        'otp_generation_on_home' => 'Constantemente',
        'otp_generation_on_home_legend' => 'Todos ellos, en casa',
        'otp_generation_on_home_title' => 'Mostrar todas las contraseñas en la vista principal, sin hacer nada',
        'never' => 'Nunca',
        'on_otp_copy' => 'Al copiar código de seguridad',
        '1_minutes' => 'Después de 1 minuto',
        '2_minutes' => 'After 2 minutes',
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