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
    'notifications' => 'Notifications',
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
            'label' => 'Time zone',
            'help' => 'The time zone applied to all dates and times displayed in the application'
        ],
        'show_otp_as_dot' => [
            'label' => 'Oluşturulan <abbr title="One-Time Password">OTP\'yi</abbr> noktalar olarak göster',
            'help' => 'Oluşturulan paroladaki karakterli, güvenliği arttırmak için, *** olarak gösterir. Kopyala/yapıştır özelliğini etkilemez'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Gizlenmiş <abbr title="One-Time Password">OTP\'yi</abbr> göster',
            'help' => 'Noktalar şeklinde gösterilen parolanın geçici olarak gösterilmesini sağlar'
        ],
        'close_otp_on_copy' => [
            'label' => 'Kopyaladıktan sonra <abbr title="One-Time Password">OTP\'yi</abbr> kapat',
            'help' => 'Click on a generated password to copy it automatically hides it from the screen'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close <abbr title="One-Time Password">OTP</abbr>',
            'help' => 'Automatically hide on-screen password after a timeout. This avoids unnecessary requests for fresh passwords if you forget to close the password view.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Kopyaladıktan sonra Aramayı Temizle',
            'help' => 'Bir kod panoya kopyalandıktan hemen sonra Arama kutusunu temizler'
        ],
        'sort_case_sensitive' => [
            'label' => 'Sort case sensitive',
            'help' => 'When invoked, force the Sort function to sort accounts on a case-sensitive basis'
        ],
        'copy_otp_on_display' => [
            'label' => '<abbr title="One-Time Password">OTP\'yi</abbr> görününce kopyala',
            'help' => 'Oluşturulan parolayı, ekranda görünür görünmez, otomatik olarak kopyalar. Tarayıcı sınırlandırmaları nedeniyle, yenilenenler değil yalnızca ilk <abbr title="Time-based One-Time Password">TOTP</abbr> kopyalanır'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Basit QR kod okuyucuyu kullan',
            'help' => 'QR kod okunması sırasında sorunlar yaşıyorsanız, bu seçenek daha basit ancak daha güvenilir bir QR kod okuyucu kullanılmasını sağlar'
        ],
        'display_mode' => [
            'label' => 'Görüntüleme modu',
            'help' => 'Hesapların liste olarak mı yoksa ızgara olarak mı görüntülenmesini istediğinizi seçin'
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
            'help' => 'Ana ekranda hesapların simgelerini gösterir'
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
            'label' => 'Auto-save accounts',
            'help' => 'New accounts are automatically registered after scanning or uploading a QR code, no need to click a Save button',
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
            'label' => 'On new device',
            'help' => 'Get an email when a new device connects to your 2FAuth account for the first time'
        ],
        'notify_on_failed_login' => [
            'label' => 'On failed login',
            'help' => 'Get an email each time an attempt to connect to your 2FAuth account fails'
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
        '2_minutes' => 'After 2 minutes',
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