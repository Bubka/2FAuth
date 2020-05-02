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
    'account' => 'Account',
    'password' => 'Password',
    'options' => 'Options',
    'confirm' => [

    ],
    'forms' => [
        'edit_settings' => 'Edit settings',
        'setting_saved' => 'Settings saved',
        'language' => [
            'label' => 'Language',
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
            'label' => 'Use basic qrcode reader',
            'help' => 'If you experiences issues when capturing qrCodes enables this option to switch to a more basic but more reliable qrcode reader'
        ],
        'display_mode' => [
            'label' => 'Desktop display mode',
            'help' => 'Choose whether you want accounts to be displayed as a list or as a grid on desktop'
        ],
        'grid' => 'Grid',
        'list' => 'List',
    ],
    

];
