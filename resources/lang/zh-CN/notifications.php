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
    'hello_user' => '您好，:username，',
    'regards' => '此致',
    'test_email_settings' => [
        'subject' => '2FAuth 测试邮件',
        'reason' => '您之所以会收到这封邮件，是因为您要求发送一封测试邮件，来验证您 2FAuth 的上邮件发送功能是否能正常工作。',
        'success' => '恭喜您，功能正常 :)'
    ],
    'new_device' => [
        'subject' => '来自新设备的访问',
        'resume' => '您的 2FAuth 账户有来自新设备的登录。',
        'connection_details' => '这是本次事件的相关信息',
        'recommandations' => '如果是您操作的，您可以忽略此告警。如果您怀疑自己的账户有任何可疑活动，请立即更改您的密码。'
    ],
    'failed_login' => [
        'subject' => '2FAuth 登录失败',
        'resume' => '您的 2FAuth 账户触发了一次登录失败警告。',
        'connection_details' => '这是本次事件的相关信息',
        'recommandations' => '如果这是您触发的，您可以忽略此告警。但如果不是您，且持续有登录失败的警告的话，您应立即通知 2FAuth 的管理员去检查相关的安全设置，并对此攻击者采取措施。'
    ],
];