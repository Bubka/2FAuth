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
    'failed' => 'Credenziali non valide.',
    'throttle' => 'Troppi tentativi di accesso. Riprova tra :seconds secondi.',

    // 2FAuth
    'sign_out' => 'Sign out',
    'sign_in' => 'Sign in',
    'register' => 'Register',
    'welcome_back_x' => 'Welcome back {0}',
    'already_authenticated' => 'Already authenticated',
    'confirm' => [
        'logout' => 'Are you sure you want to log out?',
    ],
    'forms' => [
        'name' => 'Name',
        'login' => 'Login',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm password',
        'confirm_new_password' => 'Confirm new password',
        'dont_have_account_yet' => 'Don\'t have your account yet?',
        'already_register' => 'Already registered?',
        'password_do_not_match' => 'Password do not match',
        'forgot_your_password' => 'Forgot your password?',
        'request_password_reset' => 'Reset it',
        'reset_password' => 'Reset password',
        'no_reset_password_in_demo' => 'No reset in Demo mode',
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
        'welcome_to_demo_app_use_those_credentials' => 'Welcome to the 2FAuth demo.<br><br>You can connect using the email address <strong>demo@2fauth.app</strong> and the password <strong>demo</demo>',
        'register_punchline' => 'Welcome to 2FAuth.<br/>You need an account to go further. Fill this form to register yourself, and please, choose a strong password, 2FA data are sensitives.',
        'reset_punchline' => '2FAuth will send you a password reset link to this address. Click the link in the received email to set a new password.',
    ],

];
