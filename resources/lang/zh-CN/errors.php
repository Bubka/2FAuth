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

    'resource_not_found' => '找不到资源',
    'error_occured' => '发生错误：',
    'refresh' => '刷新',
    'no_valid_otp' => '此二维码中没有有效的 OTP 字段',
    'something_wrong_with_server' => '服务器发生内部错误',
    'Unable_to_decrypt_uri' => '无法解密 uri',
    'not_a_supported_otp_type' => '不支持此 OTP 格式',
    'cannot_create_otp_without_secret' => '无法在无密码的情况下创建一个 OTP',
    'data_of_qrcode_is_not_valid_URI' => '此二维码的数据不是有效的 OTP Auth URI。此二维码包含：',
    'wrong_current_password' => '当前密码错误，更改已取消',
    'error_during_encryption' => '加密失败，您的数据库仍处于未保护状态。',
    'error_during_decryption' => '解密失败，您的数据库仍处于保护状态。这通常是因为某个或多个账户的加密数据的完整性存在问题而导致的。',
    'qrcode_cannot_be_read' => '二维码无效',
    'too_many_ids' => '查询参数中包含太多 ID，最多允许 100 个',
    'delete_user_setting_only' => '只能删除由用户创建的设置',
    'indecipherable' => '*无法解析*',
    'cannot_decipher_secret' => '无法解密，可能是因为 2FAuth 的 .env 文件中 APP_KEY 值的设置错误，或存储在数据库中的数据已损坏。',
    'https_required' => '需要 HTTPS',
    'browser_does_not_support_webauthn' => '您的设备不支持 Webauthn，请尝试换用新版浏览器并重试。',
    'aborted_by_user' => '被用户中止',
    'security_device_already_registered' => '设备已被注册过',
    'not_allowed_operation' => '不允许此操作',
    'no_authenticator_support_specified_algorithms' => '此算法没有任何身份验证器支持',
    'authenticator_missing_discoverable_credential_support' => '身份验证器暂不兼容可识别凭据',
    'authenticator_missing_user_verification_support' => '身份验证器暂不兼容用户验证',
    'unknown_error' => '未知错误',
    'security_error_check_rpid' => '安全错误<br/>请检查您的 WEBAUTHN_ID 环境变量',
    '2fauth_has_not_a_valid_domain' => '2FAuth 的域名无效',
    'user_id_not_between_1_64' => '用户 ID 需为 1 至 64 个字符内',
    'no_entry_was_of_type_public_key' => '没有类型为 “公钥” 的条目',
    'unsupported_with_reverseproxy' => '当身份代理或 SSO 启用时不可用',
    'unsupported_with_sso_only' => '仅管理员允许使用此鉴权方式，普通用户必须使用 SSO 登录。',
    'user_deletion_failed' => '账户删除失败，数据未被删除',
    'auth_proxy_failed' => '身份代理认证失败',
    'auth_proxy_failed_legend' => '已为 2FAuth 配置了前置的身份代理，但身份代理并没有返回正确的请求头，请检查您的配置并重试。',
    'invalid_x_migration' => '无效或不兼容的 :appname 数据',
    'invalid_2fa_data' => '无效的 2FA 数据',
    'unsupported_migration' => '不兼容的数据格式',
    'unsupported_otp_type' => '不兼容的 OTP 类型',
    'encrypted_migration' => '无法读取，数据可能已被加密',
    'no_logo_found_for_x' => '没有为 :service 找到可用的 Logo',
    'file_upload_failed' => '文件上传失败',
    'unauthorized' => '无权限',
    'unauthorized_legend' => '您无权查看此资源或执行此操作',
    'cannot_delete_the_only_admin' => '这是唯一的管理员账户，无法删除',
    'cannot_demote_the_only_admin' => '这是唯一的管理员账户，无法降级',
    'error_during_data_fetching' => '💀 获取数据时出错',
    'check_failed_try_later' => '检查失败，请稍后重试',
    'sso_disabled' => 'SSO 已禁用',
    'sso_bad_provider_setup' => '您未在 .env 文件中正确配置此 SSO 提供方',
    'sso_failed' => 'SSO 验证被拒绝',
    'sso_no_register' => '已停用注册',
    'sso_email_already_used' => '已存在相同邮箱的账户，但不匹配您的外部账户 ID 。如果您已使用此邮箱在 2FAuth 上注册过，请不要使用 SSO。',
    'account_managed_by_external_provider' => '由外部提供方管理的账户',
    'data_cannot_be_refreshed_from_server' => '无法从服务器刷新数据',
    'no_pwd_reset_for_this_user_type' => '无法为此用户重置密码',
    'cannot_detect_qrcode_in_image' => '未在图像中检测到二维码，请裁切图像后再试',
    'cannot_decode_detected_qrcode' => '二维码已识别，但解码失败，请裁切或锐化图像后再试',
    'qrcode_has_invalid_checksum' => '二维码的校验码错误',
    'no_readable_qrcode' => '没有可识别的二维码',
    'failed_icon_store_database_toggling' => '图标迁移失败，相关设置已恢复为先前的值。',
    'failed_to_retrieve_app_settings' => 'Failed to retrieve application settings',
    'reserved_name_please_choose_something_else' => 'Reserved name, please choose something else',
];