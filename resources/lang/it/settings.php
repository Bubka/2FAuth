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

    'settings' => 'Settings',
    'preferences' => 'Preferences',
    'account' => 'Account',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Options',
    'user_preferences' => 'User preferences',
    'admin_settings' => 'Admin settings',
    'confirm' => [

    ],
    'you_are_administrator' => 'You are an administrator',
    'account_linked_to_sso_x_provider' => 'You signed-in via SSO using your :provider account. Your information cannot be changed here but on :provider.',
    'general' => 'General',
    'security' => 'Security',
    'notifications' => 'Notifications',
    'profile' => 'Profile',
    'change_password' => 'Change password',
    'personal_access_tokens' => 'Personal access tokens',
    'token_legend' => 'Personal Access Tokens allow any app to authenticate to the 2Fauth API. You should specify the access token as a Bearer token in the authorization header of consumer apps requests.',
    'generate_new_token' => 'Generate a new token',
    'revoke' => 'Revoke',
    'token_revoked' => 'Token successfully revoked',
    'revoking_a_token_is_permanent' => 'Revoking a token is permanent',
    'confirm' => [
        'revoke' => 'Are you sure you want to revoke this token?',
    ],
    'make_sure_copy_token' => 'Make sure to copy your personal access token now. You won’t be able to see it again!',
    'data_input' => 'Data input',
    'forms' => [
        'edit_settings' => 'Edit settings',
        'setting_saved' => 'Settings saved',
        'new_token' => 'New token',
        'some_translation_are_missing' => 'Some translations are missing using the browser preferred language?',
        'help_translate_2fauth' => 'Help translate 2FAuth',
        'language' => [
            'label' => 'Language',
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
            'help' => 'Let the ability to temporarily reveal Dot-Obscured passwords'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close <abbr title="One-Time Password">OTP</abbr> after copy',
            'help' => 'Click on a generated password to copy it automatically hides it from the screen'
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
            'label' => 'Copy <abbr title="One-Time Password">OTP</abbr> on display',
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
        'grid' => 'Grid',
        'list' => 'List',
        'theme' => [
            'label' => 'Theme',
            'help' => 'Force a specific theme or apply the theme defined in your system/browser preferences'
        ],
        'light' => 'Light',
        'dark' => 'Dark',
        'automatic' => 'Auto',
        'show_accounts_icons' => [
            'label' => 'Show icons',
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
            'label' => 'Show Password',
            'help' => 'Set how and when <abbr title="One-Time Passwords">OTPs</abbr> are displayed.<br/>',
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
        'otp_generation_on_request' => 'After a click/tap',
        'otp_generation_on_request_legend' => 'Alone, in its own view',
        'otp_generation_on_request_title' => 'Click an account to get a password in a dedicated view',
        'otp_generation_on_home' => 'Constantly',
        'otp_generation_on_home_legend' => 'All of them, on home',
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