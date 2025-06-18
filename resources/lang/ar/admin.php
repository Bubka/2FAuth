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

    'admin' => 'مدير',
    'admin_panel' => 'لوحة الإدارة',
    'app_setup' => 'إعداد التطبيق',
    'auth' => 'المصادقة',
    'registrations' => 'التسجيلات',
    'users' => 'المستخدمون',
    'users_legend' => 'Manage users registered on your instance or create new ones.',
    'admin_settings' => 'إعدادات المدير',
    'create_new_user' => 'أنشئ مستخدمًا',
    'new_user' => 'مستخدم جديد',
    'search_user_placeholder' => 'اسم المستخدم، البريد الإلكتروني...',
    'quick_filters_colons' => 'فلاتر السريعة:',
    'user_created' => 'تم إنشاء المستخدم بنجاح',
    'confirm' => [
        'delete_user' => 'هل أنت متيقِّن من أنك تريد حذف هذا المستخدم؟ لا يوجد أي رجعة.',
        'request_password_reset' => 'هل أنت متيقِّن من أنك تريد إعادة تعيين كلمة مرور هذا المستخدم؟',
        'purge_password_reset_request' => 'هل أنت متيقِّن من أنك تريد إلغاء الطلب السابق؟',
        'delete_account' => 'هل أنت متيقِّن من أنك تريد حذف هذا المستخدم ؟',
        'edit_own_account' => 'هذا هو حسابك الخاص. هل أنت متيقِّن؟',
        'change_admin_role' => 'سيكون لهذا تأثيرات خطيرة على أذونات هذا المستخدم. هل أنت متيقِّن؟',
        'demote_own_account' => 'لن تكون بعد الآن مديرا. هل أنت متيقِّن؟'
    ],
    'logs' => 'السجلات',
    'administration_legend' => 'الإعدادات التالية هي شاملة وتنطبق على جميع المستخدمين.',
    'user_management' => 'إدارة المستخدم',
    'oauth_provider' => 'مُزود OAuth',
    'account_bound_to_x_via_oauth' => 'هذا الحساب مرتبط بحساب :provider عبر OAuth',
    'last_seen_on_date' => 'اخر ظهور :date',
    'registered_on_date' => 'تم تسجيله :date',
    'updated_on_date' => 'تم التحديث :date',
    'access' => 'الدخول',
    'password_requested_on_t' => 'A password reset request exists for this user (request sent at :datetime), which means that the user has not yet changed their password but the link they received is still valid. This may be a request from the user themselves or from an administrator.',
    'password_request_expired' => 'يوجد طلب إعادة تعيين كلمة المرور لهذا المستخدم ولكن انتهت صلاحيته، بمعنى أن المستخدم لم يغير كلمة المرور الخاصة به في الوقت المناسب. قد يكون هذا طلباً من المستخدم نفسه أو من أحد المسؤولين.',
    'resend_email' => 'إعادة إرسال البريد الإلكتروني',
    'resend_email_title' => 'إعادة إرسال بريد إلكتروني لإعادة تعيين كلمة المرور للمستخدم',
    'resend_email_help' => 'استخدم <b>إعادة إرسال البريد الإلكتروني</b> لإرسال بريد إلكتروني جديد لإعادة تعيين كلمة المرور للمستخدم حتى يتمكن من تعيين كلمة مرور جديدة. هذا سيترك كلمة المرور الحالية كما هو وسيتم إلغاء أي طلب سابق.',
    'reset_password' => 'إعادة تعيين كلمة المرور',
    'reset_password_help' => 'Use <b>Reset password</b> to force a password reset (this will set a temporary password) before sending a password reset email to the user so they can set a new password. Any previous request will be revoked.',
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
    'successful_login_on' => 'Successful login on <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => 'Successful logout on <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => 'Failed login on <span class="light-or-darker">:login_at</span>',
    'viewed_on' => 'Viewed on <span class="light-or-darker">:login_at</span>',
    'last_accesses' => 'Last accesses',
    'see_full_log' => 'See full log',
    'browser_on_platform' => ':browser on :platform',
    'access_log_has_more_entries' => 'The access log contains more entries.',
    'access_log_legend_for_user' => 'Full access log for user :username',
    'show_last_month_log' => 'Show entries from the last month',
    'show_three_months_log' => 'Show entries from the last 3 months',
    'show_six_months_log' => 'Show entries from the last 6 months',
    'show_one_year_log' => 'Show entries from the last year',
    'sort_by_date_asc' => 'Show least recent first',
    'sort_by_date_desc' => 'Show most recent first',
    'single_sign_on' => 'Single Sign-On (SSO)',
    'database' => 'Database',
    'file_system' => 'File system',
    'storage' => 'Storage',
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
            'label' => 'Enable SSO',
            'help' => 'Allow visitors to authenticate using an external ID via the Single Sign-On scheme',
        ],
        'use_sso_only' => [
            'label' => 'Use SSO only',
            'help' => 'Make SSO the only available method to log in to 2FAuth. Password login and Webauthn are then disabled for regular users. Administrators are not affected by this restriction.',
        ],
        'allow_pat_in_sso_only' => [
            'label' => 'Allow PAT usage',
            'help' => 'Let users create personal access tokens and use them to authenticate with third party application like the 2FAuth web extension',
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
        'health_endpoint' => [
            'label' => 'Health endpoint',
            'help' => 'URL you can visit to check the health of this 2FAuth instance. This URL can be used to set up a Docker HEALTHCHECK or a Kubernetes HTTPS Liveness probe.',
        ],
        'cache_management' => [
            'label' => 'Cache management',
            'help' => 'Sometimes cache needs to be cleared, for instance after a change to environment variables or an update. You can do it from here.',
        ],
        'store_icon_to_database' => [
            'label' => 'Store icons to database',
            'help' => 'Uploaded icons are registered in the database in addition to the file system storage, which is then used only as a cache. This makes creating a 2FAuth backup much easier, as only the database has to be backed up.<br /><br />But beware, this may has some drawbacks: The database size may increase significantly if the instance hosts many large icons. It may also affect the application performance because the file system is hit more often to ensure it is synchronised with the database.',
        ],
    ],

];