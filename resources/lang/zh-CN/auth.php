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
   
    // Laravel
    'failed' => '登录信息错误',
    'password' => '密码错误',
    'throttle' => '您尝试登录的次数过多，请 :seconds 秒后再试。',

    // 2FAuth
    'sign_out' => '退出',
    'sign_in' => '登录',
    'sign_in_using' => '切换登录方式：',
    'if_administrator' => '您是管理员吗？',
    'sign_in_here' => '您可以不使用 SSO 登录',
    'or_continue_with' => '您也可以使用：',
    'password_login_and_webauthn_are_disabled' => '密码与 WebAuthn 登录已禁用。',
    'sign_in_using_sso' => '选择一个 SSO 渠道来登录：',
    'no_provider' => '暂无选项',
    'no_sso_provider_or_provider_is_missing' => '找不到提供方？',
    'see_how_to_enable_sso' => '看看如何启用提供方',
    'sign_in_using_security_device' => '使用安全设备登录',
    'login_and_password' => '用户名和密码',
    'register' => '注册',
    'welcome_to_2fauth' => '欢迎使用 2FAuth',
    'autolock_triggered' => '自动锁定已触发',
    'autolock_triggered_punchline' => '自动锁定已触发，您已被自动退出登录。',
    'already_authenticated' => '您已登录，请先退出登录。',
    'authentication' => '身份认证',
    'maybe_later' => '以后再说',
    'user_account_controlled_by_proxy' => '此账户由身份代理提供。<br />请在身份代理中进行管理。',
    'auth_handled_by_proxy' => '账户验证已被身份代理接管，下列设置已被禁用。<br />请在身份代理中进行管理。',
    'sso_only_x_settings_are_disabled' => '仅允许通过 SSO 进行身份鉴权，:auth_method 已被禁用',
    'confirm' => [
        'logout' => '您确定要退出吗？',
        'revoke_device' => '您确定要删除此设备吗？',
        'delete_account' => '您确定要删除您的账户吗？',
    ],
    'webauthn' => [
        'security_device' => '安全设备',
        'security_devices' => '安全设备',
        'security_devices_legend' => '您可以用来登录 2FAuth 的认证设备，例如安全密钥 (如 Yubikey) 或具有生物识别能力的智能手机 (如 Apple FaceID / TouchID)',
        'enhance_security_using_webauthn' => '您可以启用 WebAuthn 身份验证来增强您 2FAuth 账户的安全性。<br /><br />
WebAuthn 允许您使用受信任的设备 (如 Yubikeys 或具有生物识别功能的智能手机) 来安全、快捷地进行登录验证。',
        'use_security_device_to_sign_in' => '若要使用安全设备进行身份验证，请根据验证类型，插入您的密钥设备，或取下口罩 (或手套) 进行生物验证。',
        'lost_your_device' => '设备已遗失？',
        'recover_your_account' => '恢复您的账号',
        'account_recovery' => '恢复账号',
        'recovery_punchline' => '2FAuth 将向此邮箱发送账户恢复链接。请点击邮件中的链接，并跟随引导操作。<br /><br />为了保证安全性，请务必在您自己的设备上进行操作。',
        'send_recovery_link' => '发送恢复链接',
        'account_recovery_email_sent' => '账号恢复邮件已发送！',
        'disable_all_security_devices' => '禁用所有安全设备',
        'disable_all_security_devices_help' => '此操作将吊销您所有的安全设备。若某个设备已遗失或不再安全，请点击此选项。',
        'register_a_new_device' => '注册新设备',
        'register_a_device' => '注册设备',
        'device_successfully_registered' => '设备注册成功',
        'device_revoked' => '设备吊销成功',
        'revoking_a_device_is_permanent' => '设备吊销后无法恢复',
        'recover_account_instructions' => '为了恢复您的账户，2FAuth 将会重置部分 WebAuthn 相关的设置，以便您可以使用邮箱和密码进行登录。',
        'invalid_recovery_token' => '恢复令牌无效',
        'webauthn_login_disabled' => 'Webauthn 登录已被禁用',
        'invalid_reset_token' => '此密码重置令牌无效',
        'rename_device' => '重命名设备',
        'my_device' => '我的设备',
        'unknown_device' => '未知设备',
        'use_webauthn_only' => [
            'label' => '仅允许 WebAuthn',
            'help' => '将 WebAuthn 设为 2FAuth 的唯一鉴权方式。若要发挥 WebAuth 的最佳安全性，推荐启用此选项。<br /><br />
                当设备丢失时， 您可以重置此选项来恢复您的账户，并使用您的邮箱与密码来登录。<br /><br />
                请注意！ 启用此选项后，2FAuth 不会禁用邮箱和密码的登录界面，但如果尝试使用邮箱登录，则永远会提示 “身份验证失败”。'
        ],
        'need_a_security_device_to_enable_options' => '若要启用下列选项，请添加 1 个 WebAuthn 设备。',
        'options' => '选项',
    ],
    'forms' => [
        'name' => '用户名',
        'login' => '登录',
        'webauthn_login' => '使用 WebAuthn 登录',
        'sso_login' => 'SSO 登录',
        'email' => '邮箱',
        'password' => '密码',
        'reveal_password' => '显示密码',
        'hide_password' => '隐藏密码',
        'confirm_password' => '再次确认密码',
        'new_password' => '新密码',
        'confirm_new_password' => '再次确认新密码',
        'dont_have_account_yet' => '还没有账户？',
        'already_register' => '已经注册？',
        'authentication_failed' => '验证失败',
        'forgot_your_password' => '忘记密码？',
        'request_password_reset' => '重置密码',
        'reset_your_password' => '重置您的密码',
        'reset_password' => '重置密码',
        'disabled_in_demo' => '此功能在演示模式下被禁用',
        'sso_only_form_restricted_to_admin' => '普通用户必须使用 SSO 登录，仅有管理员可选用其他登录选项。',
        'new_password' => '新密码',
        'current_password' => [
            'label' => '当前密码',
            'help' => '为了确认您是此账户的所有人，请输入当前的密码'
        ],
        'change_password' => '修改密码',
        'send_password_reset_link' => '发送密码重置链接',
        'password_successfully_reset' => '密码重置成功',
        'edit_account' => '编辑账号',
        'profile_saved' => '个人资料更新成功！',
        'welcome_to_demo_app_use_those_credentials' => '欢迎来到 2FAuth 的演示站点。<br><br>您可以使用邮箱 <strong>demo@2fauth.app</strong> 和密码 <strong>demo</strong> 来登录。',
        'welcome_to_testing_app_use_those_credentials' => '欢迎来到 2FAuth 的测试节点。<br><br>您可以使用邮箱 <strong>testing@2fauth.app</strong> 和密码 <strong>password</strong> 来登录。',
        'register_punchline' => '欢迎使用 <b>2FAuth</b>。<br/>您需要一个账号才能继续，请先完成注册。',
        'reset_punchline' => '2FAuth 将向此邮箱发送密码重置链接，请点击邮件中的链接设置新密码。',
        'name_this_device' => '命名此设备',
        'delete_account' => '删除账户',
        'delete_your_account' => '删除我的账户',
        'delete_your_account_and_reset_all_data' => '您所有的 2FA 数据将与此账户被一同删除，此操作无法恢复。',
        'reset_your_password_to_delete_your_account' => '如果您此前使用的是 SSO 登录，请在退出登录后，使用 “重置密码” 功能设置密码。',
        'deleting_2fauth_account_does_not_impact_provider' => '删除此 2FAuth 账户不会影响您的外部 SSO 账户。',
        'user_account_successfully_deleted' => '账户删除成功',
        'has_lower_case' => '包含小写字母',
        'has_upper_case' => '包含大写字母',
        'has_special_char' => '包含特殊字符',
        'has_number' => '包含数字',
        'is_long_enough' => '至少 8 位',
        'mandatory_rules' => '必须',
        'optional_rules_you_should_follow' => '建议 (更安全)',
        'caps_lock_is_on' => '大写锁定已打开',
    ],
    'sso_providers' => [
        'unknown' => '未知',
        'github' => 'Github',
        'openid' => 'OpenID'
    ]
];
