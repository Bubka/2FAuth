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
    'preferences' => '偏好设置',
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
    'account_linked_to_sso_x_provider' => '您当前是通过 :provider 登录的，因此无法在此处更改信息，请回到 :provider 进行操作。',
    'general' => '通用',
    'security' => '安全',
    'notifications' => '通知',
    'profile' => '配置文件',
    'change_password' => '更改密码',
    'personal_access_tokens' => '个人访问令牌',
    'token_legend' => '任何应用都能够通过个人访问令牌来与 2Fauth API 进行鉴权。您需要在第三方应用的请求头中，提供此令牌作为 Bearer 令牌。',
    'generate_new_token' => '生成新令牌',
    'revoke' => '吊销',
    'token_revoked' => '已成功吊销令牌',
    'revoking_a_token_is_permanent' => '令牌吊销后无法恢复',
    'confirm' => [
        'revoke' => '您确定要吊销此令牌吗？',
    ],
    'make_sure_copy_token' => '请确保您已复制个人访问令牌！此令牌仅展示这一次。',
    'data_input' => '数据录入',
    'forms' => [
        'edit_settings' => '编辑设置',
        'setting_saved' => '设置已保存',
        'new_token' => '新建令牌',
        'some_translation_are_missing' => '发现有词条缺少翻译吗？',
        'help_translate_2fauth' => '协助翻译 2FAuth！',
        'language' => [
            'label' => '语言',
            'help' => '2FAuth 用户界面的显示语言。以下仅列出完成翻译的语言，请选择的一个语言来覆盖当前的设置。'
        ],
        'timezone' => [
            'label' => '时区',
            'help' => '设置本 App 中所有日期与时间的时区'
        ],
        'show_otp_as_dot' => [
            'label' => '隐藏 <abbr title="One-Time Password">OTP</abbr> 验证码',
            'help' => '使用星号来遮挡明文验证码。启用此功能不会影响复制和粘贴功能。'
        ],
        'reveal_dotted_otp' => [
            'label' => '显示被星号遮挡的 <abbr title="One-Time Password">OTP</abbr> 验证码',
            'help' => '临时允许验证码以明文显示'
        ],
        'close_otp_on_copy' => [
            'label' => '复制后关闭 <abbr title="One-Time Password">OTP</abbr> 验证码',
            'help' => '点击某个验证码即可复制并隐藏显示'
        ],
        'auto_close_timeout' => [
            'label' => '自动关闭 <abbr title="One-Time Password">OTP</abbr> 验证码',
            'help' => '超时后自动隐藏明文显示的验证码。如果您忘记退出验证码界面，此功能可以避免非必要的验证码刷新请求。'
        ],
        'clear_search_on_copy' => [
            'label' => '复制后清空搜索框',
            'help' => '复制验证码后立即清空搜索框'
        ],
        'sort_case_sensitive' => [
            'label' => '按大小写排序',
            'help' => '选中时，强制 “排序” 功能以大小写敏感的方式对账户进行排序'
        ],
        'copy_otp_on_display' => [
            'label' => '当 <abbr title="One-Time Password">OTP</abbr> 显示时复制',
            'help' => '当验证码显示时立即复制。由于浏览器的限制，仅能复制第一个 <abbr title="Time-based One-Time Password">TOTP</abbr> 验证码，后续刷新的无法自动复制。'
        ],
        'use_basic_qrcode_reader' => [
            'label' => '使用简版二维码扫描器',
            'help' => '如果你在扫描二维码时遇到问题，此选项可以切换到更简单但更可靠的二维码扫描器'
        ],
        'display_mode' => [
            'label' => '显示模式',
            'help' => '选择以列表或网格的方式显示所有的账户'
        ],
        'password_format' => [
            'label' => '密码格式',
            'help' => '分段显示验证码，提高可读性并且便于记忆'
        ],
        'pair' => '两位一组',
        'pair_legend' => '以两位为一组进行分隔',
        'trio_legend' => '以三位为一组进行分隔',
        'half_legend' => '平均拆分位两组',
        'trio' => '三位一组',
        'half' => '对半分组',
        'grid' => '网格',
        'list' => '列表',
        'theme' => [
            'label' => '主题',
            'help' => '强制一个特定主题，或跟随系统 / 浏览器的设置'
        ],
        'light' => '亮色',
        'dark' => '暗色',
        'automatic' => '自动',
        'show_accounts_icons' => [
            'label' => '显示图标',
            'help' => 'Show account icons in the main view'
        ],
        'get_official_icons' => [
            'label' => '获取官方图标',
            'help' => '在添加账户时，尝试获取 2FA 提供方的官方图标'
        ],
        'auto_lock' => [
            'label' => '自动锁定',
            'help' => '在不活跃时自动退出登录。当使用身份代理，或没有配置自定义注销 URL 时无效。'
        ],
        'default_group' => [
            'label' => '默认分组',
            'help' => '新创建的账户所关联的分组',
        ],
        'view_default_group_on_copy' => [
            'label' => '在复制后显示默认分组',
            'help' => '复制 OTP 验证码后总是返回到默认分组',
        ],
        'auto_save_qrcoded_account' => [
            'label' => '自动保存账户',
            'help' => '扫描或上传二维码后，新账户会被自动录入，无需点击 ”保存“ 按钮。',
        ],
        'useDirectCapture' => [
            'label' => '直接录入',
            'help' => '决定是否要在您录入时弹出录入模式选单，或者直接使用默认的录入模式',
        ],
        'defaultCaptureMode' => [
            'label' => '默认录入模式',
            'help' => '直接录入模式启用时所使用的默认录入模式',
        ],
        'remember_active_group' => [
            'label' => '记住分组筛选器',
            'help' => '记住上次筛选出的分组，并在下次访问时展示',
        ],
        'otp_generation' => [
            'label' => '显示验证码',
            'help' => '设置 <abbr title="One-Time Passwords">OTPs</abbr> 验证码的显示方式和时间。<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => '来自新设备时',
            'help' => '当新设备首次登录时发送邮件通知'
        ],
        'notify_on_failed_login' => [
            'label' => '登录失败时',
            'help' => '每次登录失败时都发送邮件通知'
        ],
        'show_email_in_footer' => [
            'label' => 'Show email in footer',
            'help' => 'Display the logged-in user\'s email in the footer instead of direct navigation links. The links are then available in a menu behind a click/tap on the email address.'
        ],
        'otp_generation_on_request' => '点击 / 单击账户后',
        'otp_generation_on_request_legend' => '在独立页面中显示',
        'otp_generation_on_request_title' => '点击账户后，在独立页面中打开并获取验证码',
        'otp_generation_on_home' => '始终',
        'otp_generation_on_home_legend' => '全部在主页中显示',
        'otp_generation_on_home_title' => '所有验证码都在主页中显示，不做任何操作',
        'never' => '从不',
        'on_otp_copy' => '复制验证码后',
        '1_minutes' => '1 分钟后',
        '2_minutes' => '2 分钟后',
        '5_minutes' => '5 分钟后',
        '10_minutes' => '10 分钟后',
        '15_minutes' => '15 分钟后',
        '30_minutes' => '30 分钟后',
        '1_hour' => '1 小时后',
        '1_day' => '1 天后',
        'livescan' => '扫描二维码',
        'upload' => '上传二维码',
        'advanced_form' => '高级表单',
    ],

];