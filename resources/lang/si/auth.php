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
    'failed' => 'මෙම අක්තපත්ර අපගේ වාර්තා වලට ගැලපෙන්නේ නැත.',
    'throttle' => 'බොහෝ පිවිසුම් උත්සහයන් සිදු කර ඇත. කරුණාකර නැවත උත්සාහ කරන්න: තත්පර තත්පර.',

    // 2FAuth
    'sign_out' => 'වරන්න',
    'sign_in' => 'පුරන්න',
    'register' => 'ලියාපදිංචි වන්න',
    'welcome_back_x' => 'Welcome back {0}',
    'already_authenticated' => 'Already authenticated',
    'confirm' => [
        'logout' => 'Are you sure you want to log out?',
    ],
    'forms' => [
        'name' => 'නම',
        'login' => 'පිවිසෙන්න',
        'email' => 'විද්‍යුත් තැපෑල',
        'password' => 'මුර පදය',
        'confirm_password' => 'මුරපදය තහවුරු කරන්න',
        'confirm_new_password' => 'නව මුර පදය තහවුරු කරන්න',
        'dont_have_account_yet' => 'Don\'t have your account yet?',
        'already_register' => 'දැනටමත් ලියාපදිංචි වී ඇත්ද ?',
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
