<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'admin' => '管理员',
    'app_setup' => '应用设置',
    'registrations' => '注册',
    'users' => '用户',
    'users_legend' => '管理在您的实例上注册的用户或创建新的用户。',
    'admin_settings' => '管理员设置',
    'create_new_user' => '创建新用户',
    'new_user' => '新用户',
    'search_user_placeholder' => '用户名，电子邮件...',
    'quick_filters_colons' => '快速筛选:',
    'user_created' => '用户创建成功',
    'confirm' => [
        'delete_user' => '您确定要删除这个用户吗？没有回头路。',
        'request_password_reset' => '您确定要重置此用户的密码吗？',
        'purge_password_reset_request' => '您确定要清除请求吗？',
        'delete_account' => '您确定要删除该用户吗？',
        'edit_own_account' => '这是您自己的帐户。您确定吗？',
        'change_admin_role' => '这将会对此用户的权限产生重大影响。您确定吗？',
        'demote_own_account' => '您将不再是管理员。真的确定吗？'
    ],
    'logs' => '日志',
    'administration_legend' => '以下设置是全局设置，适用于所有用户。',
    'user_management' => '用户管理',
    'oauth_provider' => 'OAuth 提供者',
    'account_bound_to_x_via_oauth' => '此帐户通过 OAuth 绑定到 :provider 帐户',
    'last_seen_on_date' => '最后活跃：:date',
    'registered_on_date' => '注册于 :date',
    'updated_on_date' => '更新于 :date',
    'access' => '访问',
    'password_requested_on_t' => '存在此用户的密码重置请求 (在 :datetime 发出的请求) 意味着该用户尚未更改其密码，但他收到的链接仍然有效。 此请求可能来自用户本人或管理员。',
    'password_request_expired' => '存在此用户的密码重置请求但已过期，意味着用户并未及时更改密码。此请求可能来自用户本人或管理员。',
    'resend_email' => '重新发送电子邮件',
    'resend_email_title' => '重新发送密码重置邮件给用户',
    'resend_email_help' => '使用 <b>重新发送电子邮件</b> 向用户发送新密码重置邮件，以便他可以设置新密码。 这将保留当前密码，且之前的请求都将被撤销。',
    'reset_password' => '重置密码',
    'reset_password_help' => '使用 <b>重置密码</b> 来强制重置密码 (这将会以临时密码覆盖用户当前密码)，以便用户可以设置新密码。之前的请求都将被撤销。',
    'reset_password_title' => '重置用户的密码',
    'password_successfully_reset' => '密码重置成功',
    'user_has_x_active_pat' => ':count 个有效的令牌',
    'user_has_x_security_devices' => ':count 个安全设备 (安全钥匙)',
    'revoke_all_pat_for_user' => '吊销用户的所有令牌',
    'revoke_all_devices_for_user' => '吊销用户的所有安全设备',
    'danger_zone' => '危险选项',
    'delete_this_user_legend' => '用户帐户及其所有2FA 数据将被删除。',
    'this_is_not_soft_delete' => '这不是软删除，没有退路。',
    'delete_this_user' => '删除这个用户',
    'user_role_updated' => '用户角色已更新',
    'pats_succesfully_revoked' => '用户的令牌已成功吊销。',
    'security_devices_succesfully_revoked' => '用户的安全设备已成功吊销。',
    'variables' => '变量',
    'cache_cleared' => '已清除缓存',
    'cache_optimized' => '已优化缓存',
    'check_now' => '立即检查',
    'view_on_github' => '在 GitHub 上查看',
    'x_is_available' => '新版本 :version 可用！',
    'forms' => [
        'use_encryption' => [
            'label' => '保护敏感数据',
            'help' => '敏感数据、2FA 秘钥和电子邮件会被加密存储在数据库中。请务必备份您在 .env 中设置的 APP_KEY 的值(或备份整个文件)。没有此密钥将无法解码已加密的数据。',
        ],
        'restrict_registration' => [
            'label' => '限制注册',
            'help' => '只允许有限范围的电子邮件地址进行注册。这两条规则都可以同时使用。这对通过SSO进行注册没有影响。',
        ],
        'restrict_list' => [
            'label' => '过滤列表',
            'help' => '此列表中的电子邮件将被允许注册。用管道分隔("|")',
        ],
        'restrict_rule' => [
            'label' => '过滤规则',
            'help' => '与此正则表达式匹配的电子邮件将被允许注册',
        ],
        'disable_registration' => [
            'label' => '禁用注册',
            'help' => '防止新用户注册。除非被覆盖(见下文)，否则这也会影响到SSO，所以新用户将无法通过 SSO 登录',
        ],
        'enable_sso' => [
            'label' => '启用单点登录 (SSO)',
            'help' => '允许访问者通过单点登录方案使用外部ID进行身份验证',
        ],
        'keep_sso_registration_enabled' => [
            'label' => '保持启用 SSO 注册',
            'help' => '在注册已禁用时允许新用户通过 SSO 登录',
        ],
        'is_admin' => [
            'label' => '管理员',
            'help' => '授予用户管理员权限。管理员有权管理整个应用，如: 设置和其他用户，但不能生成不属于他们的2FA 密码。'
        ],
        'test_email' => [
            'label' => '电子邮件配置测试',
            'help' => '发送测试邮件来控制您的实例的电子邮件配置。 有一个正常的工作配置是很必要的，否则用户将无法请求重置密码。',
            'email_will_be_send_to_x' => '电子邮件将被发送到 <span class="is-family-code has-text-info">:email</span>',
        ],
        'cache_management' => [
            'label' => '缓存管理',
            'help' => '有时缓存需要清除，例如在更改环境变量或更新后。您可以在此处这样做。',
        ]
    ],

];