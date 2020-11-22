<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'service' => 'Service',
    'account' => 'Account',
    'accounts' => 'Accounts',
    'icon' => 'Icon',
    'no_account_here' => 'No 2FA here!',
    'add_first_account' => 'Add your first account',
    'use_full_form' => 'Or use the full form',
    'add_one' => 'Add one',
    'show_qrcode' => 'Show QR code',
    'forms' => [
        'service' => [
            'placeholder' => 'example.com',
        ],
        'account' => [
            'placeholder' => 'John DOE',
        ],
        'new_account' => 'New account',
        'edit_account' => 'Edit account',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'Scan a QR code',
        'upload_qrcode' => 'Upload a QR code',
        'use_advanced_form' => 'Use the advanced form',
        'prefill_using_qrcode' => 'Prefill using a QR Code',
        'use_qrcode' => [
            'val' => 'Use a qrcode',
            'title' => 'Use a QR code to fill the form magically',
        ],
        'unlock' => [
            'val' => 'Unlock',
            'title' => 'Unlock it (at your own risk)',
        ],
        'lock' => [
            'val' => 'Lock',
            'title' => 'Lock it',
        ],
        'choose_image' => 'Choose an image…',
        'test' => 'Test',
        'secret' => [
            'label' => 'Secret',
            'help' => 'The key used to generate your security codes'
        ],
        'plain_text' => 'Plain text',
        'otp_type' => [
            'label' => 'Choose the type of OTP to create',
            'help' => 'Time-based OTP or HMAC-based OTP'
        ],
        'digits' => [
            'label' => 'Digits',
            'help' => 'The number of digits of the generated security codes'
        ],
        'algorithm' => [
            'label' => 'Algorithm',
            'help' => 'The algorithm used to secure your security codes'
        ],
        'totpPeriod' => [
            'label' => 'Period',
            'placeholder' => 'Default is 30',
            'help' => 'The period of validity of the generated security codes in second'
        ],
        'hotpCounter' => [
            'label' => 'Counter',
            'placeholder' => 'Default is 0',
            'help' => 'The initial counter value',
            'help_lock' => 'It is risky to edit the counter as you can desynchronize the account with the verification server of the service. Use the lock icon to enable modification, but only if you know for you are doing'
        ],
        'image_link' => [
            'label' => 'Image',
            'placeholder' => 'http://...',
            'help' => 'The url of an external image to use as the account icon'
        ],
        'options_help' => 'You can leave the following options blank if you don\'t know how to set them. The most commonly used values will be applied.',
        'alternative_methods' => 'Alternative methods',
    ],
    'stream' => [
        'need_grant_permission' => 'You need to grant camera access permission',
        'not_readable' => 'Fail to load scanner. Is the camera already in use?',
        'no_cam_on_device' => 'No camera on this device',
        'secured_context_required' => 'Secure context required (HTTPS or localhost)',
        'https_required' => 'HTTPS required for camera streaming',
        'camera_not_suitable' => 'Installed cameras are not suitable',
        'stream_api_not_supported' => 'Stream API is not supported in this browser'
    ],
    'confirm' => [
        'delete' => 'Are you sure you want to delete this account?',
        'cancel' => 'The account will be lost. Are you sure?'
    ],

];