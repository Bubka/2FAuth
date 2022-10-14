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

    'resource_not_found' => '资源未找到',
    'error_occured' => '发生错误:',
    'cannot_register_more_user' => '您不能注册多个用户',
    'refresh' => '刷新',
    'no_valid_otp' => '此二维码中没有有效的OTP资源',
    'something_wrong_with_server' => '服务器发生内部错误',
    'Unable_to_decrypt_uri' => '无法解密uri',
    'not_a_supported_otp_type' => '不支持此OTP格式',
    'cannot_create_otp_without_secret' => '无法在没有密码的情况下创建一个OTP',
    'data_of_qrcode_is_not_valid_URI' => '此QR码的数据不是有效的OTP Auth URI。该QR码包含:',
    'wrong_current_password' => '当前密码错误，没有发生任何更改',
    'error_during_encryption' => '加密失败，您的数据库仍未受到保护',
    'error_during_decryption' => '解密失败，您的数据库仍受保护。这通常由一个或多个帐户加密数据的完整性不足导致。',
    'qrcode_cannot_be_read' => '二维码无效',
    'too_many_ids' => '查询参数中包含太多ID，最多允许 100 个',
    'delete_user_setting_only' => '只能删除用户创建的设置',
    'indecipherable' => '*无法解析*',
    'cannot_decipher_secret' => '密钥不能被解密。这主要是由 2Fauth 的 .env 文件中 APP_KEY 设置错误或存储在数据库中的数据已损坏引发的。',
    'https_required' => '需要 HTTPS',
    'browser_does_not_support_webauthn' => '您的设备不支持Webauthn。请使用更现代的浏览器重试。',
    'aborted_by_user' => '被用户中止。',
    'security_device_unsupported' => '不支持的安全设备',
    'unsupported_with_reverseproxy' => '使用代理进行认证时不可用',
    'user_deletion_failed' => '帐户删除失败，没有数据被删除',
    'auth_proxy_failed' => '代理认证失败',
    'auth_proxy_failed_legend' => '2Fauth 被配置为在身份验证代理后运行，但您的代理没有返回预期的请求头。请检查您的配置并重试。',
    'invalid_x_migration' => '无效或不可读的 :appname 数据',
    'invalid_2fa_data' => '无效的2FA数据',
    'unsupported_migration' => '数据与任何支持的格式不匹配',
    'unsupported_otp_type' => '不支持的 OTP 类型',
    'encrypted_migration' => '无法读取，数据似乎已加密',
    'no_logo_found_for_x' => '{service} 没有可用的 Logo',
    'file_upload_failed' => '文件上传失败'
];