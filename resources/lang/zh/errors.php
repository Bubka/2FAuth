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
    'security_device_already_registered' => '设备已被注册过',
    'not_allowed_operation' => '不允许此操作',
    'no_authenticator_support_specified_algorithms' => '没有身份验证器支持指定的算法',
    'authenticator_missing_discoverable_credential_support' => '身份验证器缺少可发现凭据的支持',
    'authenticator_missing_user_verification_support' => '身份验证器缺少用户验证支持',
    'unknown_error' => '未知错误',
    'security_error_check_rpid' => '安全错误<br/>请检查您的 WEBAUTHN_ID env var',
    '2fauth_has_not_a_valid_domain' => '2FAuth的域不是一个有效的域',
    'user_id_not_between_1_64' => '用户ID不在 1 到 64 个字符内',
    'no_entry_was_of_type_public_key' => '没有类型为"公钥"的条目',
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
    'file_upload_failed' => '文件上传失败',
    'unauthorized' => '无权限',
    'unauthorized_legend' => '您无权查看此资源或执行此操作',
    'cannot_delete_the_only_admin' => '无法删除唯一的管理员账户',
    'error_during_data_fetching' => '💀 在获取数据过程中出了问题',
    'check_failed_try_later' => '检查失败，请稍后重试',
    'sso_disabled' => 'SSO 已禁用',
    'sso_bad_provider_setup' => '此 SSO 提供商没有在您的 .env 文件中完全设置',
    'sso_failed' => '通过 SSO 验证被拒绝',
    'sso_no_register' => '注册已禁用',
    'sso_email_already_used' => '已存在具有相同电子邮件地址的用户帐户，但它与您的外部帐户ID不匹配。 如果您已经在 2FAuth 上使用此邮箱注册，请不要使用 SSO。',
    'account_managed_by_external_provider' => '由外部提供商管理的帐户',
    'data_cannot_be_refreshed_from_server' => 'Data cannot be refreshed from server'
];