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

    'hello' => '您好',
    'hello_user' => 'Hello :username,',
    'regards' => 'Regards',
    'test_email_settings' => [
        'subject' => '2FAuth 测试电子邮件',
        'reason' => '您收到这封邮件是因为您请求了一封测试电子邮件来验证您的2FAuth 实例的电子邮件设置。',
        'success' => '好消息是，它正常工作:)'
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