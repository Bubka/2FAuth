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

    'hello' => '안녕하세요',
    'hello_user' => '{username}님 안녕하세요,',
    'regards' => '만나서 반갑습니다',
    'test_email_settings' => [
        'subject' => '2FAuth 테스트 이메일',
        'reason' => '2FAuth 인스턴스의 이메일 설정을 확인하기 위해 테스트 이메일을 요청했으므로 이 이메일이 전송되었습니다.',
        'success' => '좋은 소식이네요, 잘 작동합니다 :)'
    ],
    'new_device' => [
        'subject' => '새로운 기기에서 2fAuth에 연결했습니다.',
        'resume' => '새 장치가 방금 2FAuth 계정에 연결되었습니다.',
        'connection_details' => '이 연결에 대한 자세한 내용은 다음과 같습니다.',
        'recommandations' => '이 경우 이 알림을 무시해도 됩니다. 계정에서 의심스러운 활동이 의심되는 경우 비밀번호를 변경하시기 바랍니다.'
    ],
    'failed_login' => [
        'subject' => '2FAuth 로그인 실패',
        'resume' => '사용자의 2FAuth 계정에서의 로그인 시도가 실패했습니다.',
        'connection_details' => '이 로그인 시도에 대한 자세한 내용은 다음과 같습니다.',
        'recommandations' => '사용자 본인이라면 이 경고를 무시해도 됩니다. 추가 시도가 실패하면 2FAuth 관리자에게 연락하여 보안 설정을 검토하고 이 공격자에 대한 조치를 취해야 합니다.'
    ],
];