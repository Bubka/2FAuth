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

    'hello' => 'こんにちは',
    'hello_user' => '{username} さん、こんにちは',
    'regards' => 'よろしくお願いします',
    'test_email_settings' => [
        'subject' => '2FAuth テストメール',
        'reason' => 'このメールはあなたの要望に応じて送信された、2FAuth インスタンスのメール設定検証用のテストメールです。',
        'success' => '届いているようですね！'
    ],
    'new_device' => [
        'subject' => '新しいデバイスから 2FAuth に接続しました',
        'resume' => '新しいデバイスがあなたの2FAuthアカウントに接続されました。',
        'connection_details' => 'この接続の詳細は以下の通りです',
        'recommandations' => '心当たりがある場合は、この通知を無視しても構いません。アカウントへの不審な行為が疑われる場合は、パスワードをご変更ください。'
    ],
    'failed_login' => [
        'subject' => '2FAuth へのログイン失敗発生',
        'resume' => 'あなたの 2FAuth アカウントへのログイン失敗が発生しました。',
        'connection_details' => '具体的な情報は以下の通りです',
        'recommandations' => '心当たりがある場合は、この通知を無視しても構いません。今後も行為が続く場合は、2FAuth の管理者に相談し、セキュリティ設定の見直しや攻撃者への対処を検討してください。'
    ],
];