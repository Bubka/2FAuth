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

    'admin' => '관리자',
    'admin_panel' => '관리자 패널',
    'app_setup' => '앱 설정',
    'auth' => '인증',
    'registrations' => '가입',
    'users' => '사용자',
    'users_legend' => '인스턴스에 등록된 사용자를 관리하거나 새 사용자를 추가합니다.',
    'admin_settings' => '관리자 설정',
    'create_new_user' => '사용자 추가',
    'new_user' => '새로운 사용자',
    'search_user_placeholder' => '사용자 이름, 이메일...',
    'quick_filters_colons' => '빠른 필터:',
    'user_created' => '사용자가 추가됨',
    'confirm' => [
        'delete_user' => '정말 이 사용자를 삭제하시겠습니까? 되돌릴 수 없습니다.',
        'request_password_reset' => '이 사용자의 비밀번호를 재설정하시겠습니까?',
        'purge_password_reset_request' => '이전 요청을 정말 취소하시겠습니까?',
        'delete_account' => '정말 이 사용자를 삭제하시겠습니까?',
        'edit_own_account' => '본인의 계정입니다. 계속하시겠습니까?',
        'change_admin_role' => '이 사용자의 권한에 중요한 영향을 미칩니다. 계속하시겠습니까?',
        'demote_own_account' => '당신은 더 이상 관리자가 아니게 됩니다. 정말 계속하시겠습니까?'
    ],
    'logs' => '로그',
    'administration_legend' => '다음 설정은 전역적이며 모든 사용자에게 적용됩니다.',
    'user_management' => '사용자 관리',
    'oauth_provider' => 'OAuth 제공자',
    'account_bound_to_x_via_oauth' => '이 계정은 OAuth를 통해 :provider 계정과 연결되어 있습니다',
    'last_seen_on_date' => '마지막 접속 :date',
    'registered_on_date' => '가입일 :date',
    'updated_on_date' => '수정일 :date',
    'access' => '접근',
    'password_requested_on_t' => '이 사용자에 대한 비밀번호 재설정 요청이 있습니다(요청이 :datetime에 전송됨). 이는 사용자가 아직 비밀번호를 변경하지 않았지만 전송된 링크가 여전히 유효하다는 의미입니다. 이 요청은 사용자 본인 또는 관리자에 의한 것일 수 있습니다.',
    'password_request_expired' => '이 사용자에 대한 비밀번호 재설정 요청이 있지만 만료되었으므로 사용자가 제때 비밀번호를 변경하지 않았음을 의미합니다. 이 요청은 사용자 본인 또는 관리자에 의한 것일 수 있습니다.',
    'resend_email' => '이메일 재전송',
    'resend_email_title' => '사용자에게 비밀번호 재설정 이메일 재전송',
    'resend_email_help' => '<b>이메일 재전송</b>을 통해 사용자에게 비밀번호를 재설정할 수 있도록 비밀번호 재설정 이메일을 새로 보냅니다. 이렇게 하면 현재 비밀번호는 그대로 유지되며 이전 요청은 모두 취소됩니다.',
    'reset_password' => '비밀번호 재설정',
    'reset_password_help' => '사용자에게 새 비밀번호를 설정할 수 있도록 비밀번호 재설정 이메일을 보내기 전에 <b>비밀번호 재설정</b>를 통해 비밀번호를 강제로 재설정(임시 비밀번호가 설정됨) 하고, 새 비밀번호를 설정할 수 있게 합니다. 이전 요청은 모두 취소됩니다.',
    'reset_password_title' => '사용자 비밀번호 재설정',
    'password_successfully_reset' => '비밀번호가 성공적으로 재설정됨',
    'user_has_x_active_pat' => ':count개의 활성 토큰',
    'user_has_x_security_devices' => ':count개의 보안 장치 (패스키)',
    'revoke_all_pat_for_user' => '모든 사용자의 토큰 비활성화',
    'revoke_all_devices_for_user' => '모든 사용자의 보안 장치 비활성화',
    'danger_zone' => '위험 구역',
    'delete_this_user_legend' => '사용자 계정은 모든 2FA 데이터와 함께 삭제됩니다.',
    'this_is_not_soft_delete' => '이는 영구 삭제이며, 되돌릴 수 없습니다.',
    'delete_this_user' => '이 사용자 삭제',
    'user_role_updated' => '사용자 역할 업데이트됨',
    'pats_succesfully_revoked' => '사용자의 PATs가 비활성화되었습니다.',
    'security_devices_succesfully_revoked' => '사용자의 보안 장치가 비활성화됨',
    'variables' => '변수',
    'cache_cleared' => '캐시 삭제됨',
    'cache_optimized' => '캐시 최적화됨',
    'check_now' => '지금 확인',
    'view_on_github' => 'Github에서 보기',
    'x_is_available' => '새 :version 버전 이용 가능',
    'successful_login_on' => '<span class="light-or-darker">:login_at</span>에서 로그인',
    'successful_logout_on' => '<span class="light-or-darker">:login_at</span>에서 로그아웃 성공',
    'failed_login_on' => '<span class="light-or-darker">:login_at</span>에서 로그인 실패',
    'viewed_on' => '<span class="light-or-darker">:login_at</span>에서 열람',
    'last_accesses' => '마지막 접속',
    'see_full_log' => '모든 로그 보기',
    'browser_on_platform' => ':browser on :platform',
    'access_log_has_more_entries' => '접속 로그에는 더 많은 항목이 포함되어 있습니다.',
    'access_log_legend_for_user' => ':username 사용자에 대한 전체 접속 로그',
    'show_last_month_log' => '지난 달 항목 표시',
    'show_three_months_log' => '지난 3개월 항목 표시',
    'show_six_months_log' => '지난 6개월 항목 표시',
    'show_one_year_log' => '지난 해 항목 표시',
    'sort_by_date_asc' => '오래된 순으로 표시',
    'sort_by_date_desc' => '최신 순으로 표시',
    'single_sign_on' => '통합 인증(SSO)',
    'database' => '데이터베이스',
    'file_system' => '파일 시스템',
    'storage' => '저장공간',
    'forms' => [
        'use_encryption' => [
            'label' => '민감한 데이터 보호',
            'help' => '민감한 데이터인 2FA 시크릿키와 이메일은 데이터베이스에 암호화되어 저장됩니다. .env 파일(또는 전체 파일)의 APP_KEY 값은 키 암호화 역할을 하므로 반드시 백업하세요. 이 키 없이는 암호화된 데이터를 복호화할 방법이 없습니다.',
        ],
        'restrict_registration' => [
            'label' => '가입 제한',
            'help' => '제한된 이메일 주소만 가입할 수 있도록 설정합니다. 두 규칙을 동시에 사용할 수 있습니다. 이는 SSO를 통한 가입에는 영향을 미치지 않습니다.',
        ],
        'restrict_list' => [
            'label' => '필터링 목록',
            'help' => '이 목록에 있는 이메일은 가입이 허용됩니다. 세로 막대("|") 로 주소를 구분합니다.',
        ],
        'restrict_rule' => [
            'label' => '필터링 규칙',
            'help' => '이 정규식과 일치하는 이메일은 가입이 허용됩니다.',
        ],
        'disable_registration' => [
            'label' => '가입 비활성화',
            'help' => '새 사용자 가입을 방지합니다. 재정의하지 않는 한(아래 참조), 이 설정은 SSO에도 영향을 미치므로 새 사용자는 SSO를 통해 로그인할 수 없습니다.',
        ],
        'enable_sso' => [
            'label' => 'SSO 활성화',
            'help' => '방문자가 Single Sign-On 을 통해 외부 ID를 사용하여 인증할 수 있도록 허용',
        ],
        'use_sso_only' => [
            'label' => 'SSO만 사용',
            'help' => '2FAuth 로그인 수단을 SSO만으로 제한합니다. 일반 사용자의 비밀번호 로그인과 웹 인증(WebAuthn)이 비활성화됩니다. 관리자는 이 제한의 영향을 받지 않습니다.',
        ],
        'allow_pat_in_sso_only' => [
            'label' => 'Allow PAT usage',
            'help' => 'Let users create personal access tokens and use them to authenticate with third party application like the 2FAuth web extension',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'SSO 가입을 활성화된 상태로 유지',
            'help' => '등록은 비활성화 되었어도 신규 사용자가 SSO를 통해 처음 로그인할 수 있도록 허용',
        ],
        'is_admin' => [
            'label' => '관리자 여부',
            'help' => '사용자에게 관리자 권한을 부여합니다. 관리자는 \'설정\' 및 \'사용자\'와 같은 전체 앱을 관리할 수 있는 권한을 갖지만 자신이 소유하지 않은 2FA의 비밀번호를 생성할 수는 없습니다.'
        ],
        'test_email' => [
            'label' => '이메일 구성 테스트',
            'help' => '인스턴스의 이메일 설정을 확인하기 위해 테스트 이메일을 보내세요. 설정이 정상적으로 되어 있지 않으면, 사용자가 비밀번호 재설정을 요청할 수 없습니다.',
            'email_will_be_send_to_x' => '이메일이 <span class="is-family-code has-text-info">:email</span>로 전송됩니다.',
        ],
        'health_endpoint' => [
            'label' => 'Health 엔드포인트',
            'help' => '2FAuth 인스턴스 상태를 확인하기 위해 방문할 수 있는 URL입니다. 이 URL은 Docker HEALTHCHECK 또는 Kubernetes HTTPS Liveness probe를 설정하는 데에 사용할 수 있습니다.',
        ],
        'cache_management' => [
            'label' => '캐시 관리',
            'help' => '환경 변수를 변경하거나 업데이트한 후와 같은 경우 캐시를 삭제해야 할 수 있습니다. 아래에서 삭제할 수 있습니다.',
        ],
        'store_icon_to_database' => [
            'label' => '데이터베이스에 아이콘 저장',
            'help' => '업로드된 아이콘은 파일 시스템 저장소 외에도 데이터베이스에 등록되어 캐시로 사용됩니다. 데이터베이스만 백업하면 되므로 2FAuth 백업을 더 간단하게 만들 수 있습니다.<br /><br />하지만 몇 가지 단점에 유의해야 합니다: 인스턴스에 큰 아이콘이 많이 있는 경우 데이터베이스 크기가 증가할 수 있습니다. 또한 파일 시스템이 데이터베이스와 동기화하기 위해 더 자주 접속하므로 애플리케이션 성능에 영향을 미칠 수 있습니다.',
        ],
    ],

];