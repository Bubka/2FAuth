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
        'subject' => '2FAuth\'a yeni bir cihazdan erişim',
        'resume' => '2FAuth hesabınıza yeni bir cihaz bağlandı.',
        'connection_details' => 'Bu bağlantının detayları',
        'recommandations' => 'Eğer bu sizseniz, bu uyarı gözardı edebilirsiniz. Eğer hesabınızda şüpheli bir hareket olduğunu düşünüyorsanız, lütfen parolanızı değiştirin.'
    ],
    'failed_login' => [
        'subject' => '2FAuth uygulamasına başarısız giriş',
        'resume' => '2FAuth hesabınıza başarısız bir giriş denemesi yapıldı.',
        'connection_details' => 'Bu giriş denemesinin detayları şöyle',
        'recommandations' => 'Eğer bu sizseniz, bu uyarıyı göz ardı edebilirsiniz. Eğer daha fazla başarısız giriş denemesi görürseniz, güvenlik ayarlarını gözden geçirmek ve bu saldırgana karşı işlem yapmak için 2FAuth yöneticisiyle iletişime geçmelisiniz.'
    ],
];