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
    'failed' => 'Bu kimlik bilgileri bizdeki kayıtlarla eşleşmiyor.',
    'password' => 'Parola yanlış.',
    'throttle' => 'Çok fazla hatalı giriş denemesi. Lütfen :seconds saniye sonra yeniden deneyin.',

    // 2FAuth
    'sign_out' => 'Çıkış',
    'sign_in' => 'Oturum aç',
    'sign_in_using' => 'Oturum açma yöntemi',
    'if_administrator' => 'Yönetici?',
    'sign_in_here' => 'SSO olmadan giriş yapabilirsiniz',
    'or_continue_with' => 'Ayrıca şununla da devam edebilirsiniz:',
    'password_login_and_webauthn_are_disabled' => 'Şifre ve WebAuthn ile giriş devre dışı bırakıldı.',
    'sign_in_using_sso' => 'Giriş yapmak için bir SSO sağlayıcısı seçin:',
    'no_provider' => 'sağlayıcı yok',
    'no_sso_provider_or_provider_is_missing' => 'Sağlayıcı yok mu?',
    'see_how_to_enable_sso' => 'Nasıl bir sağlayıcı etkinleştirebileceğinizi görün',
    'sign_in_using_security_device' => 'Güvenlik cihazı kullanarak oturum aç',
    'login_and_password' => 'kullanıcı & şifre',
    'register' => 'Kayıt',
    'welcome_to_2fauth' => '2Fauth\'a hoşgeldiniz',
    'autolock_triggered' => 'Otomatik kilit devreye girdi',
    'autolock_triggered_punchline' => 'Otomatik kilit devreye girdi, sistemden çıkışınız yapıldı',
    'already_authenticated' => 'Zaten giriş yapılmış, lütfen öncelikle çıkış yapın',
    'authentication' => 'Kimlik doğrulama',
    'maybe_later' => 'Belki daha sonra',
    'user_account_controlled_by_proxy' => 'Kimlik doğrulama proxy\'si tarafından kullanıma sunulan kullanıcı hesabı.<br />Hesabı proxy düzeyinde yönetin.',
    'auth_handled_by_proxy' => 'Kimlik doğrulama ters proxy tarafından gerçekleştirilmiş, aşağıdaki ayarlar devre dışı.<br />Kimlik doğrulamayı proxy düzeyinde yönetin.',
    'sso_only_x_settings_are_disabled' => 'Kimlik doğrulama yalnızca SSO ile mümkün, :auth_method devre dışı',
    'confirm' => [
        'logout' => 'Çıkış yapmak istediğinizden emin misiniz?',
        'revoke_device' => 'Bu cihazın yetkilerini iptal etmek istediğinizden emin misiniz?',
        'delete_account' => 'Hesabınızı silmek istediğinizden emin misiniz?',
    ],
    'webauthn' => [
        'security_device' => 'bir güvenlik cihazı',
        'security_devices' => 'Güvenlik cihazları',
        'security_devices_legend' => '2FAuth\'a giriş yapabilmek için kullanabileceğiniz; güvenlik anahtarı (ör. Yubikey) veya biyometrik özellikli bir akıllı telefon (ör. Apple FaceId/TouchId) gibi cihazlar',
        'enhance_security_using_webauthn' => '2FAuth hesabınızın güvenliğini, WebAuthn kimlik doğrulaması kullanarak arttırabilirsiniz.<br /><br />
            WebAuthn, güvenilir cihazlar (Yubikey veya biyometrik özellikli bir akıllı telefon gibi) kullanarak daha hızlı ve daha güvenli giriş yapmanızı sağlar.',
        'use_security_device_to_sign_in' => 'Güvenlik cihazlarınız ile giriş yapmaya hazırlanın. Ör. anahtar cihazınızı takın, yüz maskenizi veya eldiveninizi çıkarın.',
        'lost_your_device' => 'Cihazınız mı kayboldu?',
        'recover_your_account' => 'Hesabınızı kurtarın',
        'account_recovery' => 'Hesap kurtarma',
        'recovery_punchline' => '2FAuth, bu ePosta adresine bir kurtarma bağlantısı gönderecek. ePostadaki bağlantıya tıklayın ve talimatları takip edin.<br /><br />ePostayı tamamen size ait olan bir cihazda açtığınızda emin olun.',
        'send_recovery_link' => 'Kurtarma bağlantısı gönder',
        'account_recovery_email_sent' => 'Hesap kurtarma ePostası gönderildi!',
        'disable_all_security_devices' => 'Tüm güvenlik cihazlarını devre dışı bırak',
        'disable_all_security_devices_help' => 'Tüm güvenlik cihazlarını iptal edilecektir. Bu seçeneği yalnızca cihazınız kayboldu veya cihazınızda güvenlik zaafiyeti oluştuysa kullanın.',
        'register_a_new_device' => 'Yeni cihaz ekle',
        'register_a_device' => 'Cihaz ekle',
        'device_successfully_registered' => 'Cihaz başarıyla kaydedildi',
        'device_revoked' => 'Cihaz başarıyla iptal edildi',
        'revoking_a_device_is_permanent' => 'Cihaz iptali kalıcıdır',
        'recover_account_instructions' => 'Hasabınızı kurtarmak için, 2FAuth bazı Webauthn özelliklerini sıfırlar, böylece ePosta ve parolanızla giriş yapabilirsiniz.',
        'invalid_recovery_token' => 'Geçersiz kurtarma token\'i',
        'webauthn_login_disabled' => 'Webauthn girişi devre dışı bırakıldı',
        'invalid_reset_token' => 'Bu sıfırlama token\'i geçerli değil.',
        'rename_device' => 'Cihazı adı değişikliği',
        'my_device' => 'Cihazım',
        'unknown_device' => 'Bilinmeyen cihaz',
        'use_webauthn_only' => [
            'label' => 'Yalnızca WebAuthn kullan',
            'help' => 'WebAuthn girişini, 2FAuth hesabınıza tek yetkili giriş yöntemi yapın. Bu yöntem ile WebAuthn\'in güvenlik avantajlarından yararlanabilirsiniz.<br /><br />
                Cihazınızın kaybolması durumunda, bu seçeneği iptal edip kullanıcı adı ve parolanız ile giriş yaparak hesabınızı kurtarabilirsiniz.<br /><br />
                Uyarı! Bu seçenek işaretlenmiş olsa bile ePosta ve Parola formu aktif olarak görünecek ancak her seferinde \'Başarısız giriş\' hatası verecektir.'
        ],
        'need_a_security_device_to_enable_options' => 'Aşağıdaki seçenekleri aktifleştirmek için en az bir cihaz ekleyin',
        'options' => 'Seçenekler',
    ],
    'forms' => [
        'name' => 'İsim',
        'login' => 'Giriş',
        'webauthn_login' => 'WebAuthn girişi',
        'sso_login' => 'SSO girişi',
        'email' => 'ePosta',
        'password' => 'Parola',
        'reveal_password' => 'Parolayı göster',
        'hide_password' => 'Parolayı gizle',
        'confirm_password' => 'Parolayı onayla',
        'new_password' => 'Yeni parola',
        'confirm_new_password' => 'Yeni parolayı onayla',
        'dont_have_account_yet' => 'Henüz hesabınız yok mu?',
        'already_register' => 'Zaten kayıtlı mısınız?',
        'authentication_failed' => 'Kimlik doğrulama başarısız',
        'forgot_your_password' => 'Parolanızı mı unuttunuz?',
        'request_password_reset' => 'Sıfırla',
        'reset_your_password' => 'Parolanızı sıfırlayın',
        'reset_password' => 'Parola sıfırlama',
        'disabled_in_demo' => 'Bu özellik Demo modunda geçerli değil',
        'sso_only_form_restricted_to_admin' => 'Kullanıcıların SSO ile giriş yapması gerekiyor. Diğer yöntemleri yalnızca yöneticiler kullanabilir.',
        'new_password' => 'Yeni parola',
        'current_password' => [
            'label' => 'Mevcut parola',
            'help' => 'Kimliğinizi doğrulamak için mevcut parolanızı girin'
        ],
        'change_password' => 'Parola değiştir',
        'send_password_reset_link' => 'Parola sıfırlama bağlantısı yolla',
        'password_successfully_reset' => 'Parola başarıyla sıfırlandı',
        'edit_account' => 'Hesabı düzenle',
        'profile_saved' => 'Profil başarıyla güncellendi!',
        'welcome_to_demo_app_use_those_credentials' => '2FAuth demo moduna hoşgeldiniz.<br><br>ePosta adresi olarak <strong>demo@2fauth.app</strong> ve parola olarak <strong>demo</strong> kullanarak bağlanabilirsiniz',
        'welcome_to_testing_app_use_those_credentials' => '2FAuth deneme moduna hoşgeldiniz.<br><br> ePosta adresi olarak <strong>testing@2fauth.app</strong> ve şifre olarak <strong>password</strong> kullanabilirsiniz',
        'register_punchline' => '<b>2FAuth</b> uygulamasına hoşgeldiniz.<br/>Devam edebilmek için bir hesaba ihtiyacınız var, lütfen kayıt olun.',
        'reset_punchline' => '2FAuth bu adrese bir parola sıfırlama bağlantısı yollanacak. Yeni bir parola belirlemek için ePostadaki bağlantıya tıklayın.',
        'name_this_device' => 'Bu cihaza isim verin',
        'delete_account' => 'Hesabı sil',
        'delete_your_account' => 'Hesabınızı silin',
        'delete_your_account_and_reset_all_data' => 'Kullanıcı hesabınız ve tüm 2FA verileriniz silinecektir. Bunun geri dönüşü yok.',
        'reset_your_password_to_delete_your_account' => 'Eğer şimdiye kadar giriş için hep SSO kullandıysanız, parola alabillmek için çıkış yapın ve sonrasında parola sıfırlama özelliğini kullanın.',
        'deleting_2fauth_account_does_not_impact_provider' => '2FAuth hesabını silmek, harici SSO hesabınızı etkilemez.',
        'user_account_successfully_deleted' => 'Kullanıcı hesabı başarıyla silindi',
        'has_lower_case' => 'Küçük harf barındırmalı',
        'has_upper_case' => 'Büyük harf barındırmalı',
        'has_special_char' => 'Özel karakter barındırmalı',
        'has_number' => 'Rakam barındırmalı',
        'is_long_enough' => 'En az 8 karakter',
        'mandatory_rules' => 'Zorunlu',
        'optional_rules_you_should_follow' => 'Önerilen (şiddetle)',
        'caps_lock_is_on' => 'Caps lock Açık',
    ],
    'sso_providers' => [
        'unknown' => 'bilinmiyor',
        'github' => 'Github',
        'openid' => 'OpenID'
    ]
];
