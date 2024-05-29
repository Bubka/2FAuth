<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifications Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'hello' => 'Hello',
    'hello_user' => 'Hello :username,',
    'regards' => 'Regards',
    'test_email_settings' => [
        'subject' => '2FAuth test email',
        'reason' => 'You are receiving this email because you requested a test email to validate the email settings of your 2FAuth instance.',
        'success' => 'Good news, it works :)'
    ],
    'new_device' => [
        'subject' => 'Connection to 2FAuth from a new device',
        'resume' => 'A new device has just connected to your 2FAuth account.',
        'connection_details' => 'Here are the details of this connection',
        'recommandations' => 'If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.'
    ],
    'failed_login' => [
        'subject' => 'Failed login to 2FAuth',
        'resume' => 'There has been a failed login attempt to your 2FAuth account.',
        'connection_details' => 'Here are the details of this connection attempt',
        'recommandations' => 'If this was you, you can ignore this alert. If further attempts fail, you should contact the 2FAuth administrator to review security settings and take action against this attacker.'
    ],
];