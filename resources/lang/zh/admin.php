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
    'reset_password_help' => 'Use <b>Reset password</b> to force a password reset (this will set a temporary password) before sending a password reset email to the user so he can set a new password. Any previous request will be revoked.',
    'reset_password_title' => 'Reset the user\'s password',
    'password_successfully_reset' => 'Password successfully reset',
    'user_has_x_active_pat' => ':count active token(s)',
    'user_has_x_security_devices' => ':count security device(s) (passkeys)',
    'revoke_all_pat_for_user' => 'Revoke all user\'s tokens',
    'revoke_all_devices_for_user' => 'Revoke all user\'s security devices',
    'danger_zone' => 'Danger Zone',
    'delete_this_user_legend' => 'The user account will be deleted as well as all its 2FA data.',
    'this_is_not_soft_delete' => 'This is not a soft delete, there is no going back.',
    'delete_this_user' => 'Delete this user',
    'user_role_updated' => 'User role updated',
    'pats_succesfully_revoked' => 'User\'s PATs successfully revoked',
    'security_devices_succesfully_revoked' => 'User\'s security devices successfully revoked',
    'variables' => 'Variables',
    'cache_cleared' => 'Cache cleared',
    'cache_optimized' => 'Cache optimized',
    'check_now' => 'Check now',
    'view_on_github' => 'View on Github',
    'x_is_available' => ':version is available',
    'forms' => [
        'use_encryption' => [
            'label' => 'Protect sensitive data',
            'help' => 'Sensitive data, the 2FA secrets and emails, are stored encrypted in database. Be sure to backup the APP_KEY value of your .env file (or the whole file) as it serves as key encryption. There is no way to decypher encrypted data without this key.',
        ],
        'restrict_registration' => [
            'label' => 'Restrict registration',
            'help' => 'Make registration only available to a limited range of email addresses. Both rules can be used simultaneously. This has no effect on registration via SSO.',
        ],
        'restrict_list' => [
            'label' => 'Filtering list',
            'help' => 'Emails in this list will be allowed to register. Separate addresses with a pipe ("|")',
        ],
        'restrict_rule' => [
            'label' => 'Filtering rule',
            'help' => 'Emails matching this regular expression will be allowed to register',
        ],
        'disable_registration' => [
            'label' => 'Disable registration',
            'help' => 'Prevent new user registration. Unless overridden (see below), this affects SSO as well, so new users won\'t be able to sign in via SSO',
        ],
        'enable_sso' => [
            'label' => 'Enable Single Sign-On (SSO)',
            'help' => 'Allow visitors to authenticate using an external ID via the Single Sign-On scheme',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'Keep SSO registration enabled',
            'help' => 'Allow new users to sign in for the first time via SSO whereas registration is disabled',
        ],
        'is_admin' => [
            'label' => 'Is administrator',
            'help' => 'Give administrator rights to the user. Administrators have permissions to manage the whole app, i.e. settings and other users, but cannot generate password for a 2FA they don\'t own.'
        ],
        'test_email' => [
            'label' => 'Email configuration test',
            'help' => 'Send a test email to control your instance\'s email configuration. It is important to have a working configuration, otherwise users will not be able to request a reset password.',
            'email_will_be_send_to_x' => 'The email will be send to <span class="is-family-code has-text-info">:email</span>',
        ],
        'cache_management' => [
            'label' => 'Cache management',
            'help' => '有时缓存需要清除，例如在更改环境变量或更新后。您可以在此处这样做。',
        ]
    ],

];