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
    'failed' => '用户名或密码错误',
    'password' => '提供的密码不正确',
    'throttle' => '您尝试的登录次数过多，请 :seconds 秒后再试。',

    // 2FAuth
    'sign_out' => '退出',
    'sign_in' => '登录',
    'sign_in_using' => '登录使用',
    'sign_in_using_security_device' => '使用安全设备登录',
    'login_and_password' => '使用密码登录',
    'register' => '注册',
    'welcome_back_x' => '欢迎回来，{0}!',
    'autolock_triggered' => '自动锁定已触发',
    'autolock_triggered_punchline' => '自动锁定已触发。您已被自动断开连接。',
    'change_autolock_in_settings' => '您可以在“设置 > 选项”中更改自动锁定的行为。',
    'already_authenticated' => '已验证',
    'authentication' => '身份认证',
    'maybe_later' => '以后再说',
    'user_account_controlled_by_proxy' => '用户帐户由身份验证代理提供。<br />在代理管理帐户。',
    'auth_handled_by_proxy' => '身份验证由代理处理，下面的设置被禁用。<br />在代理管理身份验证。',
    'confirm' => [
        'logout' => '确定要退出吗？',
        'revoke_device' => '你确定要删除此设备？',
        'delete_account' => '您确定要删除您的账户?',
    ],
    'webauthn' => [
        'security_device' => '安全设备',
        'security_devices' => '安全设备',
        'security_devices_legend' => '您可以用来登录2FAuth的认证设备，例如安全密钥(如Yubikey)或具有生物识别能力的智能手机(如Apple Face Id/TouchId)',
        'enhance_security_using_webauthn' => '您可以通过启用 WebAuthn 身份验证来增强您的2FAuth 账户的安全性。<br /><br />
WebAuthn允许您使用受信任的设备 (如Yubikeys 或具有生物识别能力的智能手机) 快速和更安全地登录。',
        'use_security_device_to_sign_in' => '准备好使用您的（一个）安全设备进行身份验证。请插入您的密钥，移除口罩或手套等。',
        'lost_your_device' => '设备丢失？',
        'recover_your_account' => '恢复您的账号',
        'account_recovery' => '恢复账号',
        'recovery_punchline' => '2FAuth 将向您发送恢复链接到此电子邮件地址。点击收到电子邮件中的链接注册新的安全设备。<br /><br />确保在您拥有的设备上打开电子邮件。',
        'send_recovery_link' => '发送恢复链接',
        'account_recovery_email_sent' => '帐号恢复邮件已发送！',
        'disable_all_other_devices' => '禁用除此设备以外的所有其他设备',
        'register_a_new_device' => '注册新设备',
        'register_a_device' => '注册设备',
        'device_successfully_registered' => '成功注册设备。',
        'device_revoked' => '成功注销设备。',
        'revoking_a_device_is_permanent' => '注销设备是永久性的',
        'recover_account_instructions' => '点击下面的按钮注册一个新的安全设备来恢复您的帐户。只需遵循您的浏览器说明。',
        'invalid_recovery_token' => '无效的恢复密钥',
        'rename_device' => '重命名设备',
        'my_device' => '我的设备',
        'unknown_device' => '未知设备',
        'use_webauthn_only' => [
            'label' => '仅使用 WebAuthn',
            'help' => '使WebAuth成为唯一可用的登录 2FAuth的方法。这是推荐的设置，以利用WebAuth增强安全性。<br />如果设备丢失，您总是能够注册一个新的安全设备来恢复您的帐户。'
        ],
        'need_a_security_device_to_enable_options' => '设置至少一个设备以启用这些选项',
        'use_webauthn_as_default' => [
            'label' => '使用 WebAuthn 作为默认登录方式',
            'help' => '设置 2FAuth 首先提出WebAuth身份验证。然后用户名/密码作为备用解决方案。<br />如果您只使用WebAuthn，这将不会产生任何效果。'
        ],
    ],
    'forms' => [
        'name' => '用户名',
        'login' => '登录',
        'webauthn_login' => '使用 WebAuthn 登录',
        'email' => '电子邮件',
        'password' => '密码',
        'reveal_password' => '显示密码',
        'hide_password' => '隐藏密码',
        'confirm_password' => '确认密码',
        'confirm_new_password' => '确认新密码',
        'dont_have_account_yet' => '还没有账号?',
        'already_register' => '已经注册？',
        'authentication_failed' => '验证失败',
        'forgot_your_password' => '忘记密码？',
        'request_password_reset' => '重置',
        'reset_your_password' => '重置密码',
        'reset_password' => '重置密码',
        'disabled_in_demo' => '此功能将在演示模式下禁用。',
        'new_password' => '新密码',
        'current_password' => [
            'label' => '当前密码',
            'help' => '填写您当前的密码以确认是你'
        ],
        'change_password' => '修改密码',
        'send_password_reset_link' => '发送密码重置链接',
        'password_successfully_changed' => '密码修改成功',
        'edit_account' => '编辑账户',
        'profile_saved' => '帐户资料更新成功',
        'welcome_to_demo_app_use_those_credentials' => '欢迎访问 2FAuth 的演示。<br><br>您可以使用电子邮件地址 <strong>demo@2fauth.app</strong> 和密码 <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => '欢迎访问 2FAuth 的测试实例。<br><br>您可以使用电子邮件地址 <strong>testing@2fauth.app</strong> 和密码 <strong>password</strong>',
        'register_punchline' => '欢迎使用 <b>2FAuth</b>。<br/>您需要一个帐户才能继续，请注册自己。',
        'reset_punchline' => '2FAuth 将向您发送密码重置链接到此地址。点击收到的电子邮件中的链接设置新密码。',
        'name_this_device' => '命名此设备',
        'delete_account' => '删除账户',
        'delete_your_account' => '删除您的帐户',
        'delete_your_account_and_reset_all_data' => '这将重置 2FAuth。您的用户帐户以及所有 2FA 数据都将被删除，没有回头路。',
        'user_account_successfully_deleted' => '帐户已成功删除',
        'has_lower_case' => '包含小写',
        'has_upper_case' => '包含大写',
        'has_special_char' => '包含特殊字符',
        'has_number' => '包含数字',
        'is_long_enough' => '至少8个字符',
        'mandatory_rules' => '必选项',
        'optional_rules_you_should_follow' => '(强烈) 推荐',
        'caps_lock_is_on' => '大写锁定已开启',
    ],

];
