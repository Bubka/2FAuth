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
    'icon' => 'Icon',
    'new' => 'New',
    'no_account_here' => 'No 2FA here!',
    'add_first_account' => 'Add your first account',
    'use_full_form' => 'Or use the full form',
    'add_one' => 'Add one',
    'manage' => 'Manage',
    'done' => 'Done',
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
        'hotp_counter' => 'HOTP Counter',
        'scan_qrcode' => 'Scan a qrcode',
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
        'create' => 'Create',
        'save' => 'Save',
        'test' => 'Test',
    ],
    'stream' => [
        'need_grant_permission' => 'You need to grant camera access permission',
        'not_readable' => 'Fail to load scanner. Is the camera already in use?',
        'no_cam_on_device' => 'No camera on this device',
        'secured_context_required' => 'Secure context required (HTTPS or localhost)',
        'camera_not_suitable' => 'Installed cameras are not suitable',
        'stream_api_not_supported' => 'Stream API is not supported in this browser'
    ],
    'confirm' => [
        'delete' => 'Are you sure you want to delete this account?',
        'cancel' => 'The account will be lost. Are you sure?'
    ],

];