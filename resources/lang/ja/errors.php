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

    'resource_not_found' => 'リソースが見つかりません',
    'error_occured' => 'エラーが発生しました',
    'refresh' => '再読み込み',
    'no_valid_otp' => 'このQRコードに有効なOTPリソースがありません',
    'something_wrong_with_server' => 'サーバーに問題が発生しました',
    'Unable_to_decrypt_uri' => 'URIを復号できません',
    'not_a_supported_otp_type' => 'このOTP形式は現在サポートされていません',
    'cannot_create_otp_without_secret' => 'シークレットなしのOTPは作成できません',
    'data_of_qrcode_is_not_valid_URI' => 'このQRコードのデータは有効なOTP認証URIではありません。QRコードの内容：',
    'wrong_current_password' => '現在のパスワードが間違っています。変更は行われていません',
    'error_during_encryption' => '暗号化に失敗しました。データベースの保護は行われていません',
    'error_during_decryption' => '復号に失敗しました。データベースは暗号化されたままです。1 つ以上のアカウントの暗号化データが壊れている可能性が高いです。',
    'qrcode_cannot_be_read' => 'このQRコードは読み取れません',
    'too_many_ids' => 'クエリパラメータに一度に含まれるIDidが多すぎます。上限は 100 です',
    'delete_user_setting_only' => 'ユーザーが作成した設定のみ削除できます',
    'indecipherable' => '※解読不能※',
    'cannot_decipher_secret' => 'シークレットは解読できませんでした。2FAuth の .env 設定ファイルに間違った APP_KEY が設定されているか、データベース内のデータが破損している可能性が高いです。',
    'https_required' => 'HTTPS 通信が必要です',
    'browser_does_not_support_webauthn' => 'お使いのデバイスは webauthn をサポートしていません。新しいブラウザでもう一度お試しください。',
    'aborted_by_user' => 'ユーザーによる中断',
    'security_device_already_registered' => 'デバイスは既に登録済みです',
    'not_allowed_operation' => '許可されていない操作です',
    'no_authenticator_support_specified_algorithms' => '指定されたアルゴリズムに対応する認証システムがありません',
    'authenticator_missing_discoverable_credential_support' => '認証システムが識別子つき認証情報 (discoverable credential) に対応していません',
    'authenticator_missing_user_verification_support' => '認証システムがユーザー認証に対応していません',
    'unknown_error' => '不明なエラー',
    'security_error_check_rpid' => 'セキュリティエラー<br/>WEBAUTHN_ID 環境変数を確認してください',
    '2fauth_has_not_a_valid_domain' => '2FAuth のドメインが有効なドメインではありません',
    'user_id_not_between_1_64' => 'ユーザー ID は 1 文字以上 64 文字以内でなければなりません',
    'no_entry_was_of_type_public_key' => '"public-key" 型の項目がありません',
    'unsupported_with_reverseproxy' => 'Not applicable when using an auth proxy or SSO',
    'unsupported_with_sso_only' => 'This authentication method is for administrators only. Users must log in with SSO.',
    'user_deletion_failed' => 'ユーザーアカウントを削除できませんでした。データは保存されたままです',
    'auth_proxy_failed' => 'プロキシ認証に失敗しました',
    'auth_proxy_failed_legend' => '2FAuth は認証プロキシ経由で動作するよう設定されていますが、プロキシは必要なヘッダーを返しませんでした。設定をご確認のうえ再度お試しください。',
    'invalid_x_migration' => '無効または読み取り不能な :appname データです',
    'invalid_2fa_data' => '無効な 2 要素認証データです',
    'unsupported_migration' => '対応しているどの形式とも一致しません',
    'unsupported_otp_type' => '未対応のOTPタイプです',
    'encrypted_migration' => '読み込めませんでした。暗号化されたデータのようです',
    'no_logo_found_for_x' => ':service のロゴがありません',
    'file_upload_failed' => 'ファイルのアップロードに失敗しました',
    'unauthorized' => '許可されていません',
    'unauthorized_legend' => 'このリソースを表示したり、このアクションを実行する権限がありません。',
    'cannot_delete_the_only_admin' => '唯一の管理者アカウントは削除できません',
    'cannot_demote_the_only_admin' => '唯一の管理者アカウントは降格できません',
    'error_during_data_fetching' => '💀 データ取得中に問題が発生しました',
    'check_failed_try_later' => 'チェックが失敗しました。後ほどもう一度お試しください',
    'sso_disabled' => 'SSOが無効です',
    'sso_bad_provider_setup' => 'このSSOプロバイダの .env ファイル設定が不完全です',
    'sso_failed' => 'SSO認証が拒否されました',
    'sso_no_register' => '新規登録は受け付けていません',
    'sso_email_already_used' => '同じメールアドレスを持つユーザーアカウントは既に存在しますが、あなたの外部アカウントIDと一致しません。 このアドレスで 2FAuth に登録済みの場合は、SSOを使用しないでください。',
    'account_managed_by_external_provider' => '外部プロバイダが管理するアカウント',
    'data_cannot_be_refreshed_from_server' => 'データをサーバーから更新できません',
    'no_pwd_reset_for_this_user_type' => 'このユーザーにパスワードリセットはできません',
    'cannot_detect_qrcode_in_image' => '画像内にQRコードを検出できません。画像をトリミングしてください',
    'cannot_decode_detected_qrcode' => '検出されたQRコードをデコードできません。画像をトリミングまたはシャープにしてください。',
    'qrcode_has_invalid_checksum' => 'QRコードのチェックサムが不正です',
    'no_readable_qrcode' => '読み取り可能なQRコードがありません',
    'failed_icon_store_database_toggling' => 'Migration of icons failed. The setting has been restored to its previous value.',
];