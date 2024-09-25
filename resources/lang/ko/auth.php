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
    'failed' => '로그인 정보가 일치하지 않습니다.',
    'password' => '비밀번호가 일치하지 않습니다.',
    'throttle' => '로그인 시도가 너무 많습니다. :seconds초 후에 다시 시도하십시오.',

    // 2FAuth
    'sign_out' => '로그아웃',
    'sign_in' => '로그인',
    'sign_in_using' => '로그인 방식:',
    'if_administrator' => 'Administrator?',
    'sign_in_here' => 'You can sign without SSO',
    'or_continue_with' => '다른 방법으로 로그인:',
    'password_login_and_webauthn_are_disabled' => 'Password login and WebAuthn are disabled.',
    'sign_in_using_sso' => 'Pick an SSO provider to sign in with:',
    'no_provider' => 'no provider',
    'no_sso_provider_or_provider_is_missing' => 'Provider is missing?',
    'see_how_to_enable_sso' => 'See how to enable a provider',
    'sign_in_using_security_device' => '보안 장치로 로그인',
    'login_and_password' => '로그인 및 암호',
    'register' => '가입',
    'welcome_to_2fauth' => '2FAuth를 시작해보세요',
    'autolock_triggered' => '자동 잠금 설정됨',
    'autolock_triggered_punchline' => '자동 잠금이 설정되어 로그아웃되었습니다',
    'already_authenticated' => '이미 인증되었습니다. 먼저 로그아웃해주세요.',
    'authentication' => '인증',
    'maybe_later' => '나중에 하기',
    'user_account_controlled_by_proxy' => '인증 프록시에서 사용할 수 있는 사용자 계정입니다.<br />프록시를 통해 계정을 관리할 수 있습니다.',
    'auth_handled_by_proxy' => '역방향 프록시에서 인증이 처리되므로, 아래 설정은 비활성화됩니다.<br />프록시를 통해 인증을 관리할 수 있습니다.',
    'sso_only_x_settings_are_disabled' => 'Authentication is restricted to SSO only, :auth_method is disabled',
    'confirm' => [
        'logout' => '정말 로그아웃 하시겠습니까?',
        'revoke_device' => '이 장치를 삭제하시겠습니까?',
        'delete_account' => '정말 계정을 삭제하시겠습니까?',
    ],
    'webauthn' => [
        'security_device' => '보안 장치',
        'security_devices' => '보안 장치',
        'security_devices_legend' => '보안 키(예: Yubikey) 또는 생체 인식 기능이 있는 스마트폰(예: Apple FaceId/TouchId)과 같은 2FAuth 로그인에 사용할 수 있는 인증 장치',
        'enhance_security_using_webauthn' => 'WebAuthn 인증을 활성화하여 2FAuth 계정의 보안을 강화할 수 있습니다.<br /><br />
            WebAuthn을 사용하면 신뢰할 수 있는 장치(예: Yubikeys 또는 생체 인식 기능이 있는 스마트폰) 를 사용하여 빠르고 안전하게 로그인할 수 있습니다.',
        'use_security_device_to_sign_in' => '보안 기기로 인증할 준비를 하세요. 보안키를 연결하거나, 마스크나 장갑을 벗는 등의 준비를 해주세요.',
        'lost_your_device' => '기기를 분실하셨나요?',
        'recover_your_account' => '계정 복구',
        'account_recovery' => '계정 복구',
        'recovery_punchline' => '2FAuth가 이메일 주소로 복구 링크를 전송합니다. 수신한 이메일의 링크를 클릭하고 지침을 따르세요.<br /><br />본인 소유의 기기에서 이메일을 열어야 합니다.',
        'send_recovery_link' => '복구 링크 전송',
        'account_recovery_email_sent' => '계정 복구 이메일 전송됨!',
        'disable_all_security_devices' => '모든 보안 장치 비활성화',
        'disable_all_security_devices_help' => '모든 보안 장치가 비활성화됩니다. 보안 장치를 분실했거나 보안 위협이 발생한 경우 이 옵션을 사용하세요.',
        'register_a_new_device' => '장치 등록',
        'register_a_device' => '장치 등록',
        'device_successfully_registered' => '장치가 성공적으로 등록됨',
        'device_revoked' => '장치가 성공적으로 비활성화됨',
        'revoking_a_device_is_permanent' => '디바이스 비활성화는 영구적입니다.',
        'recover_account_instructions' => '계정을 복구하기 위해 2FAuth는 일부 Webauthn 설정을 초기화하여 이메일과 비밀번호를 사용하여 로그인할 수 있도록 합니다.',
        'invalid_recovery_token' => '유효하지 않은 복구 코드',
        'webauthn_login_disabled' => 'Webauthn 로그인 비활성화',
        'invalid_reset_token' => '이 재설정 토큰은 유효하지 않습니다.',
        'rename_device' => '장치 이름 변경',
        'my_device' => '내 장치',
        'unknown_device' => '알 수 없는 장치',
        'use_webauthn_only' => [
            'label' => 'WebAuthn만 사용',
            'help' => 'WebAuthn을 2FAuth 계정 로그인의 유일한 인증 방식으로 설정합니다. 이는 WebAuthn의 향상된 보안을 활용하기 위한 권장 설정입니다.<br /><br />
                기기를 분실한 경우, 이 옵션을 해제하고 이메일과 비밀번호로 로그인하여 계정을 복구할 수 있습니다.<br /><br />
                주의! 이 옵션을 활성화해도 이메일 & 비밀번호 입력란은 계속 표시되지만, 항상 \'로그인 실패\' 응답을 반환합니다.'
        ],
        'need_a_security_device_to_enable_options' => '다음 옵션을 사용하도록 하나 이상의 장치를 설정합니다.',
        'options' => '옵션',
    ],
    'forms' => [
        'name' => '이름',
        'login' => '로그인',
        'webauthn_login' => 'WebAuthn 로그인',
        'sso_login' => 'SSO login',
        'email' => '이메일',
        'password' => '비밀번호',
        'reveal_password' => '비밀번호 표시',
        'hide_password' => '비밀번호 숨김',
        'confirm_password' => '비밀번호 확인',
        'new_password' => '새 비밀번호',
        'confirm_new_password' => '새 비밀번호 확인',
        'dont_have_account_yet' => '아직 계정이 없으신가요?',
        'already_register' => '이미 가입하셨습니까?',
        'authentication_failed' => '인증 실패',
        'forgot_your_password' => '비밀번호를 잊으셨나요?',
        'request_password_reset' => '재설정',
        'reset_your_password' => '비밀번호 재설정',
        'reset_password' => '비밀번호 재설정',
        'disabled_in_demo' => '데모에서 비활성화된 기능',
        'sso_only_form_restricted_to_admin' => 'Regular users must sign in with SSO. Other methods are for administrators only.',
        'new_password' => '새 비밀번호',
        'current_password' => [
            'label' => '현재 비밀번호',
            'help' => '본인임을 확인하기 위해 현재 비밀번호를 입력하세요'
        ],
        'change_password' => '비밀번호 변경',
        'send_password_reset_link' => '비밀번호 재설정 링크 전송',
        'password_successfully_reset' => '비밀번호가 성공적으로 재설정됨',
        'edit_account' => '계정 수정',
        'profile_saved' => '프로필이 업데이트되었습니다!',
        'welcome_to_demo_app_use_those_credentials' => '2FAuth 데모를 시작하세요.<br><br>다음 이메일 주소와 비밀번호를 입력해 접속할 수 있습니다. 이메일: <strong>demo@2fauth.app</strong> 비밀번호: <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => '2FAuth 테스트를 시작하세요.<br><br>다음 이메일 주소와 비밀번호를 입력해 접속할 수 있습니다. 이메일: <strong>testing@2fauth.app</strong> 비밀번호: <strong>password</strong>',
        'register_punchline' => '<b>2FAuth</b>에 오신 것을 환영합니다.<br/>계속 진행하려면 계정이 필요하므로 가입해주세요.',
        'reset_punchline' => '2FAuth에서 이 주소로 비밀번호 재설정 링크를 보내드립니다. 받은 이메일의 링크를 클릭하여 새 비밀번호를 설정하세요.',
        'name_this_device' => '장치 이름',
        'delete_account' => '계정 삭제',
        'delete_your_account' => '계정 삭제',
        'delete_your_account_and_reset_all_data' => '사용자 계정과 모든 2FA 데이터가 삭제됩니다. 되돌릴 수 없습니다.',
        'reset_your_password_to_delete_your_account' => '항상 SSO를 사용하여 로그인하는 경우 로그아웃한 다음 비밀번호 재설정 기능을 사용하여 여기에 입력할 수 있는 비밀번호를 확인하세요.',
        'deleting_2fauth_account_does_not_impact_provider' => '2FAuth 계정을 삭제해도 외부 SSO 계정에는 영향을 미치지 않습니다.',
        'user_account_successfully_deleted' => '계정을 성공적으로 삭제했습니다',
        'has_lower_case' => '소문자 포함',
        'has_upper_case' => '대문자 포함',
        'has_special_char' => '특수문자 포함',
        'has_number' => '숫자 포함',
        'is_long_enough' => '8자 이상',
        'mandatory_rules' => '필수',
        'optional_rules_you_should_follow' => '(강력히) 권장',
        'caps_lock_is_on' => 'Caps Lock 켜짐',
    ],
    'sso_providers' => [
        'unknown' => 'unknown',
        'github' => 'Github',
        'openid' => 'OpenID'
    ]
];
