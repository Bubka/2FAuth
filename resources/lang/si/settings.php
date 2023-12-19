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

    'settings' => 'සැකසුම්',
    'preferences' => 'Preferences',
    'account' => 'ගිණුම',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'විකල්ප',
    'user_preferences' => 'User preferences',
    'admin_settings' => 'Admin settings',
    'confirm' => [

    ],
    'administration' => 'Administration',
    'administration_legend' => 'While previous settings are user settings (every user can set its own preferences), following settings are global and apply to all users.',
    'only_an_admin_can_edit_them' => 'Only an administrator can view and edit them.',
    'you_are_administrator' => 'You are an administrator',
    'account_linked_to_sso_x_provider' => 'You signed-in via SSO using your :provider account. Your information cannot be changed here but on :provider.',
    'general' => 'General',
    'security' => 'ආරක්ෂාව',
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
    'data_input' => 'දත්ත ආදානය',
    'forms' => [
        'edit_settings' => 'සැකසුම් සංස්කරණය',
        'setting_saved' => 'සැකසුම් සුරැකිණි',
        'new_token' => 'New token',
        'some_translation_are_missing' => 'Some translations are missing using the browser preferred language?',
        'help_translate_2fauth' => 'Help translate 2FAuth',
        'language' => [
            'label' => 'භාෂාව',
            'help' => 'Language used to translate the 2FAuth user interface. Named languages are complete, set the one of your choice to override your browser preference.'
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
            'help' => 'Clicking a generated password to copy it automatically hide it from the screen'
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
            'label' => 'නිරූපක පෙන්වන්න',
            'help' => 'Show icons accounts in the main view'
        ],
        'get_official_icons' => [
            'label' => 'Get official icons',
            'help' => '(Try to) Get the official icon of the 2FA issuer when adding an account'
        ],
        'auto_lock' => [
            'label' => 'ස්වයං අගුලුවැටීම',
            'help' => 'Log out the user automatically in case of inactivity. Has no effect when authentication is handled by a proxy and no custom logout url is specified.'
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
            'label' => 'සෘජු ආදානය',
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
        'disable_registration' => [
            'label' => 'Disable registration',
            'help' => 'Prevent new user registration. This affects SSO as well, so new SSO users won\'t be able to sign on',
        ],
        'enable_sso' => [
            'label' => 'Enable Single Sign-On (SSO)',
            'help' => 'Allow visitors to authenticate using an external ID via the Single Sign-On scheme',
        ],
        'otp_generation' => [
            'label' => 'Show Password',
            'help' => 'Set how and when <abbr title="One-Time Passwords">OTPs</abbr> are displayed.<br/>',
        ],
        'otp_generation_on_request' => 'After a click/tap',
        'otp_generation_on_request_legend' => 'Alone, in its own view',
        'otp_generation_on_request_title' => 'Click an account to get a password in a dedicated view',
        'otp_generation_on_home' => 'Constantly',
        'otp_generation_on_home_legend' => 'All of them, on home',
        'otp_generation_on_home_title' => 'Show all passwords in the main view, without doing anything',
        'never' => 'Never',
        'on_otp_copy' => 'On security code copy',
        '1_minutes' => 'විනාඩි 1කට පසු',
        '5_minutes' => 'විනාඩි 5කට පසු',
        '10_minutes' => 'විනාඩි 10කට පසු',
        '15_minutes' => 'විනාඩි 15කට පසු',
        '30_minutes' => 'විනාඩි 30කට පසු',
        '1_hour' => 'පැය 1කට පසු',
        '1_day' => 'දිනකට පසු',
        'livescan' => 'QR code livescan',
        'upload' => 'QR code upload',
        'advanced_form' => 'Advanced form',
    ],

];