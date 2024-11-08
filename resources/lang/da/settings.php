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

    'settings' => 'Indstillinger',
    'preferences' => 'Præferencer',
    'account' => 'Konto',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Valgmuligheder',
    'user_preferences' => 'Præferencer',
    'admin_settings' => 'Admin indstillinger',
    'confirm' => [

    ],
    'you_are_administrator' => 'You are an administrator',
    'account_linked_to_sso_x_provider' => 'You signed-in via SSO using your :provider account. Your information cannot be changed here but on :provider.',
    'general' => 'Generelt',
    'security' => 'Sikkerhed',
    'notifications' => 'Notifikationer',
    'profile' => 'Profil',
    'change_password' => 'Skift adgangskode',
    'personal_access_tokens' => 'personlige adgangstokens',
    'token_legend' => 'Personal Access Tokens allow any app to authenticate to the 2Fauth API. You should specify the access token as a Bearer token in the authorization header of consumer apps requests.',
    'generate_new_token' => 'Generate a new token',
    'revoke' => 'Ophæv',
    'token_revoked' => 'Token successfully revoked',
    'revoking_a_token_is_permanent' => 'Revoking a token is permanent',
    'confirm' => [
        'revoke' => 'Are you sure you want to revoke this token?',
    ],
    'make_sure_copy_token' => 'Make sure to copy your personal access token now. You won’t be able to see it again!',
    'data_input' => 'Data input',
    'forms' => [
        'edit_settings' => 'Edit settings',
        'setting_saved' => 'Indstillingerne er gemt!',
        'new_token' => 'Ny token',
        'some_translation_are_missing' => 'Some translations are missing using the browser preferred language?',
        'help_translate_2fauth' => 'Hjælp med at oversætte 2FAuth',
        'language' => [
            'label' => 'Sprog',
            'help' => 'Language used to translate the 2FAuth user interface. Named languages are complete, set the one of your choice to override your browser preference.'
        ],
        'timezone' => [
            'label' => 'Time zone',
            'help' => 'The time zone applied to all dates and times displayed in the application'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated <abbr title="One-Time Password">OTP</abbr> as dot',
            'help' => 'Replace generated password caracters with *** to ensure confidentiality. Do not affect the copy/paste feature'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured <abbr title="One-Time Password">OTP</abbr>',
            'help' => 'Lad muligheden for midlertidigt at afsløre Dot-Obscured adgangskoder'
        ],
        'close_otp_on_copy' => [
            'label' => 'Luk <abbr title="One-Time Password">OTP</abbr> efter kopi',
            'help' => 'Klik på en genereret adgangskode for at kopiere den automatisk skjuler den fra skærmen'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close <abbr title="One-Time Password">OTP</abbr>',
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
            'label' => 'Kopier <abbr title="One-Time Password">OTP</abbr> på skærmen',
            'help' => 'Automatically copy a generated password right after it appears on screen. Due to browsers limitations, only the first <abbr title="Time-based One-Time Password">TOTP</abbr> password will be copied, not the rotating ones'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Brug grundlæggende QR-kode læser',
            'help' => 'Hvis du oplever problemer, når du optager QR-koder, kan du skifte til en mere basal, men mere pålidelig QR-kodelæser'
        ],
        'display_mode' => [
            'label' => 'Visnings tilstand',
            'help' => 'Vælg om du vil have konti vist som en liste eller som et gitter'
        ],
        'password_format' => [
            'label' => 'Formatering af adgangskode',
            'help' => 'Change how the passwords are displayed by grouping digits to ease readability and memorization'
        ],
        'pair' => 'efter par',
        'pair_legend' => 'Grupper cifre to med to',
        'trio_legend' => 'Grupper cifre tre og tre',
        'half_legend' => 'Opdel cifre i to lige store grupper',
        'trio' => 'af Trio',
        'half' => 'ved halv',
        'grid' => 'Gitter',
        'list' => 'Liste',
        'theme' => [
            'label' => 'Tema',
            'help' => 'Force a specific theme or apply the theme defined in your system/browser preferences'
        ],
        'light' => 'Lys',
        'dark' => 'Mørk',
        'automatic' => 'Automatisk',
        'show_accounts_icons' => [
            'label' => 'Vis ikoner',
            'help' => 'Show account icons in the main view'
        ],
        'get_official_icons' => [
            'label' => 'Get official icons',
            'help' => '(Try to) Get the official icon of the 2FA issuer when adding an account'
        ],
        'auto_lock' => [
            'label' => 'Auto lock',
            'help' => 'Log out the user automatically in case of inactivity. Has no effect when authentication is handled by a proxy and no custom logout url is specified.'
        ],
        'default_group' => [
            'label' => 'Default group',
            'help' => 'The group to which the newly created accounts are associated',
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
            'label' => 'Vis adgangskode',
            'help' => 'Indstil hvordan og hvornår <abbr title="One-Time Passwords">OTP\'er</abbr> vises.<br/>',
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
        'otp_generation_on_request' => 'Efter et klik/tryk',
        'otp_generation_on_request_legend' => 'Alene i sin egen visning',
        'otp_generation_on_request_title' => 'Click an account to get a password in a dedicated view',
        'otp_generation_on_home' => 'Konstant',
        'otp_generation_on_home_legend' => 'Allesammen, på hovedskærmen',
        'otp_generation_on_home_title' => 'Show all passwords in the main view, without doing anything',
        'never' => 'Never',
        'on_otp_copy' => 'On security code copy',
        '1_minutes' => 'After 1 minute',
        '2_minutes' => 'After 2 minutes',
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