<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'settings' => '설정',
    'preferences' => '설정',
    'account' => '계정',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => '토큰',
    'options' => '옵션',
    'user_preferences' => '사용자 개인 설정',
    'admin_settings' => '관리자 설정',
    'confirm' => [

    ],
    'you_are_administrator' => '당신은 관리자입니다',
    'account_linked_to_sso_x_provider' => ':provider 계정을 사용하여 SSO를 통해 로그인했습니다. 여기에서 정보를 변경할 수 없고 :provider에서 변경할 수 있습니다.',
    'general' => '일반',
    'security' => '보안',
    'notifications' => '알림',
    'profile' => '프로필',
    'change_password' => '비밀번호 변경',
    'personal_access_tokens' => '개인 액세스 토큰',
    'token_legend' => '개인 액세스 토큰을 사용하면 모든 앱이 2FAuth API에 인증할 수 있습니다. 클라이언트 앱 요청의 authorization 헤더에 액세스 토큰을 Bearer token으로 지정해야 합니다.',
    'generate_new_token' => '새 토큰 생성',
    'revoke' => '삭제',
    'token_revoked' => '토큰이 성공적으로 비활성화됨',
    'revoking_a_token_is_permanent' => '토큰 비활성화는 영구적입니다.',
    'confirm' => [
        'revoke' => '정말 이 토큰을 삭제하시겠습니까?',
    ],
    'make_sure_copy_token' => '개인 액세스 토큰을 복사해두세요. 다시 확인할 수 없습니다!',
    'data_input' => '데이터 입력',
    'settings_managed_by_administrator' => 'Some settings are being managed by your administrator',
    'forms' => [
        'edit_settings' => '설정 변경',
        'setting_saved' => '설정 저장됨',
        'new_token' => '새 토큰',
        'some_translation_are_missing' => '브라우저의 기본 언어에서 일부 번역이 누락되었나요?',
        'help_translate_2fauth' => '2FAuth 번역 기여',
        'language' => [
            'label' => '언어',
            'help' => '2FAuth 사용자 인터페이스를 번역하는 데 사용되는 언어입니다. 표시된 언어는 번역이 완료된 언어이며, 원하는 언어로 설정하여 브라우저 기본 설정을 무시할 수 있습니다.'
        ],
        'timezone' => [
            'label' => '시간대',
            'help' => '서비스에 표시되는 모든 날짜와 시간에 적용되는 표준 시간대'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated OTP as dot',
            'help' => 'Replace generated password characters with *** to ensure confidentiality. Does not affect the copy/paste feature'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured OTP',
            'help' => '점으로 가려진 비밀번호를 일시적으로 표시합니다.'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close OTP after copy',
            'help' => '생성된 비밀번호를 클릭하여 복사하면 화면에서 비밀번호가 자동으로 숨겨집니다.'
        ],
        'show_next_otp' => [
            'label' => 'Show next OTP',
            'help' => 'Preview the next password, i.e. the password that will replace the current password when it expires. Preferences set for the current OTP also apply to the next one (formatting, show as dot)'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close OTP',
            'help' => '일정 시간이 지나면 화면에 표시된 비밀번호를 자동으로 숨깁니다. 비밀번호 보기를 닫는 것을 잊어버렸을 때 불필요한 비밀번호 요청을 피할 수 있습니다.'
        ],
        'clear_search_on_copy' => [
            'label' => '복사 후 검색 창 지우기',
            'help' => '코드를 클립보드에 복사한 직후 검색 창을 비웁니다.'
        ],
        'sort_case_sensitive' => [
            'label' => '대소문자 구분 정렬',
            'help' => '정렬 함수가 호출되면 대소문자를 구분하여 계정을 정렬하도록 강제합니다.'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copy OTP on display',
            'help' => '생성된 비밀번호가 화면에 나타난 직후 자동으로 복사됩니다. 브라우저의 제한으로 인해 갱신된 비밀번호는 복사되지 않으며, 처음 <abbr title="Time-based One-Time Password">TOTP</abbr> 비밀번호만 복사됩니다.'
        ],
        'use_basic_qrcode_reader' => [
            'label' => '기본 QR 코드 리더기 사용',
            'help' => 'QR 코드를 캡처할 때 문제가 발생하는 경우 이 옵션을 활성화하여 기본 QR 코드 리더기를 사용할 수 있습니다.'
        ],
        'display_mode' => [
            'label' => '보기 방식',
            'help' => '계정을 목록으로 표시할지 그리드로 표시할지 선택합니다.'
        ],
        'password_format' => [
            'label' => '비밀번호 서식 지정',
            'help' => '비밀번호를 더 쉽게 읽고 기억할 수 있도록 숫자를 그룹화하여 표시 방식을 변경합니다.'
        ],
        'pair' => '두 자리씩',
        'pair_legend' => '숫자를 두 자리씩 그룹화',
        'trio_legend' => '숫자를 세 자리씩 그룹화',
        'half_legend' => '숫자를 두 개의 동일한 그룹으로 분할',
        'trio' => '세 자리씩',
        'half' => '절반씩',
        'grid' => '그리드',
        'list' => '목록',
        'theme' => [
            'label' => '테마',
            'help' => '특정 테마를 강제로 적용하거나 시스템/브라우저에 설정된 테마를 적용합니다.'
        ],
        'light' => '라이트',
        'dark' => '다크',
        'automatic' => '자동',
        'show_accounts_icons' => [
            'label' => '아이콘 표시',
            'help' => '메인 화면에 계정 아이콘을 표시합니다'
        ],
        'get_official_icons' => [
            'label' => '공식 아이콘 불러오기',
            'help' => '계정을 추가할 때 가능한 경우, 2FA 발급자의 공식 아이콘을 불러옵니다.'
        ],
        'auto_lock' => [
            'label' => '자동 잠금',
            'help' => '활동이 없는 경우 사용자를 자동으로 로그아웃합니다. 프록시에 의해 인증이 처리되고 사용자 지정 로그아웃 Url이 지정되지 않은 경우에는 작동하지 않습니다.'
        ],
        'default_group' => [
            'label' => '기본 그룹',
            'help' => '새로 만든 계정이 연결된 그룹',
        ],
        'view_default_group_on_copy' => [
            'label' => '복사 후 기본 그룹 표시',
            'help' => 'OTP를 복사 한 후 항상 기본 그룹으로 돌아갑니다.',
        ],
        'auto_save_qrcoded_account' => [
            'label' => '계정 자동 저장',
            'help' => 'QR코드를 스캔 또는 업로드하면 \'저장\' 버튼을 누르지 않아도 자동으로 새 계정을 등록할 수 있습니다.',
        ],
        'useDirectCapture' => [
            'label' => '직접 입력',
            'help' => '사용 가능한 입력 모드 중에서 입력 모드를 선택하라는 메시지를 표시할지 아니면 직접 입력 모드를 바로 사용할지 선택합니다.',
        ],
        'defaultCaptureMode' => [
            'label' => '기본 입력 모드',
            'help' => '직접 입력 옵션이 켜짐일 때 사용되는 기본 입력 모드',
        ],
        'remember_active_group' => [
            'label' => '그룹 필터 기억',
            'help' => '다음 접속 시 마지막으로 사용한 그룹 필터를 적용합니다.',
        ],
        'otp_generation' => [
            'label' => '비밀번호 표시',
            'help' => '<abbr title="One-Time Passwords">OTP</abbr>가 표시되는 방법과 시기를 설정합니다.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => '새로운 기기 연결 시',
            'help' => '새 장치가 2FAuth 계정에 처음으로 연결되면 이메일 받기'
        ],
        'notify_on_failed_login' => [
            'label' => '로그인 실패 시',
            'help' => '2FAuth 계정에 로그인 시도가 실패할 때마다 이메일 받기'
        ],
        'show_email_in_footer' => [
            'label' => '하단 영역에 이메일을 표시합니다',
            'help' => '하단 영역에 직접 네비게이션 링크 대신 로그인된 사용자의 이메일을 표시합니다. 이메일을 클릭/탭하여 표시되는 메뉴에서 링크에 접근할 수 있습니다.'
        ],
        'otp_generation_on_request' => '클릭/탭 후',
        'otp_generation_on_request_legend' => '개별 화면으로 열기',
        'otp_generation_on_request_title' => '계정을 클릭하여 개별 화면에서 비밀번호 열기',
        'otp_generation_on_home' => '항상',
        'otp_generation_on_home_legend' => '홈 화면의 모든 것',
        'otp_generation_on_home_title' => '아무것도 하지 않아도 메인 화면에 모든 비밀번호를 표시',
        'never' => '안 함',
        'on_otp_copy' => '보안 키가 복사될 때',
        '1_minutes' => '1분 후',
        '2_minutes' => '2분 후',
        '5_minutes' => '5분 후',
        '10_minutes' => '10분 후에',
        '15_minutes' => '15분 후',
        '30_minutes' => '30분 후',
        '1_hour' => '1시간 후',
        '1_day' => '1일 후',
        'livescan' => 'QR 실시간 스캔',
        'upload' => 'QR코드 업로드',
        'advanced_form' => '고급 양식',
    ],

];