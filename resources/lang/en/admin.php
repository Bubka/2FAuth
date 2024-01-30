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

    'admin' => 'Admin',
    'app_setup' => 'App setup',
    'users' => 'Users',
    'users_legend' => 'Manage users registered on your instance or create new ones.',
    'admin_settings' => 'Admin settings',
    'create_new_user' => 'Create a user',
    'new_user' => 'New user',
    'user_created' => 'user successfully created',
    'confirm' => [
        'delete_user' => 'Are you sure you want to delete this user? There is no going back.',
        'request_password_reset' => 'Are you sure you want to reset this user\'s password?',
        'purge_password_reset_request' => 'Are you sure you want to purge the request?',
        'delete_account' => 'Are you sure you want to delete this user?',
        'edit_own_account' => 'This is your own account. Are you sure?',
        'change_admin_role' => 'This will have serious impacts on this user\'s permissions. Are you sure?',
        'demote_own_account' => 'You will no longer be an administrator. Really sure?'
    ],
    'administration' => 'Administration',
    'logs' => 'Logs',
    'administration_legend' => 'Following settings are global and apply to all users.',
    'user_management' => 'User management',
    'oauth_provider' => 'OAuth provider',
    'account_bound_to_x_via_oauth' => 'This account is bound to a :provider account via OAuth',
    'last_seen_on_date' => 'Last seen at :date',
    'registered_on_date' => 'Registered on :date',
    'updated_on_date' => 'Updated on :date',
    'access' => 'Access',
    'password_requested_on_t' => 'A password reset request exists for this user (request sent at :datetime) meaning the user didn\'t change its password yet but the link he received is still valid. This could be a request from the user himself or from an administrator.',
    'password_request_expired' => 'A password reset request exists for this user but has expired, meaning the user didn\'t change its password in time. This could be a request from the user himself or from an administrator.',
    'resend_email' => 'Resend email',
    'resend_email_title' => 'Resend a password reset email to the user',
    'resend_email_help' => 'Use <b>Resend email</b> to send a new password reset email to the user so he can set a new password. This will leave its current password as is and any previous request will be revoked.',
    'reset_password' => 'Reset password',
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
    'forms' => [
        'use_encryption' => [
            'label' => 'Protect sensible data',
            'help' => 'Sensitive data, the 2FA secrets and emails, are stored encrypted in database. Be sure to backup the APP_KEY value of your .env file (or the whole file) as it serves as key encryption. There is no way to decypher encrypted data without this key.',
        ],
        'disable_registration' => [
            'label' => 'Disable registration',
            'help' => 'Prevent new user registration. This affects SSO as well, so new SSO users won\'t be able to sign on',
        ],
        'enable_sso' => [
            'label' => 'Enable Single Sign-On (SSO)',
            'help' => 'Allow visitors to authenticate using an external ID via the Single Sign-On scheme',
        ],
        'is_admin' => [
            'label' => 'Is administrator',
            'help' => 'Give administrator rights to the user. Administrators have permissions to manage app settings and users.'
        ]
    ],

];