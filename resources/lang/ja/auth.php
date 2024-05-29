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
    'failed' => '認証情報と一致するレコードがありません。',
    'password' => 'パスワードが違います。',
    'throttle' => 'ログインの試行回数が多すぎます。:seconds 秒後にお試しください。',

    // 2FAuth
    'sign_out' => 'サインアウト',
    'sign_in' => 'サインイン',
    'sign_in_using' => 'ログイン方式：',
    'or_continue_with' => 'または外部認証を利用：',
    'sign_in_using_security_device' => 'セキュリティデバイスでログイン',
    'login_and_password' => 'IDとパスワード',
    'register' => '新規登録',
    'welcome_to_2fauth' => '2FAuth へようこそ',
    'autolock_triggered' => '自動ロック作動',
    'autolock_triggered_punchline' => '自動ロックが作動し、ログアウトされました。',
    'already_authenticated' => 'すでに認証済みです。一度ログアウトしてください。',
    'authentication' => '認証',
    'maybe_later' => 'あとで',
    'user_account_controlled_by_proxy' => '認証プロキシを利用したアカウントです。<br />プロキシからアカウントを管理してください。',
    'auth_handled_by_proxy' => 'リバースプロキシが認証を処理しているため、以下の設定は利用できません。<br />プロキシから認証を管理してください。',
    'confirm' => [
        'logout' => 'ログアウトしてもよろしいですか？',
        'revoke_device' => 'このデバイスを失効させてもよろしいですか？',
        'delete_account' => 'アカウントを削除してもよろしいですか？',
    ],
    'webauthn' => [
        'security_device' => 'セキュリティデバイス',
        'security_devices' => 'セキュリティデバイス',
        'security_devices_legend' => '2FAuth にログインできる認証デバイス。セキュリティキー（Yubikey など）や生体認証機能を備えたスマートフォン（Apple FaceId/TouchId など）のようなものです。',
        'enhance_security_using_webauthn' => 'WebAuthn 認証を利用して、2FAuth アカウントのセキュリティを強化することができます。<br /><br />
            WebAuthn を使用すると、信頼できるデバイス（Yubikey や生体認証機能が付いたスマートフォンなど）を使用して迅速かつ安全にログインできます。',
        'use_security_device_to_sign_in' => 'これからセキュリティデバイスを使用して認証を行います。キーは接続し、マスクや手袋は外しましょう。',
        'lost_your_device' => 'デバイスをなくした？',
        'recover_your_account' => 'アカウントの復元',
        'account_recovery' => 'アカウントの復元',
        'recovery_punchline' => '2FAuth はこのメールアドレスに復元用のリンクを送信します。受信したメールのリンクをクリックし、指示に従ってください。<br /><br />メールは必ずご自分のデバイスで開いてください。',
        'send_recovery_link' => '復元用リンクを送信',
        'account_recovery_email_sent' => 'アカウント復元メールを送信しました！',
        'disable_all_security_devices' => 'すべてのセキュリティデバイスを無効化',
        'disable_all_security_devices_help' => 'すべてのセキュリティデバイスを失効させます。デバイスを紛失したり、データが漏洩したと思われる場合に使用します。',
        'register_a_new_device' => '新しいデバイスを登録',
        'register_a_device' => 'デバイスの登録',
        'device_successfully_registered' => 'デバイスを登録しました！',
        'device_revoked' => 'デバイスを失効させました！',
        'revoking_a_device_is_permanent' => 'デバイスの取り消しは元に戻せません',
        'recover_account_instructions' => 'アカウントを復元するため、2FAuth は Webauthn の設定を一部リセットし、メールアドレスとパスワードでログインできるようにします。',
        'invalid_recovery_token' => '無効な復元用トークンです',
        'webauthn_login_disabled' => 'Webauthn ログインが無効です',
        'invalid_reset_token' => 'このリセットトークンは無効です',
        'rename_device' => 'デバイス名の変更',
        'my_device' => '私のデバイス',
        'unknown_device' => '不明なデバイス',
        'use_webauthn_only' => [
            'label' => 'WebAuthn 以外を無効化',
            'help' => 'WebAuthn を 2FAuth アカウントにログインする唯一の認証手段にします。WebAuthn の高い安全性をフルに発揮させるためにおすすめです。<br /><br />
                デバイスを紛失した場合は、このオプションを無効にすれば、メールアドレスとパスワードでログインできるようになります。<br /><br />
                このオプションを有効にしても、メールアドレスとパスワードによるログイン画面は表示されますが、常に認証に失敗します。'
        ],
        'need_a_security_device_to_enable_options' => '以下のオプションを有効にするには、1 つ以上デバイスを登録してください',
        'options' => 'オプション',
    ],
    'forms' => [
        'name' => '名前',
        'login' => 'ログイン',
        'webauthn_login' => 'WebAuthn ログイン',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'reveal_password' => 'パスワードを表示',
        'hide_password' => 'パスワードを非表示',
        'confirm_password' => 'パスワード（確認用）',
        'new_password' => '新しいパスワード',
        'confirm_new_password' => '新しいパスワード（確認用）',
        'dont_have_account_yet' => 'アカウントをお持ちでない方は',
        'already_register' => '登録済みの方は',
        'authentication_failed' => '認証失敗',
        'forgot_your_password' => 'パスワードをお忘れですか？',
        'request_password_reset' => 'リセットする',
        'reset_your_password' => 'パスワードをリセット',
        'reset_password' => 'パスワードリセット',
        'disabled_in_demo' => 'デモモードでは無効な機能です',
        'new_password' => '新しいパスワード',
        'current_password' => [
            'label' => '現在のパスワード',
            'help' => '本人確認のため、現在のパスワードを入力してください'
        ],
        'change_password' => 'パスワードを変更',
        'send_password_reset_link' => 'リセット用リンクを送信',
        'password_successfully_reset' => 'パスワードがリセットされました',
        'edit_account' => 'アカウント編集',
        'profile_saved' => 'プロフィールを更新しました！',
        'welcome_to_demo_app_use_those_credentials' => '2FAuth デモへようこそ。<br><br>メールアドレス <strong>demo@2fauth.app</strong> とパスワード <strong>demo</strong> で接続できます',
        'welcome_to_testing_app_use_those_credentials' => '2FAuth テストインスタンスへようこそ。<br><br>メールアドレス <strong>testing@2fauth.app</strong> パスワード <strong>password</strong> をお使いください',
        'register_punchline' => '<b>2FAuth</b> へようこそ。<br/>ご利用にはアカウント登録が必要です。',
        'reset_punchline' => '2FAuth はこのアドレスにパスワードリセット用のリンクを送信します。受信したメール内のリンクをクリックして、新しいパスワードを設定してください。',
        'name_this_device' => 'デバイスの名前を設定',
        'delete_account' => 'アカウントの削除',
        'delete_your_account' => 'アカウントを削除',
        'delete_your_account_and_reset_all_data' => 'あなたのアカウントとすべての 2 要素認証データが削除されます。元に戻すことはできません。',
        'reset_your_password_to_delete_your_account' => '常にSSOを使用してログインしていた場合は、一度ログアウトし、パスワードリセットの機能を使用して、ここに記入するパスワードを取得してください。',
        'deleting_2fauth_account_does_not_impact_provider' => '2FAuth アカウントを削除しても、あなたの外部SSOアカウントに影響はありません。',
        'user_account_successfully_deleted' => 'アカウントを削除しました',
        'has_lower_case' => '半角小文字を含む',
        'has_upper_case' => '半角大文字を含む',
        'has_special_char' => '半角記号を含む',
        'has_number' => '半角数字を含む',
        'is_long_enough' => '8 文字以上',
        'mandatory_rules' => '必須条件',
        'optional_rules_you_should_follow' => '（強く）推奨',
        'caps_lock_is_on' => 'Caps Lock がオンです！',
    ],

];
