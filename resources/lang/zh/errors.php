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
    'data_of_qrcode_is_not_valid_URI' => '此QR码的数据不是有效的OTP Auth URI：',
    'wrong_current_password' => '当前密码错误，没有发生任何更改',
    'error_during_encryption' => '加密失败，您的数据库仍未受到保护',
    'error_during_decryption' => 'Decryption failed, your database is still protected. This is mainly caused by an integrity issue of encrypted data for one or more accounts.',
    'qrcode_cannot_be_read' => 'This QR code is unreadable',
    'too_many_ids' => 'too many ids were included in the query parameter, max 100 allowed',
    'delete_user_setting_only' => 'Only user-created setting can be deleted',
    'indecipherable' => '*indecipherable*',
    'cannot_decipher_secret' => 'The secret cannot be deciphered. This is mainly caused by a wrong APP_KEY set in the .env configuration file of 2Fauth or a corrupted data stored in database.',
    'https_required' => 'HTTPS context required',
    'browser_does_not_support_webauthn' => 'Your device does not support webauthn. Try again later using a more modern browser',
    'aborted_by_user' => 'Aborted by user',
    'security_device_unsupported' => 'Security device unsupported',
    'unsupported_with_reverseproxy' => 'Not applicable when using an auth proxy',
    'user_deletion_failed' => 'User account deletion failed, no data have been deleted',
    'auth_proxy_failed' => 'Proxy authentication failed',
    'auth_proxy_failed_legend' => '2Fauth is configured to run behind an authentication proxy but your proxy does not return the expected header. Check your configuration and try again.',
    'invalid_google_auth_migration' => 'Invalid or unreadable Google Authenticator data',
    'unsupported_otp_type' => 'Unsupported OTP type',
    'no_logo_found_for_x' => 'No logo available for {service}'
];