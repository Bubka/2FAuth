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
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // 2FAuth
    'sign_out' => 'Sign out',
    'sign_in' => 'Sign in',
    'sign_in_using' => 'Sign in using',
    'login_and_password' => 'login & password',
    'register' => 'Register',
    'welcome_back_x' => 'Welcome back {0}',
    'autolock_triggered' => 'Auto lock triggered',
    'autolock_triggered_punchline' => 'The event watched by the Auto Lock feature has fired. You\'ve been automatically disconnected.',
    'change_autolock_in_settings' => 'You can change the behavior of the Autolock feature in Settings > Options tab.',
    'already_authenticated' => 'Already authenticated',
    'authentication' => 'Authentication',
    'maybe_later' => 'Maybe later',
    'user_account_controlled_by_proxy' => 'User account made available by an authentication proxy.<br />Manage the account at proxy level.',
    'auth_handled_by_proxy' => 'Authentication handled by a reverse proxy, below settings are disabled.<br />Manage authentication at proxy level.',
    'confirm' => [
        'logout' => 'Are you sure you want to log out?',
        'revoke_device' => 'Are you sure you want to revoke this device?',
        'delete_account' => 'Are you sure you want to delete your account?',
    ],
    'webauthn' => [
        'security_device' => 'a security device',
        'security_devices' => 'Security devices',
        'security_devices_legend' => 'Authentication devices you can use to sign in 2FAuth, like security keys (i.e Yubikey) or smartphones with biometric capabilities (i.e. Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'You can enhance the security of your 2FAuth account by enabling WebAuthn authentication.<br /><br />
            WebAuthn allows you to use trusted devices (like Yubikeys or smartphones with biometric capabilities) to sign in quickly and more securely.',
        'use_security_device_to_sign_in' => 'Get ready to authenticate using (one of) your security devices. Plug your key in, remove face mask or gloves, etc.',
        'lost_your_device' => 'Lost your device?',
        'recover_your_account' => 'Recover your account',
        'account_recovery' => 'Account recovery',
        'recovery_punchline' => '2FAuth will send you a recovery link to this email address. Click the link in the received email to register a new security device.<br /><br />Ensure you open the email on a device you fully own.',
        'send_recovery_link' => 'Send recovery link',
        'account_recovery_email_sent' => 'Account recovery email sent!',
        'disable_all_other_devices' => 'Disable all other devices except this one',
        'register_a_new_device' => 'Register a new device',
        'device_successfully_registered' => 'Device successfully registered',
        'device_revoked' => 'Device successfully revoked',
        'revoking_a_device_is_permanent' => 'Revoking a device is permanent',
        'recover_account_instructions' => 'Click the button below to register a new security device to recover your account. Just follow your browser instructions.',
        'invalid_recovery_token' => 'Invalid recovery token',
        'rename_device' => 'Rename device',
        'my_device' => 'My device',
        'unknown_device' => 'Unknown device',
        'use_webauthn_only' => [
            'label' => 'Use WebAuthn only',
            'help' => 'Make WebAuthn the only available method to sign in 2FAuth. This is the recommended setup to take advantage of the WebAuthn enhanced security.<br />
                In case of device lost you will always be able to register a new security device to recover your account.'
        ],
        'need_a_security_device_to_enable_options' => 'Set at least one device to enable these options',
        'use_webauthn_as_default' => [
            'label' => 'Use WebAuthn as default sign in method',
            'help' => 'Set the 2FAuth sign in form to propose the WebAuthn authentication at first. The Login/password method is then available as an alternative/fallback solution.<br />
                This has no effect if you only use WebAuthn.'
        ],
    ],
    'forms' => [
        'name' => 'Name',
        'login' => 'Login',
        'webauthn_login' => 'WebAuthn login',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm password',
        'confirm_new_password' => 'Confirm new password',
        'dont_have_account_yet' => 'Don\'t have your account yet?',
        'already_register' => 'Already registered?',
        'authentication_failed' => 'Authentication failed',
        'forgot_your_password' => 'Forgot your password?',
        'request_password_reset' => 'Reset it',
        'reset_password' => 'Reset password',
        'disabled_in_demo' => 'Feature disabled in Demo mode',
        'new_password' => 'New password',
        'current_password' => [
            'label' => 'Current password',
            'help' => 'Fill in your current password to confirm that it\'s you'
        ],
        'change_password' => 'Change password',
        'send_password_reset_link' => 'Send password reset link',
        'password_successfully_changed' => 'Password successfully changed',
        'edit_account' => 'Edit account',
        'profile_saved' => 'Profile successfully updated!',
        'welcome_to_demo_app_use_those_credentials' => 'Welcome to the 2FAuth demo.<br><br>You can connect using the email address <strong>demo@2fauth.app</strong> and the password <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Welcome to the 2FAuth testing instance.<br><br>Use email address <strong>testing@2fauth.app</strong> and password <strong>password</strong>',
        'register_punchline' => 'Welcome to 2FAuth.<br/>You need an account to go further. Fill this form to register yourself, and please, choose a strong password, 2FA data are sensitives.',
        'reset_punchline' => '2FAuth will send you a password reset link to this address. Click the link in the received email to set a new password.',
        'name_this_device' => 'Name this device',
        'delete_account' => 'Delete account',
        'delete_your_account' => 'Delete your account',
        'delete_your_account_and_reset_all_data' => 'This will reset 2FAuth. Your user account will be deleted as well as all 2FA data. There is no going back.',
        'user_account_successfully_deleted' => 'User account successfully deleted',
    ],

];
