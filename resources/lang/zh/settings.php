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

    'settings' => '设置',
    'preferences' => '偏好',
    'account' => '账户',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => '令牌',
    'options' => '选项',
    'user_preferences' => '用户偏好',
    'admin_settings' => '管理员设置',
    'confirm' => [

    ],
    'you_are_administrator' => '您是管理员',
    'account_linked_to_sso_x_provider' => '您通过SSO使用您的 :provider 帐户登录。您的信息不能在这里更改，只能在 :provider 。',
    'general' => '常规',
    'security' => '安全',
    'profile' => '配置文件',
    'change_password' => '更改密码',
    'personal_access_tokens' => '个人访问令牌',
    'token_legend' => '个人访问令牌允许任何应用访问 2Fauth API。您应该在第三方应用授权请求头中提供访问令牌作为一个 Bearer 令牌。',
    'generate_new_token' => '生成新令牌',
    'revoke' => '吊销',
    'token_revoked' => '已成功吊销令牌',
    'revoking_a_token_is_permanent' => '吊销令牌是永久的',
    'confirm' => [
        'revoke' => '你确定要吊销此令牌？',
    ],
    'make_sure_copy_token' => '请确保您已复制个人访问令牌。令牌将不再显示。',
    'data_input' => '数据输入',
    'forms' => [
        'edit_settings' => '编辑设置',
        'setting_saved' => '设置已保存',
        'new_token' => '新建令牌',
        'some_translation_are_missing' => '使用浏览器偏好时缺少一些翻译？',
        'help_translate_2fauth' => '帮助翻译 2FAuth',
        'language' => [
            'label' => '语言',
            'help' => '用来翻译 2FAuth 用户界面的语言。列出的语言已完成翻译，请设置你选择的语言来覆盖你的浏览器偏好。'
        ],
        'show_otp_as_dot' => [
            'label' => '用 *** 来显示生成的一次性密码',
            'help' => '将生成的密码替换为 *** 以确保保密。不影响复制和粘贴功能。'
        ],
        'reveal_dotted_otp' => [
            'label' => '显示被 *** 隐藏的 <abbr title="One-Time Password">OTP</abbr>',
            'help' => '允许临时显示被 *** 隐藏的密码'
        ],
        'close_otp_on_copy' => [
            'label' => '复制后关闭 <abbr title="One-Time Password">OTP</abbr>',
            'help' => '点击生成的密码进行复制，并自动将其从屏幕上隐藏'
        ],
        'clear_search_on_copy' => [
            'label' => 'Clear Search on copy',
            'help' => 'Empty the Search box right after a code has been copied to the clipboard'
        ],
        'copy_otp_on_display' => [
            'label' => '在显示时复制 <abbr title="One-Time Password">OTP</abbr>',
            'help' => '在屏幕显示后自动复制生成的密码。 由于浏览器限制，只有第一个 <abbr title="Time-based One-Time Password">TOTP</abbr> 密码将被复制，而不是更新后的'
        ],
        'use_basic_qrcode_reader' => [
            'label' => '使用基本二维码读取器',
            'help' => '如果你在扫描二维码时遇到问题，这个选项可以切换到更基本但更可靠的二维码阅读器'
        ],
        'display_mode' => [
            'label' => '显示模式',
            'help' => '选择将账户以列表或网格的方式进行展示'
        ],
        'password_format' => [
            'label' => '密码格式',
            'help' => '分组显示密码，提高可读性并且便于记忆'
        ],
        'pair' => '两位数分组',
        'pair_legend' => '以两位数分组拆分数字',
        'trio_legend' => '以三位数分组拆分数字',
        'half_legend' => '将数字拆分为两个相等数位的分组',
        'trio' => '三位数分组',
        'half' => '两个相等数位的分组',
        'grid' => '网格',
        'list' => '列表',
        'theme' => [
            'label' => '主题',
            'help' => '强制一个特定主题或应用系统/浏览器首选项中定义的主题'
        ],
        'light' => '亮色主题',
        'dark' => '暗色主题',
        'automatic' => '自动检测',
        'show_accounts_icons' => [
            'label' => '显示图标',
            'help' => '在主视图中显示应用图标'
        ],
        'get_official_icons' => [
            'label' => '获取官方图标',
            'help' => '(尝试) 在添加账户时获取两步验证发行者的官方图标'
        ],
        'auto_lock' => [
            'label' => '自动锁定',
            'help' => '在没有活动的情况下自动登出用户。当使用认证代理或没有指定自定义注销 URL 时无效。'
        ],
        'default_group' => [
            'label' => '默认分组',
            'help' => '新创建的账户所关联的分组',
        ],
        'view_default_group_on_copy' => [
            'label' => 'View default group on copy',
            'help' => 'Always return to the default group when an OTP is copied',
        ],
        'useDirectCapture' => [
            'label' => '直接输入',
            'help' => '选择您是否想要在可用的输入模式中选择输入模式，或者直接使用默认输入模式',
        ],
        'defaultCaptureMode' => [
            'label' => '默认输入模式',
            'help' => '直接输入选项开启时使用的默认输入模式',
        ],
        'remember_active_group' => [
            'label' => '记住组筛选器',
            'help' => '保存最后应用的组过滤器并在下次访问时还原它',
        ],
        'otp_generation' => [
            'label' => '显示密码',
            'help' => '设置 <abbr title="One-Time Passwords">OTPs</abbr> 的显示方式和时间。<br/>',
        ],
        'otp_generation_on_request' => '点击/单击账户后',
        'otp_generation_on_request_legend' => '在专用视图中显示密码',
        'otp_generation_on_request_title' => '单击帐户在专用视图中获取密码',
        'otp_generation_on_home' => '始终',
        'otp_generation_on_home_legend' => '主视图中显示所有密码',
        'otp_generation_on_home_title' => '在主视图中显示所有密码，无需任何操作',
        'never' => '从不',
        'on_otp_copy' => '在复制安全代码后',
        '1_minutes' => '1分钟后',
        '5_minutes' => '5分钟后',
        '10_minutes' => '10 分钟后',
        '15_minutes' => '15分钟后',
        '30_minutes' => '30 分钟后',
        '1_hour' => '1小时后',
        '1_day' => '1天后',
        'livescan' => '扫描二维码',
        'upload' => '上传二维码',
        'advanced_form' => '高级表单',
    ],

];