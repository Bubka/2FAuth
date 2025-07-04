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

    'settings' => 'Opcions',
    'preferences' => 'Preferències',
    'account' => 'Compte',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Opcions',
    'user_preferences' => 'Preferències d\'usuari',
    'admin_settings' => 'Opcions d\'Admin',
    'confirm' => [

    ],
    'you_are_administrator' => 'Ets admin',
    'account_linked_to_sso_x_provider' => 'You signed-in via SSO using your :provider account. Your information cannot be changed here but on :provider.',
    'general' => 'General',
    'security' => 'Seguretat',
    'notifications' => 'Notificacions',
    'profile' => 'Perfil',
    'change_password' => 'Canvia contrasenya',
    'personal_access_tokens' => 'Claus d\'accés personals',
    'token_legend' => 'Personal Access Tokens allow any app to authenticate to the 2Fauth API. You should specify the access token as a Bearer token in the authorization header of consumer apps requests.',
    'generate_new_token' => 'Generar nou token',
    'revoke' => 'Revocar',
    'token_revoked' => 'Token revocat satisfactòriament',
    'revoking_a_token_is_permanent' => 'La revocació del token és permanent',
    'confirm' => [
        'revoke' => 'Segur que vols revocar aquest token?',
    ],
    'make_sure_copy_token' => 'Make sure to copy your personal access token now. You won’t be able to see it again!',
    'data_input' => 'Entrada de dades',
    'settings_managed_by_administrator' => 'Some settings are being managed by your administrator',
    'forms' => [
        'edit_settings' => 'Edita Opcions',
        'setting_saved' => 'Opcions desades',
        'new_token' => 'Nou token',
        'some_translation_are_missing' => 'Some translations are missing using the browser preferred language?',
        'help_translate_2fauth' => 'Ajuda a traduïr 2FAuth',
        'language' => [
            'label' => 'Idioma',
            'help' => 'Language used to translate the 2FAuth user interface. Named languages are complete, set the one of your choice to override your browser preference.'
        ],
        'timezone' => [
            'label' => 'Zona horària',
            'help' => 'The time zone applied to all dates and times displayed in the application'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated OTP as dot',
            'help' => 'Replace generated password characters with *** to ensure confidentiality. Does not affect the copy/paste feature'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured OTP',
            'help' => 'Let the ability to temporarily reveal Dot-Obscured passwords'
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
            'help' => 'Automatically copy a generated password right after it appears on screen. Due to browsers limitations, only the first <abbr title="Time-based One-Time Password">TOTP</abbr> password will be copied, not the rotating ones'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Use basic QR code reader',
            'help' => 'If you experiences issues when capturing QR codes enables this option to switch to a more basic but more reliable QR code reader'
        ],
        'display_mode' => [
            'label' => 'Display mode',
            'help' => 'Choose whether you want accounts to be displayed as a list or as a grid'
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
        'grid' => 'Graella',
        'list' => 'Llista',
        'theme' => [
            'label' => 'Tema',
            'help' => 'Force a specific theme or apply the theme defined in your system/browser preferences'
        ],
        'light' => 'Clar',
        'dark' => 'Fosc',
        'automatic' => 'Auto',
        'show_accounts_icons' => [
            'label' => 'Mostra icones',
            'help' => 'Show account icons in the main view'
        ],
        'get_official_icons' => [
            'label' => 'Obtenir icones oficials',
            'help' => '(Try to) Get the official icon of the 2FA issuer when adding an account'
        ],
        'icon_collection' => [
            'label' => 'Favorite icon source',
            'help' => 'The icons collection to be queried at first when an official icon is required. Changing this setting does not refresh icons that have already been fetched.'
        ],
        'icon_variant' => [
            'label' => 'Icon variant',
            'help' => 'Some icons are available in different flavors to best suit dark or light user interfaces. Set the one you want to look for first. The regular variant will automatically be fetched as a fallback.'
        ],
        'icon_variant_strict_fetch' => [
            'label' => 'Strict fetch',
            'help' => 'Narrow the fetch to the specified variant. If the variant is missing, 2FAuth will not try to fallback to the regular variant.'
        ],
        'auto_lock' => [
            'label' => 'Auto lock',
            'help' => 'Log out the user automatically in case of inactivity. Has no effect when authentication is handled by a proxy and no custom logout url is specified.'
        ],
        'default_group' => [
            'label' => 'Grup per defecte',
            'help' => 'The group to which the newly created accounts are associated',
        ],
        'view_default_group_on_copy' => [
            'label' => 'View default group on copy',
            'help' => 'Always return to the default group when an OTP is copied',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Auto-desa comptes',
            'help' => 'New accounts are automatically registered after scanning or uploading a QR code, no need to click a Save button',
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
        'otp_generation' => [
            'label' => 'Mostra contrasenya',
            'help' => 'Set how and when <abbr title="One-Time Passwords">OTPs</abbr> are displayed.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'En nou dispositiu',
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
        'otp_generation_on_request' => 'After a click/tap',
        'otp_generation_on_request_legend' => 'Alone, in its own view',
        'otp_generation_on_request_title' => 'Click an account to get a password in a dedicated view',
        'otp_generation_on_home' => 'Constantment',
        'otp_generation_on_home_legend' => 'All of them, on home',
        'otp_generation_on_home_title' => 'Show all passwords in the main view, without doing anything',
        'never' => 'Mai',
        'on_otp_copy' => 'On security code copy',
        '1_minutes' => 'Després d\'1 minut',
        '2_minutes' => 'Després de 2 minuts',
        '5_minutes' => 'Després de 5 minuts',
        '10_minutes' => 'Després de 10 minuts',
        '15_minutes' => 'Després de 15 minuts',
        '30_minutes' => 'Després de 30 minuts',
        '1_hour' => '1 hora més tard',
        '1_day' => 'Després d\'un dia',
        'livescan' => 'QR code livescan',
        'upload' => 'QR code upload',
        'advanced_form' => 'Advanced form',
    ],

];