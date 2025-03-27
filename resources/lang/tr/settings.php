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

    'settings' => 'Ayarlar',
    'preferences' => 'Seçenekler',
    'account' => 'Hesap',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokenler',
    'options' => 'Seçenekler',
    'user_preferences' => 'Kullanıcı tercihleri',
    'admin_settings' => 'Yönetici ayarları',
    'confirm' => [

    ],
    'you_are_administrator' => 'Yöneticisiniz',
    'account_linked_to_sso_x_provider' => ':provider hesabınızı kullanarak SSO girişi yaptınız. Bilgilerinizi buradan değil :provider üzerinden değiştirebilirsiniz.',
    'general' => 'Genel',
    'security' => 'Güvenlik',
    'notifications' => 'Bildirimler',
    'profile' => 'Profil',
    'change_password' => 'Parola değiştir',
    'personal_access_tokens' => 'Kişisel erişim tokenleri',
    'token_legend' => 'Kişisel Erişim Tokenleri, herhangi bir uygulamanın 2FAuth API\'sinde kimlik doğrulaması yapmasına olanak tanır. Erişim tokenlerini, kullanıcı uygulama isteklerinin yetkilendirmesinde Taşıyıcı token olarak belirtmelisiniz.',
    'generate_new_token' => 'Yeni bir token oluştur',
    'revoke' => 'İptal et',
    'token_revoked' => 'Token başarıyla iptal edildi',
    'revoking_a_token_is_permanent' => 'Token iptali kalıcıdır',
    'confirm' => [
        'revoke' => 'Bu tokeni iptal etmek istediğinize emin misiniz?',
    ],
    'make_sure_copy_token' => 'Kişisel tokeninizi şu anda kopyaladığınızdan emin olun. Bir daha görebilme şansınız olmayacak!',
    'data_input' => 'Veri girişi',
    'settings_managed_by_administrator' => 'Some settings are being managed by your administrator',
    'forms' => [
        'edit_settings' => 'Ayarları düzenle',
        'setting_saved' => 'Ayarlar kaydedildi',
        'new_token' => 'Yeni token',
        'some_translation_are_missing' => 'Tarayıcıda belirtilen dildeki çeviriler eksik mi?',
        'help_translate_2fauth' => '2FAuth çevirisine yardımcı olun',
        'language' => [
            'label' => 'Diller',
            'help' => '2FAuth\'un gösterileceği dili değiştirir. Buradaki diller tamamlanmış olup tarayıcınız tarafından tercih edilen dili değiştirmek için seçim yapabilirsiniz.'
        ],
        'timezone' => [
            'label' => 'Saat dilimi',
            'help' => 'Saat dilimi, uygulamada gösterilen tüm tarih ve saatler için uygulandı'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated OTP as dot',
            'help' => 'Replace generated password characters with *** to ensure confidentiality. Does not affect the copy/paste feature'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured OTP',
            'help' => 'Noktalar şeklinde gösterilen parolanın geçici olarak gösterilmesini sağlar'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close OTP after copy',
            'help' => 'Oluşturulan kodu kopyalamak için tıkladıktan sonra parolanın ekrandan kaldırılmasını sağlar'
        ],
        'show_next_otp' => [
            'label' => 'Show next OTP',
            'help' => 'Preview the next password, i.e. the password that will replace the current password when it expires. Preferences set for the current OTP also apply to the next one (formatting, show as dot)'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close OTP',
            'help' => 'Belirli bir süre sonra ekranda gösterilen kodu otomatik olarak sakla. Bu, kod görünümünü kapatmayı unutursanız gereksiz yere yeni kod isteklerinin önüne geçer.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Kopyaladıktan sonra Aramayı Temizle',
            'help' => 'Bir kod panoya kopyalandıktan hemen sonra Arama kutusunu temizler'
        ],
        'sort_case_sensitive' => [
            'label' => 'Büyük/küçük harfe duyarlı sıralama',
            'help' => 'Etkinleştirildiğinde, Sıralama işlevini hesapları büyük/küçük harfe duyarlı bir şekilde sıralayacak şekilde zorlar'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copy OTP on display',
            'help' => 'Oluşturulan parolayı, ekranda görünür görünmez, otomatik olarak kopyalar. Tarayıcı sınırlandırmaları nedeniyle, yenilenenler değil yalnızca ilk <abbr title="Time-based One-Time Password">TOTP</abbr> kopyalanır'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Basit QR kod okuyucuyu kullan',
            'help' => 'QR kod okunması sırasında sorunlar yaşıyorsanız, bu seçenek daha basit ancak daha güvenilir bir QR kod okuyucu kullanılmasını sağlar'
        ],
        'display_mode' => [
            'label' => 'Görüntüleme modu',
            'help' => 'Hesapların nasıl görünmesini istediğinizi seçin'
        ],
        'password_format' => [
            'label' => 'Parola gösterimi',
            'help' => 'Daha rahat okuma ve akılda tutma için parolalarınızdaki rakamların nasıl gruplanacağını seçin'
        ],
        'pair' => 'İkili ayır',
        'pair_legend' => 'Rakamları ikişerli ayır',
        'trio_legend' => 'Rakamları üçerli ayır',
        'half_legend' => 'Rakamları eşit sayıda iki gruba ayır',
        'trio' => 'Üçlü ayır',
        'half' => 'Ortadan böl',
        'grid' => 'Izgara',
        'list' => 'Liste',
        'theme' => [
            'label' => 'Tema',
            'help' => 'Belirli bir tema kullan veya sistem/tarayıcı tercihini uygula'
        ],
        'light' => 'Aydınlık',
        'dark' => 'Karanlık',
        'automatic' => 'Otomatik',
        'show_accounts_icons' => [
            'label' => 'Simgeleri göster',
            'help' => 'Ana görünümde hesap simgelerini göster'
        ],
        'get_official_icons' => [
            'label' => 'Özgün simgeleri al',
            'help' => 'Hesabı eklerken 2FA sağlayıcısının özgün simgelerini al (dene)'
        ],
        'auto_lock' => [
            'label' => 'Otomatik kilitleme',
            'help' => 'İşlem olmaması durumunda kullanıcının çıkışını yapar. Eğer kimlik doğrulama bir proxy aracılığı ile yapılmış ve belirlenmiş bir çıkış url\'si yok ise bu seçenek işe yaramaz.'
        ],
        'default_group' => [
            'label' => 'Varsayılan grup',
            'help' => 'Yeni oluşturulan hesapların ilişkilendirileceği grup',
        ],
        'view_default_group_on_copy' => [
            'label' => 'Kopyalarken varsayılan grubu göster',
            'help' => 'OTP kopyalandığında her zaman varsayılan gruba döner',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Hesapları otomatik kaydet',
            'help' => 'Yeni hesaplar, tarandıktan veya QR kodu yüklendikten sonra otomatik olarak kaydedilir. "Kaydet" butonuna tıklamanıza gerek kalmaz',
        ],
        'useDirectCapture' => [
            'label' => 'Doğrudan giriş',
            'help' => 'Mevcut olanlar arasından bir giriş modu seçmeniz istenip istenmeyeceğini veya doğrudan varsayılan giriş modunu kullanmak isteyip istemediğinizi seçin',
        ],
        'defaultCaptureMode' => [
            'label' => 'Varsayılan giriş modu',
            'help' => 'Doğrudan giriş seçeneği seçildiğinde varsayılan giriş modu kullanılır',
        ],
        'remember_active_group' => [
            'label' => 'Grup filtrelerini hatırla',
            'help' => 'Son uygulanan grup filtresini saklar ve bir sonraki ziyaretinizde aynısını gösterir',
        ],
        'otp_generation' => [
            'label' => 'Parolayı göster',
            'help' => '<abbr title="One-Time Passwords">OTP\'lerin</abbr> nasıl ve ne zaman gösterileceğini ayarlar.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'Yeni cihazda',
            'help' => 'Yeni bir cihaz 2FAuth hesabınıza ilk kez eriştiğinde bir eposta alın'
        ],
        'notify_on_failed_login' => [
            'label' => 'Başarısız girişte',
            'help' => '2FAuth hesabınıza başarısız bir bağlanma denemesi olduğunda eposta alın'
        ],
        'show_email_in_footer' => [
            'label' => 'Epostayı alt bilgide göster',
            'help' => 'Oturum açmış kullanıcının epostasını doğrudan gezinme bağlantıları yerine altbilgide görüntüleyin. Bağlantılara, epostaya tıkladıktan sonra açılan menüden erişilebilir.'
        ],
        'otp_generation_on_request' => 'Tıkladıktan/dokunduktan sonra',
        'otp_generation_on_request_legend' => 'Tek, kendi görünüşünde',
        'otp_generation_on_request_title' => 'Parolaları ayrı bir ekranda görmek için tıklayın',
        'otp_generation_on_home' => 'Sürekli',
        'otp_generation_on_home_legend' => 'Tümü birden, ana ekranda',
        'otp_generation_on_home_title' => 'Tüm parolaları ana sayfada gösterir',
        'never' => 'Asla',
        'on_otp_copy' => 'Güvenlik kodu kopyalandığında',
        '1_minutes' => '1 dakika sonra',
        '2_minutes' => '2 dakika sonra',
        '5_minutes' => '5 dakika sonra',
        '10_minutes' => '10 dakika sonra',
        '15_minutes' => '15 dakika sonra',
        '30_minutes' => '30 dakika sonra',
        '1_hour' => '1 saat sonra',
        '1_day' => '1 gün sonra',
        'livescan' => 'Canlı QR kod tarama',
        'upload' => 'QR kod yükleme',
        'advanced_form' => 'Gelişmiş form',
    ],

];