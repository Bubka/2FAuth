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

    'settings' => 'Configuración',
    'account' => 'Cuenta',
    'password' => 'Contraseña',
    'options' => 'Opciones',
    'confirm' => [

    ],
    'general' => 'General',
    'security' => 'Seguridad',
    'data_input' => 'Datos de entrada',
    'forms' => [
        'edit_settings' => 'Modificar configuración',
        'setting_saved' => 'Ajustes guardados',
        'language' => [
            'label' => 'Idioma',
            'help' => 'Cambiar el idioma utilizado para traducir la interfaz de la aplicación.'
        ],
        'show_token_as_dot' => [
            'label' => 'Mostrar tokens generados como punto',
            'help' => 'Sustituya los carácteres de token generados por *** para asegurar la confidencialidad. No afecta a la función de copiar/pegar.'
        ],
        'close_token_on_copy' => [
            'label' => 'Cerrar token después de la copia',
            'help' => 'Cerrar automáticamente la ventana emergente mostrando el token generado después de que ha sido copiado'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Usar lector de código QR básico',
            'help' => 'Si experimenta problemas al capturar códigos QR habilita esta opción para cambiar a un lector de código QR más básico pero más fiable'
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
        'auto_lock' => [
            'label' => 'Bloqueo automático',
            'help' => 'Log out the user automatically in case of inactivity'
        ],
        'use_encryption' => [
            'label' => 'Protect sensible data',
            'help' => 'Sensitive data, the 2FA secrets and emails, are stored encrypted in database. Be sure to backup the APP_KEY value of your .env file (or the whole file) as it serves as key encryption. There is no way to decypher encrypted data without this key.',
        ],
        'default_group' => [
            'label' => 'Default group',
            'help' => 'The group to which the newly created accounts are associated',
        ],
        'useDirectCapture' => [
            'label' => 'Direct input',
            'help' => 'Choose whether you want to be prompted to choose an input mode among those available or if you want to directly use the default input mode',
        ],
        'defaultCaptureMode' => [
            'label' => 'Default input mode',
            'help' => 'Default input mode used when the Direct input option is On',
        ],
        'remember_active_group' => [
            'label' => 'Remember group filter',
            'help' => 'Save the last group filter applied and restore it on your next visit',
        ],
        'never' => 'Never',
        'on_token_copy' => 'On security code copy',
        '1_minutes' => 'After 1 minute',
        '5_minutes' => 'After 5 minutes',
        '10_minutes' => 'After 10 minutes',
        '15_minutes' => 'After 15 minutes',
        '30_minutes' => 'After 30 minutes',
        '1_hour' => 'After 1 hour',
        '1_day' => 'After 1 day',
        'livescan' => 'QR code livescan',
        'upload' => 'QR code upload',
        'advanced_form' => 'Advanced form',
    ],

];
