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

    'service' => '서비스',
    'account' => '계정',
    'icon' => '아이콘',
    'icon_to_illustrate_the_account' => '계정을 설명하는 아이콘',
    'remove_icon' => '아이콘 제거',
    'no_account_here' => '처음이신가요?',
    'add_first_account' => '다음과 같은 방법으로 첫 번째 계정을 추가하세요.',
    'use_full_form' => '또는 양식으로',
    'add_one' => '추가',
    'show_qrcode' => 'QR코드 표시',
    'no_service' => '- 서비스 없음 -',
    'account_created' => '계정이 생성되었습니다!',
    'account_updated' => '계정이 업데이트되었습니다',
    'accounts_deleted' => '계정이 삭제되었습니다',
    'accounts_moved' => '계정이 이동되었습니다',
    'export_selected_accounts' => '선택한 계정 내보내기',
    'twofauth_export_format' => '2FAuth 형식',
    'twofauth_export_format_sub' => '2FAuth json schema를 사용하여 데이터 내보내기',
    'twofauth_export_format_desc' => '백업 데이터를 저장하는 경우에는 이 형식을 사용하는 것이 좋습니다. 아이콘을 보존합니다.',
    'twofauth_export_format_url' => 'Schema 정의 서술:',
    'twofauth_export_schema' => '2FAuth schema 내보냄',
    'otpauth_export_format' => 'otpauth URIs',
    'otpauth_export_format_sub' => '데이터를 otpauth URIs 형식으로 내보내기',
    'otpauth_export_format_desc' => 'otpauth URI는 웹사이트에서 2FA를 활성화할 때 사용되는 QR 코드와 같이 2FA 데이터를 교환하는 데에 사용되는 가장 보편적인 형식입니다. 2FAuth에서 다른 서비스로 전환하는 경우에 선택하십시오.',
    'reveal' => '표시',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => '홍길동',
        ],
        'new_account' => '새 계정',
        'edit_account' => '계정 편집',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'QR코드 스캔',
        'upload_qrcode' => 'QR코드 업로드',
        'use_advanced_form' => '고급 양식 사용',
        'prefill_using_qrcode' => 'QR코드로 부터 자동 입력',
        'use_qrcode' => [
            'val' => 'QR코드 사용',
            'title' => 'QR 코드를 사용하여 마법처럼 양식을 채우세요',
        ],
        'unlock' => [
            'val' => '잠금해제',
            'title' => '잠금 해제 (본인 책임)',
        ],
        'lock' => [
            'val' => '잠금',
            'title' => '잠그기',
        ],
        'choose_image' => '업로드',
        'i_m_lucky' => '자동으로 불러오기',
        'i_m_lucky_legend' => '\'자동으로 불러오기\' 버튼은 이 서비스의 공식 아이콘을 가져오려고 시도합니다. ".xyz"와 같은 도메인을 제외한 실제 서비스 이름을 오타 없이 입력해 주세요. (베타 기능)',
        'test' => '테스트',
        'group' => [
            'label' => '그룹',
            'help' => '계정을 할당할 그룹'
        ],
        'secret' => [
            'label' => '시크릿키',
            'help' => '보안 코드를 생성하는 데 사용되는 키'
        ],
        'plain_text' => '일반 텍스트',
        'otp_type' => [
            'label' => 'Choose the type of OTP to create',
            'help' => '시간 기반 OTP, HMAC 기반 OTP, Steam OTP 중 선택 가능'
        ],
        'digits' => [
            'label' => '자릿수',
            'help' => '생성된 보안 코드의 자릿수'
        ],
        'algorithm' => [
            'label' => '알고리즘',
            'help' => '보안 코드 보안에 사용되는 알고리즘'
        ],
        'period' => [
            'label' => '주기',
            'placeholder' => '기본값 30',
            'help' => '생성된 보안 코드의 유효 기간(초)'
        ],
        'counter' => [
            'label' => '카운터',
            'placeholder' => '기본값 0',
            'help' => '초기 카운터 값',
            'help_lock' => '카운터 값을 편집하면 계정과 서비스 인증 서버의 동기화가 해제될 수 있습니다. 이를 이해한 경우에만 자물쇠 아이콘을 통해 이 항목의 변경을 허용해 주세요.'
        ],
        'image' => [
            'label' => '이미지',
            'placeholder' => 'http://...',
            'help' => '계정 아이콘으로 사용할 외부 이미지의 URL'
        ],
        'options_help' => '설정 방법을 모른다면 다음 옵션을 비워 두어도 됩니다. 가장 일반적으로 사용되는 값이 적용됩니다.',
        'alternative_methods' => '다른 방법',
        'spaces_are_ignored' => '불필요한 공백은 자동으로 제거됩니다.'
    ],
    'stream' => [
        'live_scan_cant_start' => '스캔할 수 없습니다 (ㅠ_ㅠ)',
        'need_grant_permission' => [
            'reason' => '카메라에 액세스할 수 있는 권한이 없습니다',
            'solution' => '장치의 카메라 접근을 허용해 주세요. 이전에 액세스를 거부하여 브라우저가 다시 권한을 요청하지 않을 경우 브라우저 문서를 참고해 권한을 허용해주세요.',
            'click_camera_icon' => '일반적으로 브라우저의 주소 표시줄 안 또는 옆에 있는 카메라 아이콘을 클릭하여 권한을 허용할 수 있습니다.',
        ],
        'not_readable' => [
            'reason' => '스캐너 로드 실패',
            'solution' => '카메라가 이미 사용 중입니까? 다른 앱에서 카메라를 사용하지 않도록 하고 다시 시도해주세요.'
        ],
        'no_cam_on_device' => [
            'reason' => '기기에 카메라 없음',
            'solution' => '웹캠을 연결하는 것을 잊지 않았나요?'
        ],
        'secured_context_required' => [
            'reason' => '보안 context 필요',
            'solution' => '스캔을 위해서는 HTTPS가 필요합니다. 컴퓨터에서 2FAuth를 실행하는 경우 로컬 호스트 이외의 가상 호스트를 사용하지 마십시오.'
        ],
        'https_required' => '카메라 스트리밍을 위해 HTTPS 필요',
        'camera_not_suitable' => [
            'reason' => '연결된 카메라가 지원되지 않습니다',
            'solution' => '다른 장치나 카메라를 사용해주세요.'
        ],
        'stream_api_not_supported' => [
            'reason' => '이 브라우저에서 Stream API가 지원되지 않습니다
',
            'solution' => '최신 브라우저를 사용해주세요.'
        ],
    ],
    'confirm' => [
        'delete' => '정말로 이 계정을 삭제하시겠습니까?',
        'cancel' => '변경사항이 저장되지 않습니다. 계속하시겠습니까?',
        'discard' => '이 계정을 정말 삭제하시겠습니까?',
        'discard_all' => '정말 모든 계정을 삭제하시겠습니까?',
        'discard_duplicates' => '정말 모든 중복 항목을 삭제하시겠습니까?',
    ],
    'import' => [
        'import' => '가져오기',
        'to_import' => '가져오기',
        'import_legend' => '2FAuth는 다양한 2FA 앱에서 데이터를 가져올 수 있습니다.',
        'import_legend_afterpart' => '다른 앱의 내보내기 기능을 사용하여 QR 코드 또는 JSON 파일과 같은 마이그레이션 리소스를 가져온 다음 여기에 불러옵니다.',
        'upload' => '업로드',
        'scan' => '스캔',
        'supported_formats_for_qrcode_upload' => '허용되는 형식: jpg, jpeg, png, bmp, gif, svg, webp',
        'supported_formats_for_file_upload' => '허용되는 형식: 일반 텍스트, json, 2fas',
        'expected_format_for_direct_input' => '형식: 1행에 1개의 otpauth URI',
        'supported_migration_formats' => '지원되는 마이그레이션 형식',
        'qr_code' => 'QR코드',
        'text_file' => '텍스트 파일',
        'direct_input' => '직접 입력',
        'plain_text' => '일반 텍스트',
        'parsing_data' => '데이터 파싱 중...',
        'issuer' => '발행자',
        'imported' => '불러옴',
        'failure' => '실패',
        'x_valid_accounts_found' => ':count개의 유효한 계정 찾음',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => '마이그레이션 리소스에 다음 2FA 계정이 있습니다. 2FAuth에는 아직 추가되지 않았습니다.',
        'use_buttons_to_save_or_discard' => '각 버튼을 사용하여 가져올지 삭제할지 선택해 주세요.',
        'import_all' => '모두 가져오기',
        'import_this_account' => '이 계정 가져오기',
        'discard_all' => '모두 삭제',
        'discard_duplicates' => '모든 중복 삭제',
        'discard_this_account' => '이 계정 삭제',
        'generate_a_test_password' => '테스트 비밀번호 생성',
        'possible_duplicate' => '완전히 동일한 계정이 이미 존재합니다',
        'invalid_account' => '- 올바르지 않은 계정 -',
        'invalid_service' => '- 올바르지 않은 서비스 -',
        'do_not_set_password_or_encryption' => '2FA 인증 앱에서 데이터를 내보낼 때 비밀번호 보호 및 암호화를 적용하지 마세요. 2FAuth로 데이터를 불러올 수 없습니다.',
    ],

];