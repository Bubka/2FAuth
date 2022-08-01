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

    'service' => '服务',
    'account' => '账户',
    'accounts' => '账户',
    'icon' => '图标',
    'no_account_here' => '这里没有两步验证！',
    'add_first_account' => '添加您的第一个帐户',
    'use_full_form' => '或者使用完整的表单',
    'add_one' => '添加一个',
    'show_qrcode' => '显示 QR 码',
    'no_service' => '- 无服务 -',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'John DOE',
        ],
        'new_account' => '新建账户',
        'edit_account' => '编辑账户',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => '扫描QR码',
        'upload_qrcode' => '上传一个QR码',
        'use_advanced_form' => '使用高级表单',
        'prefill_using_qrcode' => '使用QR码进行预填充',
        'use_qrcode' => [
            'val' => '使用一个QR码',
            'title' => '使用QR码来自动填充表单',
        ],
        'unlock' => [
            'val' => '解锁',
            'title' => '解锁它(风险自负)',
        ],
        'lock' => [
            'val' => '锁定',
            'title' => '将其锁定',
        ],
        'choose_image' => 'Upload',
        'i_m_lucky' => 'I\'m lucky',
        'i_m_lucky_legend' => 'The "I\'m lucky" button try to get the official icon of the given service. Enter actual service name without ".xyz" extension and try to avoid typo. (beta feature)',
        'test' => '测试',
        'secret' => [
            'label' => '密钥',
            'help' => '用于生成安全码的密钥'
        ],
        'plain_text' => '纯文本',
        'otp_type' => [
            'label' => '选择要创建的 OTP 类型',
            'help' => '基于时间的OTP或基于HMAC的OTP'
        ],
        'digits' => [
            'label' => '码长',
            'help' => '生成的安全码位数'
        ],
        'algorithm' => [
            'label' => '算法',
            'help' => '用于保护您的安全代码的算法'
        ],
        'period' => [
            'label' => '周期',
            'placeholder' => '默认为30',
            'help' => '生成的二维码的以秒为单位的有效期限'
        ],
        'counter' => [
            'label' => '计数器',
            'placeholder' => '默认为0',
            'help' => 'The initial counter value',
            'help_lock' => 'It is risky to edit the counter as you can desynchronize the account with the verification server of the service. Use the lock icon to enable modification, but only if you know for you are doing'
        ],
        'image' => [
            'label' => 'Image',
            'placeholder' => 'http://...',
            'help' => 'The url of an external image to use as the account icon'
        ],
        'options_help' => 'You can leave the following options blank if you don\'t know how to set them. The most commonly used values will be applied.',
        'alternative_methods' => 'Alternative methods',
    ],
    'stream' => [
        'live_scan_cant_start' => 'Live scan can\'t start :(',
        'need_grant_permission' => [
            'reason' => '2FAuth does not have permission to access your camera',
            'solution' => 'You need to grant permission to use your device camera. If you already denied and your browser do not prompt you again, please refers to the browser documentation to find out how to grant permission.'
        ],
        'not_readable' => [
            'reason' => 'Fail to load scanner',
            'solution' => 'Is the camera already in use? Ensure that no other app use your camera and try again'
        ],
        'no_cam_on_device' => [
            'reason' => 'No camera on this device',
            'solution' => 'Maybe you forgot to plug in your webcam'
        ],
        'secured_context_required' => [
            'reason' => 'Secure context required',
            'solution' => 'HTTPS is required for live scan. If you run 2FAuth from your computer, do not use virtual host other than localhost'
        ],
        'https_required' => 'HTTPS required for camera streaming',
        'camera_not_suitable' => [
            'reason' => 'Installed cameras are not suitable',
            'solution' => 'Please use another device/camera'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Stream API is not supported in this browser',
            'solution' => 'You should use a modern browser'
        ],
    ],
    'confirm' => [
        'delete' => 'Are you sure you want to delete this account?',
        'cancel' => 'The account will be lost. Are you sure?',
        'discard' => 'Are you sure you want to discard this account?',
        'discard_all' => 'Are you sure you want to discard all accounts?',
        'discard_duplicates' => 'Are you sure you want to discard all duplicates?',
    ],
    'import' => [
        'import' => 'Import',
        'to_import' => 'Import',
        'import_legend' => 'Import your Google Authenticator accounts.',
        'use_the_gauth_qr_code' => 'Load a G-Auth QR code',
        'issuer' => 'Issuer',
        'imported' => 'Imported',
        'failure' => 'Failure',
        'x_valid_accounts_found' => '{count} valid accounts found',
        'import_all' => 'Import all',
        'import_this_account' => 'Import this account',
        'discard_all' => 'Discard all',
        'discard_duplicates' => 'Discard duplicates',
        'discard_this_account' => 'Discard this account',
        'generate_a_test_password' => 'Generate a test pasword',
        'possible_duplicate' => 'An account with the exact same data already exists',
    ],

];