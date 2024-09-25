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

    'admin' => '管理者',
    'app_setup' => 'アプリの機能',
    'auth' => 'Auth',
    'registrations' => '登録',
    'users' => 'ユーザー',
    'users_legend' => 'インスタンス上の登録ユーザーの管理や、新規作成を行います。',
    'admin_settings' => '管理者設定',
    'create_new_user' => 'ユーザーを作成',
    'new_user' => '新規ユーザー',
    'search_user_placeholder' => 'ユーザー名、メールアドレス…',
    'quick_filters_colons' => 'ショートカット：',
    'user_created' => 'ユーザーを作成しました',
    'confirm' => [
        'delete_user' => 'このユーザーを削除してもよろしいですか？元に戻すことはできません。',
        'request_password_reset' => 'このユーザーのパスワードをリセットしてもよろしいですか？',
        'purge_password_reset_request' => '以前のリクエストを削除してもよろしいですか？',
        'delete_account' => 'このユーザーを削除してもよろしいですか？',
        'edit_own_account' => 'これはあなた自身のアカウントです。よろしいですか？',
        'change_admin_role' => 'この操作はこのユーザーの権限に重大な影響を与えます。よろしいですか？',
        'demote_own_account' => 'あなたは管理者でなくなります。本当によろしいですか？'
    ],
    'logs' => 'ログ',
    'administration_legend' => '以下の設定はすべてのユーザーに適用される全体設定です。',
    'user_management' => 'ユーザー管理',
    'oauth_provider' => 'OAuth プロバイダ',
    'account_bound_to_x_via_oauth' => 'このアカウントは OAuth 経由で :provider アカウントにひも付けられています',
    'last_seen_on_date' => ':dateに最終ログイン',
    'registered_on_date' => ':dateに登録',
    'updated_on_date' => ':dateに更新',
    'access' => 'アクセス',
    'password_requested_on_t' => 'このユーザーにはパスワードリセット要求（送信時刻：:datetime）が送られています。つまり、このユーザーに送られたリンクはまだ有効で、パスワードは変更されていません。要求したのはユーザー自身である場合も、管理者である場合もあります。',
    'password_request_expired' => 'このユーザーには失効したパスワードリセット要求があります。つまり、このユーザーは有効期間内にパスワードを変更しませんでした。要求したのはユーザー自身である場合も、管理者である場合もあります。',
    'resend_email' => 'メールを再送信',
    'resend_email_title' => 'このユーザーにパスワードリセットメールを再送信する',
    'resend_email_help' => '「<b>メールを再送信</b>」からこのユーザーに新しいパスワードを設定させるためのリセットメールを改めて送信します。現在のパスワードは変更されず、以前のリセット要求はすべて無効になります。',
    'reset_password' => 'パスワードをリセット',
    'reset_password_help' => '「<b>パスワードをリセット</b>」でパスワードを強制的にリセット（仮パスワードが設定されます）した後、ユーザーに新しいパスワードを設定させるリセットメールを送信します。以前のリセット要求はすべて無効になります。',
    'reset_password_title' => 'このユーザーのパスワードをリセット',
    'password_successfully_reset' => 'パスワードがリセットされました',
    'user_has_x_active_pat' => '有効なトークン :count 件',
    'user_has_x_security_devices' => 'セキュリティデバイス (passkey) :count 台',
    'revoke_all_pat_for_user' => 'このユーザーの全トークンを取り消し',
    'revoke_all_devices_for_user' => 'このユーザーの全セキュリティデバイスを失効',
    'danger_zone' => '危険な操作',
    'delete_this_user_legend' => 'このユーザーアカウントと全2FAデータが削除されます。',
    'this_is_not_soft_delete' => 'ここで削除すると、二度と元には戻りません！',
    'delete_this_user' => 'このユーザーを削除',
    'user_role_updated' => 'ユーザーの身分が更新されました',
    'pats_succesfully_revoked' => 'ユーザーの PAT が取り消されました',
    'security_devices_succesfully_revoked' => 'ユーザーのセキュリティデバイスが失効しました',
    'variables' => '環境変数',
    'cache_cleared' => 'キャッシュが消去されました',
    'cache_optimized' => 'キャッシュが最適化されました',
    'check_now' => '今すぐ確認',
    'view_on_github' => 'GitHub で見る',
    'x_is_available' => ':version が利用可能',
    'successful_login_on' => '<span class="light-or-darker">:login_at</span> にログイン成功',
    'successful_logout_on' => '<span class="light-or-darker">:login_at</span> にログアウト成功',
    'failed_login_on' => '<span class="light-or-darker">:login_at</span> にログイン失敗',
    'viewed_on' => '<span class="light-or-darker">:login_at</span> に閲覧',
    'last_accesses' => '最終アクセス一覧',
    'see_full_log' => 'ログ全体を見る',
    'browser_on_platform' => ':browser（:platform）',
    'access_log_has_more_entries' => 'アクセスログには続きがあります。',
    'access_log_legend_for_user' => ':username の完全なアクセスログ',
    'show_last_month_log' => '先月のエントリを表示',
    'show_three_months_log' => '過去3ヶ月間のエントリを表示',
    'show_six_months_log' => '過去6ヶ月間のエントリを表示',
    'show_one_year_log' => '昨年からのエントリを表示',
    'sort_by_date_asc' => '古い順',
    'sort_by_date_desc' => '新しい順',
    'single_sign_on' => 'Single Sign-On (SSO)',
    'forms' => [
        'use_encryption' => [
            'label' => '機密データを保護',
            'help' => '機密データ（2 要素認証シークレットやメールアドレス）が暗号化してデータベースに保存されます。暗号化キーとなるので、.env ファイル内の APP_KEY の値（またはファイル全体）は必ずバックアップしてください。キーが失われるとデータは復号できません。',
        ],
        'restrict_registration' => [
            'label' => '登録を制限する',
            'help' => '限られたメールアドレスでのみ登録可能にします。両方のルールを同時に使用できます。SSO経由での登録には影響しません。',
        ],
        'restrict_list' => [
            'label' => 'フィルタリスト',
            'help' => 'リストにあるメールアドレスが許可されます。アドレスをパイプ ("|") で区切ってください。',
        ],
        'restrict_rule' => [
            'label' => 'フィルタルール',
            'help' => '正規表現に一致するメールアドレスを許可します。',
        ],
        'disable_registration' => [
            'label' => '登録を無効にする',
            'help' => '新規ユーザー登録を禁止します。上書き（下記参照）されない限り、SSOにも影響し、新規ユーザーはSSOでもサインインできなくなります。',
        ],
        'enable_sso' => [
            'label' => 'Enable SSO',
            'help' => '訪問者が外部 ID のシングルサインオンを利用して認証できるようにします。',
        ],
        'use_sso_only' => [
            'label' => 'Use SSO only',
            'help' => 'Make SSO the only available method to log in to 2FAuth. Password login and Webauthn are then disabled for regular users. Administrators are not affected by this restriction.',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'SSO登録は許可する',
            'help' => '新規登録が無効でも、新規ユーザーがSSO経由で初回サインインすることは許可する',
        ],
        'is_admin' => [
            'label' => '管理者権限',
            'help' => 'ユーザーに管理者権限を付与します。管理者はアプリ全体（設定や他のユーザー）を管理する権限を持っていますが、所有していない2FA用のパスワードは生成できません。'
        ],
        'test_email' => [
            'label' => 'メール設定テスト',
            'help' => 'インスタンスのメール設定を確認するためのテストメールを送信します。 メール設定が正しくないと、ユーザーはパスワードリセットを要求できなくなります。',
            'email_will_be_send_to_x' => '<span class="is-family-code has-text-info">:email</span> にメールを送信',
        ],
        'cache_management' => [
            'label' => 'キャッシュ管理',
            'help' => '環境変数の変更や更新の後などにキャッシュのクリアが必要になる場合があります。ここから実行できます。',
        ]
    ],

];