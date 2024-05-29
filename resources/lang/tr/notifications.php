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

    'hello' => 'Merhaba',
    'hello_user' => 'Merhaba :username,',
    'regards' => 'Saygılar',
    'test_email_settings' => [
        'subject' => '2FAuth deneme ePostası',
        'reason' => 'Bu ePostayı, ePosta yapılandırmanızın doğruluğunu test etmek amacıyla bir deneme ePostası istediğiniz için aldınız.',
        'success' => 'Haberler iyi, çalışıyor :)'
    ],
    'new_device' => [
        'subject' => 'Connection to 2FAuth from a new device',
        'resume' => '2FAuth hesabınıza yeni bir cihaz bağlandı.',
        'connection_details' => 'Bu bağlantının detayları',
        'recommandations' => 'Eğer bu sizseniz, bu uyarı gözardı edebilirsiniz. Eğer hesabınızda şüpheli bir hareket olduğunu düşünüyorsanız, lütfen parolanızı değiştirin.'
    ],
    'failed_login' => [
        'subject' => '2FAuth uygulamasına başarısız giriş',
        'resume' => '2FAuth hesabınıza başarısız bir giriş denemesi yapıldı.',
        'connection_details' => 'Bu giriş denemesinin detayları şöyle',
        'recommandations' => 'If this was you, you can ignore this alert. If further attempts fail, you should contact the 2FAuth administrator to review security settings and take action against this attacker.'
    ],
];