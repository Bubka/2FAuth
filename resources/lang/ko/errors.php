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

    'resource_not_found' => '리소스를 찾을 수 없음',
    'error_occured' => '오류 발생:',
    'refresh' => '새로고침',
    'no_valid_otp' => '이 QR코드에 유효한 OTP 리소스가 없습니다.',
    'something_wrong_with_server' => '서버에 문제가 발생했습니다.',
    'Unable_to_decrypt_uri' => 'Uri를 인식할 수 없습니다.',
    'not_a_supported_otp_type' => '이 OTP 형식은 현재 지원되지 않습니다.',
    'cannot_create_otp_without_secret' => '시크릿키 없이는 OTP를 만들 수 없습니다.',
    'data_of_qrcode_is_not_valid_URI' => '이 QR 코드의 데이터는 유효한 OTP 인증 URI가 아닙니다. QR 코드에 다음이 포함되어 있습니다:',
    'wrong_current_password' => '현재 비밀번호가 잘못되어 변경사항이 저장되지 않습니다.',
    'error_during_encryption' => '암호화에 실패하여 데이터베이스가 보호되지 않은 상태로 유지됩니다.',
    'error_during_decryption' => '암호 복호화에 실패했지만 데이터베이스는 여전히 보호됩니다. 이는 주로 하나 이상의 계정에 대해 암호화된 데이터의 무결성 문제로 인해 발생합니다.',
    'qrcode_cannot_be_read' => '이 QR코드를 읽을 수 없습니다',
    'too_many_ids' => '쿼리 매개 변수에 너무 많은 ID가 포함되었습니다(최대 100개 허용)',
    'delete_user_setting_only' => '사용자가 만든 설정만 삭제할 수 있습니다',
    'indecipherable' => '*해독할 수 없음*',
    'cannot_decipher_secret' => '암호를 해독할 수 없습니다. 이는 주로 2Fauth의 .env 구성 파일에 설정된 APP_KEY가 잘못되었거나 데이터베이스에 저장된 데이터가 손상된 경우 발생합니다.',
    'https_required' => 'HTTPS context 필요',
    'browser_does_not_support_webauthn' => '장치가 webauthn을 지원하지 않습니다. 최신 브라우저를 사용하여 다시 시도하세요.',
    'aborted_by_user' => '사용자에 의해 중단됨',
    'security_device_already_registered' => '장치가 이미 등록됨',
    'not_allowed_operation' => '작업이 허용되지 않음',
    'no_authenticator_support_specified_algorithms' => '지정된 알고리즘을 지원하는 인증서가 없습니다',
    'authenticator_missing_discoverable_credential_support' => '인증 방법에 검색 가능한 자격 증명 기능 없음',
    'authenticator_missing_user_verification_support' => '인증 방법에 사용자 확인 기능 없음',
    'unknown_error' => '알 수 없는 오류',
    'security_error_check_rpid' => '보안 오류<br/>WEBAUTHN_ID 환경 변수를 확인하세요',
    '2fauth_has_not_a_valid_domain' => '2FAuth의 도메인이 유효한 도메인이 아닙니다',
    'user_id_not_between_1_64' => '사용자 ID가 1~64자 사이가 아닙니다',
    'no_entry_was_of_type_public_key' => '"공개 키" 유형의 항목이 없습니다',
    'unsupported_with_reverseproxy' => 'Not applicable when using an auth proxy or SSO',
    'unsupported_with_sso_only' => 'This authentication method is for administrators only. Users must log in with SSO.',
    'user_deletion_failed' => '사용자 계정 삭제 실패, 데이터가 삭제되지 않음',
    'auth_proxy_failed' => '프록시 인증 실패',
    'auth_proxy_failed_legend' => '2Fauth가 인증 프록시 하에서 실행되도록 구성되었지만 프록시가 올바른 헤더를 반환하지 않습니다. 구성을 확인하고 다시 시도하세요.',
    'invalid_x_migration' => '유효하지 않거나 읽을 수 없음 :appname 데이터',
    'invalid_2fa_data' => '유효하지 않은 2FA 데이터',
    'unsupported_migration' => '지원되는 데이터 형식이 아닙니다',
    'unsupported_otp_type' => '지원되지 않는 OTP 형식',
    'encrypted_migration' => '읽을 수 없음, 데이터가 암호화되어 있음',
    'no_logo_found_for_x' => ':service에 대한 사용가능한 로고 없음',
    'file_upload_failed' => '파일 업로드 실패',
    'unauthorized' => '권한 없음',
    'unauthorized_legend' => '이 리소스를 보거나 작업을 수행할 수 있는 권한이 없습니다',
    'cannot_delete_the_only_admin' => '유일한 관리자 계정을 삭제할 수 없습니다',
    'cannot_demote_the_only_admin' => '유일한 관리자 계정을 강등할 수 없습니다',
    'error_during_data_fetching' => '💀 데이터 가져오기 중 문제가 발생했습니다',
    'check_failed_try_later' => '확인 실패, 나중에 다시 시도하세요',
    'sso_disabled' => 'SSO 비활성화됨',
    'sso_bad_provider_setup' => '이 SSO 제공자가 .env 파일에 올바르게 설정되어 있지 않습니다.',
    'sso_failed' => 'SSO를 통한 인증이 거부됨',
    'sso_no_register' => '가입 비활성화됨',
    'sso_email_already_used' => '동일한 이메일 주소를 가진 사용자 계정이 이미 존재하지만 외부 계정 ID와 일치하지 않습니다. 이 이메일로 이미 2FAuth에 등록되어 있는 경우 SSO를 사용하지 마세요.',
    'account_managed_by_external_provider' => '외부 제공업체가 관리하는 계정',
    'data_cannot_be_refreshed_from_server' => '서버에서 데이터를 갱신할 수 없습니다',
    'no_pwd_reset_for_this_user_type' => '이 사용자는 비밀번호를 재설정할 수 없습니다',
    'cannot_detect_qrcode_in_image' => '이미지에서 QR 코드를 감지할 수 없습니다. 이미지를 잘라보세요.',
    'cannot_decode_detected_qrcode' => '감지된 QR 코드를 인식할 수 없습니다. 이미지를 자르거나 선명한 이미지를 사용해보세요.',
    'qrcode_has_invalid_checksum' => 'QR 코드에 잘못된 체크섬이 있습니다.',
    'no_readable_qrcode' => '인식 가능한 QR 코드 없음',
];