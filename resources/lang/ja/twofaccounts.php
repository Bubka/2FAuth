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

    'service' => 'サービス',
    'account' => 'アカウント',
    'icon' => 'アイコン',
    'icon_to_illustrate_the_account' => 'このアカウントを表すアイコン',
    'remove_icon' => 'アイコンを削除',
    'no_account_here' => '初めてですか？',
    'add_first_account' => '以下の方法で最初のアカウントを追加しましょう',
    'use_full_form' => 'またはフォームで',
    'add_one' => '追加',
    'show_qrcode' => 'QRコードを表示',
    'no_service' => '- サービスなし -',
    'account_created' => 'アカウントを作成しました！',
    'account_updated' => 'アカウントを更新されました',
    'accounts_deleted' => 'アカウントを削除しました',
    'accounts_moved' => 'アカウントを移動しました',
    'export_selected_accounts' => '選択したアカウントをエクスポート',
    'twofauth_export_format' => '2FAuth 形式',
    'twofauth_export_format_sub' => '2FAuth の JSON スキーマでデータをエクスポート',
    'twofauth_export_format_desc' => '復元可能なバックアップを作成する必要がある場合は、このオプションを選択してください。この形式はアイコンにも対応しています。',
    'twofauth_export_format_url' => 'スキーマ定義の説明は：',
    'twofauth_export_schema' => '2FAuth エクスポートスキーマ',
    'otpauth_export_format' => 'otpauth URI',
    'otpauth_export_format_sub' => 'データを otpauth URI のリストとしてエクスポート',
    'otpauth_export_format_desc' => 'otpauth URI は 2FA データの交換に使用される最も一般的な形式で、ウェブサイトで 2FA を有効にした時に QR コードなど形で表示されるものです。2FAuth から移行したい場合は、これを選択します。',
    'reveal' => '表示',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => '名無 権兵衛',
        ],
        'new_account' => '新規アカウント',
        'edit_account' => 'アカウント編集',
        'otp_uri' => 'OTP URI',
        'scan_qrcode' => 'QRコードをスキャン',
        'upload_qrcode' => 'QRコードをアップロード',
        'use_advanced_form' => '詳細フォームを記入',
        'prefill_using_qrcode' => 'QRコードから転記',
        'use_qrcode' => [
            'val' => 'QRコードを使用',
            'title' => 'QRコードからデータをかんたん記入',
        ],
        'unlock' => [
            'val' => 'ロック解除',
            'title' => 'ロックを解除（自己責任で）',
        ],
        'lock' => [
            'val' => 'ロック',
            'title' => 'ロックする',
        ],
        'choose_image' => 'アップロード',
        'i_m_lucky' => '適当に取得',
        'i_m_lucky_legend' => 'The "Try my luck" button tries to get a standard icon from the selected icon collection. The simpler the Service field value, the more likely you are to get the expected icon: Do not append any extension (like ".com"), use the exact name of the service, avoid special chars.',
        'test' => 'テスト',
        'group' => [
            'label' => 'グループ',
            'help' => 'アカウントに割り当てるグループ'
        ],
        'secret' => [
            'label' => 'シークレット',
            'help' => 'セキュリティコードの生成に使用されるキー'
        ],
        'plain_text' => 'プレーンテキスト',
        'otp_type' => [
            'label' => 'Choose the type of OTP to create',
            'help' => '時間ベースのOTPか、HMACベースのOTPか、Steam OTP'
        ],
        'digits' => [
            'label' => '桁数',
            'help' => '生成されるセキュリティコードの桁数'
        ],
        'algorithm' => [
            'label' => 'アルゴリズム',
            'help' => 'セキュリティコードを生成するアルゴリズム'
        ],
        'period' => [
            'label' => '有効期間',
            'placeholder' => '既定は 30 です',
            'help' => '生成されるセキュリティコードの有効期間（秒）'
        ],
        'counter' => [
            'label' => 'カウンタ',
            'placeholder' => '既定は 0 です',
            'help' => '初期カウンタ値',
            'help_lock' => 'カウンタ値を編集すると、アカウントとサービスの認証サーバーの同期が崩れるおそれがあります。意味を理解している方のみ、錠のアイコンからこの項目の変更を許可してください。'
        ],
        'image' => [
            'label' => '画像',
            'placeholder' => 'http://...',
            'help' => 'アカウントアイコンとして使用する外部画像のURL'
        ],
        'is_shared' => [
            'label' => 'このアカウントをすべてのユーザーと共有する',
            'help' => '有効にすると、このアカウントはシステム内のすべてのユーザーに表示されます'
        ],
        'options_help' => '以下の項目は、必要がない限り空白のままで結構です。最も一般的な値が使用されます。',
        'alternative_methods' => 'または',
        'spaces_are_ignored' => '不要なスペースは自動的に削除されます'
    ],
    'stream' => [
        'live_scan_cant_start' => 'スキャンできません (´・ω・`)',
        'need_grant_permission' => [
            'reason' => 'カメラへのアクセス権限がありません',
            'solution' => 'デバイスのカメラへのアクセスを許可してください。 以前にアクセスを拒否し、ブラウザが再度権限を要求しなくなった場合は、ブラウザのドキュメントに従って操作してください。',
            'click_camera_icon' => '通常は、ブラウザのアドレスバーの横にある斜線の引かれたカメラアイコンをクリックすると起動できます。',
        ],
        'not_readable' => [
            'reason' => 'スキャナーの起動に失敗',
            'solution' => 'カメラを使用中ですか？カメラを使っている他のアプリを止めて再度お試しください。'
        ],
        'no_cam_on_device' => [
            'reason' => 'カメラが見つかりません',
            'solution' => 'ウェブカメラは正しく接続されていますか？'
        ],
        'secured_context_required' => [
            'reason' => '安全な通信が必要です',
            'solution' => 'ライブスキャンにはHTTPSが必要です。コンピュータで 2FAuth を実行している場合は、localhost 以外の仮想ホストを使用しないでください。'
        ],
        'https_required' => 'カメラストリーミングにHTTPSが必要です',
        'camera_not_suitable' => [
            'reason' => '搭載されたカメラは未対応です',
            'solution' => '別のデバイス/カメラを使用してください。'
        ],
        'stream_api_not_supported' => [
            'reason' => 'このブラウザは Stream API に対応していません',
            'solution' => '最新のブラウザを使用してください。'
        ],
    ],
    'confirm' => [
        'delete' => 'このアカウントを削除してもよろしいですか？',
        'cancel' => '変更内容は失われます。よろしいですか？',
        'discard' => 'このアカウントを破棄してもよろしいですか？',
        'discard_all' => 'すべてのアカウントを破棄してもよろしいですか？',
        'discard_duplicates' => 'すべての重複アカウントを破棄してもよろしいですか？',
    ],
    'import' => [
        'import' => 'インポート',
        'to_import' => 'インポート',
        'import_legend' => 'さまざまな 2 要素認証アプリから 2FAuth にデータを取り込めます。',
        'import_legend_afterpart' => '元のアプリのエクスポート機能を使用して、QRコードやJSONファイルにデータを出力し、読み込ませてください。',
        'upload' => 'アップロード',
        'scan' => 'スキャン',
        'supported_formats_for_qrcode_upload' => '対応形式：jpg, jpeg, png, bmp, gif, svg, webp',
        'supported_formats_for_file_upload' => '対応形式：プレーンテキスト, json, 2fas',
        'expected_format_for_direct_input' => '形式：1 行につき otpauth URI 1 件',
        'supported_migration_formats' => '対応する移行用フォーマット',
        'qr_code' => 'QRコード',
        'text_file' => 'テキストファイル',
        'direct_input' => '直接入力',
        'plain_text' => 'プレーンテキスト',
        'parsing_data' => 'データを解析中...',
        'issuer' => '発行者',
        'imported' => '完了',
        'failure' => '失敗',
        'x_valid_accounts_found' => '有効なアカウント :count 件',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => '移行データから以下の 2 要素認証アカウントが抽出されました。2FAuth にはまだ取り込まれていません。',
        'use_buttons_to_save_or_discard' => '各ボタンから取り込むか破棄するかを選択してください。',
        'import_all' => 'すべてインポート',
        'import_this_account' => 'これをインポート',
        'discard_all' => 'すべて破棄',
        'discard_duplicates' => '重複をすべて破棄',
        'discard_this_account' => 'これを破棄',
        'generate_a_test_password' => 'テストパスワードを生成',
        'possible_duplicate' => '完全に同一のアカウントがすでに存在します',
        'invalid_account' => '- 無効なアカウント -',
        'invalid_service' => '- 無効なサービス -',
        'do_not_set_password_or_encryption' => '2 要素認証アプリからデータをエクスポートする際にパスワード保護や暗号化をかけないでください。2FAuth で読み込めなくなります。',
    ],

];