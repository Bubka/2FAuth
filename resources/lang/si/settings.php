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
    'account' => 'ගිණුම',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'විකල්ප',
    'user_options' => 'User options',
    'confirm' => [

    ],
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
            'label' => 'Show generated one-time passwords as dot',
            'help' => 'Replace generated password caracters with *** to ensure confidentiality. Do not affect the copy/paste feature.'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close OTP after copy',
            'help' => 'Automatically close the popup showing the generated password after it has been copied'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Use basic QR code reader',
            'help' => 'If you experiences issues when capturing QR codes enables this option to switch to a more basic but more reliable QR code reader'
        ],
        'display_mode' => [
            'label' => 'Display mode',
            'help' => 'Choose whether you want accounts to be displayed as a list or as a grid'
        ],
        'grid' => 'Grid',
        'list' => 'List',
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