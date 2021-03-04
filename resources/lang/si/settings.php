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
    'password' => 'මුර පදය',
    'options' => 'Options',
    'confirm' => [

    ],
    'general' => 'General',
    'security' => 'ආරක්ෂාව',
    'data_input' => 'Data input',
    'forms' => [
        'edit_settings' => 'Edit settings',
        'setting_saved' => 'Settings saved',
        'language' => [
            'label' => 'භාෂාව',
            'help' => 'Change the language used to translate the app interface.'
        ],
        'show_token_as_dot' => [
            'label' => 'Show generated tokens as dot',
            'help' => 'Replace generated token caracters with *** to ensure confidentiality. Do not affect the copy/paste feature.'
        ],
        'close_token_on_copy' => [
            'label' => 'Close token after copy',
            'help' => 'Automatically close the popup showing the generated token after it has been copied'
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
            'label' => 'Show icons',
            'help' => 'Show icons accounts in the main view'
        ],
        'auto_lock' => [
            'label' => 'Auto lock',
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
