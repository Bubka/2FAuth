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
        'reason' => '之所以您会收到这封邮件，是因为您请求了一封测试邮件来验证您 2FAuth 的电子邮件送信配置。',
        'success' => '恭喜您，功能正常 :)'
    ],
    'new_device' => [
        'subject' => '来自新设备的访问',
        'resume' => '一台新设备刚被添加到您的 2FAuth 账户上。',
        'connection_details' => '这是本次事件的相关信息',
        'recommandations' => '如果是您操作的，您可以忽略此告警。如果您怀疑您的账户有任何可疑活动，请立即更改您的密码。'
    ],
    'failed_login' => [
        'subject' => '2FAuth 登录失败',
        'resume' => '您的 2FAuth 账户触发了一次登录失败。',
        'connection_details' => '这是本次事件的相关信息',
        'recommandations' => '如果是您操作的，您可以忽略此告警。如果持续有失败的登录尝试，您应立即通知 2FAuth 的管理员去检查相关的安全设置，并对此攻击者采取措施。'
    ],
];