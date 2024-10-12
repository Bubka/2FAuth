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
    'auth' => '鉴权',
    'registrations' => '注册',
    'users' => '用户',
    'users_legend' => '管理此实例上的已注册用户，或创建新的用户。',
    'admin_settings' => '管理员设置',
    'create_new_user' => '创建新用户',
    'new_user' => '新用户',
    'search_user_placeholder' => '用户名、邮箱…',
    'quick_filters_colons' => '快速筛选：',
    'user_created' => '用户创建成功',
    'confirm' => [
        'delete_user' => '您确定要删除此用户吗？此操作无法恢复。',
        'request_password_reset' => '您确定要重置此用户的密码吗？',
        'purge_password_reset_request' => '您确定要撤销之前的请求吗？',
        'delete_account' => '您确定要删除此用户吗？',
        'edit_own_account' => '这是您自己的账户，确定要继续吗？',
        'change_admin_role' => '这将严重影响此用户的权限，确定要继续吗？',
        'demote_own_account' => '您将失去管理员权限，确定要继续吗？'
    ],
    'logs' => '日志',
    'administration_legend' => '下列设置将在全局范围内生效，且影响所有用户。',
    'user_management' => '用户管理',
    'oauth_provider' => 'OAuth 提供方',
    'account_bound_to_x_via_oauth' => '此账户通过 OAuth 与 :provider 账户绑定',
    'last_seen_on_date' => '最近活跃：:date',
    'registered_on_date' => '注册于 :date',
    'updated_on_date' => '更新于 :date',
    'access' => '访问',
    'password_requested_on_t' => '此用户已申请过密码重置 (申请时间 :datetime)，但未完成重置密码的操作，且此前发送的密码重置链接仍然有效。此请求可能是由用户自己或其他管理员发起的。',
    'password_request_expired' => '此用户的密码重置请求已超时，意味着此用户未在有效期内完成密码重置。此请求可能是由用户自己或其他管理员发起的。',
    'resend_email' => '重新发送邮件',
    'resend_email_title' => '重新发送密码重置邮件给用户',
    'resend_email_help' => '使用 <b>重新发送邮件</b> 来向用户发送新的密码重置邮件，以便用户可以设置新的密码。 此操作不会更改当前的密码，且之前的重置申请都将被撤销。',
    'reset_password' => '重置密码',
    'reset_password_help' => '使用 <b>重置密码</b> 来强制重置密码 (将会使用临时密码来覆盖用户当前的密码)，以便用户可以设置新密码。此操作会撤销之前的重置申请。',
    'reset_password_title' => '重置用户密码',
    'password_successfully_reset' => '密码重置成功',
    'user_has_x_active_pat' => ':count 个有效的令牌',
    'user_has_x_security_devices' => ':count 个安全设备 (通行密钥)',
    'revoke_all_pat_for_user' => '吊销用户的所有令牌',
    'revoke_all_devices_for_user' => '吊销用户的所有安全设备',
    'danger_zone' => '危险选项',
    'delete_this_user_legend' => '此用户所有的 2FA 数据将与此账户被一同删除。',
    'this_is_not_soft_delete' => '数据将被永久删除，此操作无法恢复。',
    'delete_this_user' => '删除此用户',
    'user_role_updated' => '用户权限已更新',
    'pats_succesfully_revoked' => '用户的令牌已成功吊销。',
    'security_devices_succesfully_revoked' => '用户的安全设备已成功吊销。',
    'variables' => '环境变量',
    'cache_cleared' => '缓存已清除',
    'cache_optimized' => '缓存已优化',
    'check_now' => '立即检查',
    'view_on_github' => '在 GitHub 上查看',
    'x_is_available' => '新版本 :version 可用！',
    'successful_login_on' => '成功登录于 <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => '成功退出于 <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => '登录失败于 <span class="light-or-darker">:login_at</span>',
    'viewed_on' => '查看 <span class="light-or-darker">:login_at</span>',
    'last_accesses' => '最近访问',
    'see_full_log' => '显示完整日志',
    'browser_on_platform' => ':platform 的 :browser',
    'access_log_has_more_entries' => '访问日志包含更多条目。',
    'access_log_legend_for_user' => '用户 :username 的完整访问日志',
    'show_last_month_log' => '显示上个月的条目',
    'show_three_months_log' => '显示最近 3 个月的条目',
    'show_six_months_log' => '显示最近 6 个月的条目',
    'show_one_year_log' => '显示上一年的条目',
    'sort_by_date_asc' => '倒序显示最近访问',
    'sort_by_date_desc' => '顺序显示最近访问',
    'single_sign_on' => '单点登录（SSO）',
    'forms' => [
        'use_encryption' => [
            'label' => '保护敏感数据',
            'help' => '敏感数据、2FA 秘钥和邮箱地址会以加密形式储存在数据库中。请务必备份您在 .env 中设置的 APP_KEY 的值 (或备份整个 .env 文件)。如果此密钥丢失，那么已加密的数据将无法解密。',
        ],
        'restrict_registration' => [
            'label' => '限制注册',
            'help' => '只允许范围内的邮箱地址进行注册，下列的两条规则可以同时使用。SSO 注册流程不受此限制的影响。',
        ],
        'restrict_list' => [
            'label' => '过滤列表',
            'help' => '仅允许此列表中的邮箱进行注册，用管道符 "|" 来分隔多个地址。',
        ],
        'restrict_rule' => [
            'label' => '过滤规则',
            'help' => '仅允许匹配正则表达式的邮箱地址进行注册',
        ],
        'disable_registration' => [
            'label' => '关闭注册',
            'help' => '防止新用户注册。除非被覆盖 (见下文)。此设置也会影响到 SSO 的注册流程，所以新用户将无法通过 SSO 登录',
        ],
        'enable_sso' => [
            'label' => '启用 SSO',
            'help' => '允许访客通过单点登录的方式，使用外部 ID 进行身份验证',
        ],
        'use_sso_only' => [
            'label' => '仅使用 SSO',
            'help' => '仅允许通过 SSO。启用此功能后，普通用户将无法使用密码和 WebAuthn 登录到 2FAuth，但管理员不受此限制的影响。',
        ],
        'keep_sso_registration_enabled' => [
            'label' => '保持启用 SSO 注册',
            'help' => '在注册已禁用时允许新用户通过 SSO 登录',
        ],
        'is_admin' => [
            'label' => '管理员',
            'help' => '授予用户管理员权限。管理员有权管理整个应用，如调整 “设置” 和管理其他用户，但管理员无法对不属于他们的 2FA 创建密码。'
        ],
        'test_email' => [
            'label' => '测试送信配置',
            'help' => '发送测试邮件来验证送信配置是否正确。如果发送邮件的配置不正确，用户将无法请求重置密码。',
            'email_will_be_send_to_x' => '邮件将被发送到 <span class="is-family-code has-text-info">:email</span>',
        ],
        'health_endpoint' => [
            'label' => '节点健康检查',
            'help' => '您可以访问此 URL 来检查此 2FAuth 实例的健康状况。您可以在 Docker 的 HEALTHCKECK，或 Kubernetes 的 HTTPS Liveness 探针中使用此 URL 来进行健康检查。',
        ],
        'cache_management' => [
            'label' => '缓存管理',
            'help' => '某些情况下需要清除缓存，例如更改了环境变量，或更新了应用。您可以在此处进行缓存清理。',
        ]
    ],

];