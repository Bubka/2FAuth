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

    'service' => 'Servis',
    'account' => 'Hesap',
    'icon' => 'Simge',
    'icon_to_illustrate_the_account' => 'Hesabı gösteren simge',
    'remove_icon' => 'Simgeyi kaldır',
    'no_account_here' => 'Burada 2FA yok!',
    'add_first_account' => 'Bir metod seç ve ilk hesabını ekle',
    'use_full_form' => 'Veya detaylı formu kullan',
    'add_one' => 'Ekle',
    'show_qrcode' => 'QR kodu göster',
    'no_service' => '- servis yok -',
    'account_created' => 'Hesap başarıyla oluşturuldu',
    'account_updated' => 'Hesap başarı ile güncellendi',
    'accounts_deleted' => 'Hesap(lar) başarıyla silindi',
    'accounts_moved' => 'Hesap(lar) başarıyla taşındı',
    'export_selected_accounts' => 'Seçili hesapları dışa aktar',
    'twofauth_export_format' => '2FAuth formatı',
    'twofauth_export_format_sub' => 'Verileri, 2FAuth json formatında dışa aktar',
    'twofauth_export_format_desc' => 'Geri yüklenebilecek bir yedekleme oluşturmanız gerekiyorsa bu seçeneği tercih etmelisiniz. Bu seçenek simgeleri de halleder.',
    'twofauth_export_format_url' => 'Bu format açıklaması şöyle açıklanmıştır:',
    'twofauth_export_schema' => '2FAuth dışa aktarma formatı',
    'otpauth_export_format' => 'otpauth URI\'leri',
    'otpauth_export_format_sub' => 'Veriyi, otpauth URI listesi olarak dışa aktar',
    'otpauth_export_format_desc' => 'otpauth URI, 2FA verilerini değiştirmek için kullanılan en yaygın formattır; örneğin bir web sitesinde 2FA\'yı etkinleştirdiğinizde QR kodu biçimindedir. 2FAuth\'tan geçiş yapmak istiyorsanız bunu seçin.',
    'reveal' => 'göster',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'John DOE',
        ],
        'new_account' => 'Yeni hesap',
        'edit_account' => 'Hesabı düzenle',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'QR kod tara',
        'upload_qrcode' => 'QR kod yükle',
        'use_advanced_form' => 'Detaylı formu kullan',
        'prefill_using_qrcode' => 'QR kod kullanarak doldur',
        'use_qrcode' => [
            'val' => 'QR kod kullan',
            'title' => 'Formu sihirli bir şekilde doldurmak için QR kod kullan',
        ],
        'unlock' => [
            'val' => 'Kilidi aç',
            'title' => 'Kilidi aç (risk size ait)',
        ],
        'lock' => [
            'val' => 'Kilitle',
            'title' => 'Kilitle',
        ],
        'choose_image' => 'Yükle',
        'i_m_lucky' => 'Şansımı dene',
        'i_m_lucky_legend' => 'The "Try my luck" button tries to get a standard icon from the selected icon collection. The simpler the Service field value, the more likely you are to get the expected icon: Do not append any extension (like ".com"), use the exact name of the service, avoid special chars.',
        'test' => 'Deneme',
        'group' => [
            'label' => 'Grup',
            'help' => 'Hesabın ilişkilendirileceği grup'
        ],
        'secret' => [
            'label' => 'Anahtar',
            'help' => 'Güvenlik kodlarınızın oluşturulması için gereken anahtar'
        ],
        'plain_text' => 'Düz metin',
        'otp_type' => [
            'label' => 'Choose the type of OTP to create',
            'help' => 'Zamana dayalı OTP veya HMAC dayalı OTP veya Steam OTP'
        ],
        'digits' => [
            'label' => 'Hane sayısı',
            'help' => 'Oluşturulacak güvenlik kodunun kaç haneden oluşacağı'
        ],
        'algorithm' => [
            'label' => 'Algoritma',
            'help' => 'Güvenlik kodlarının oluşturulması için kullanılacak algoritma'
        ],
        'period' => [
            'label' => 'Süre',
            'placeholder' => 'Varsayılan 30',
            'help' => 'Oluşturulan güvenlik kodlarının, saniye cinsinden, geçerli olduğu süre'
        ],
        'counter' => [
            'label' => 'Sayaç',
            'placeholder' => 'Varsayılan değer 0',
            'help' => 'Başlangıç sayaç değeri',
            'help_lock' => 'Sayaç değerini düzenlemek risklidir çünkü hesabı hizmetin doğrulama sunucusuyla senkronize etmeyi bozabilirsiniz. Değişiklik yapmak için kilit simgesini kullanın, ancak ne yaptığınızı biliyorsanız sadece değiştirin'
        ],
        'image' => [
            'label' => 'Görüntü',
            'placeholder' => 'http://...',
            'help' => 'Dış bir resmin URL\'i, hesap simgesi olarak kullanılacak'
        ],
        'is_shared' => [
            'label' => 'Bu hesabı tüm kullanıcılarla paylaş',
            'help' => 'Etkinleştirildiğinde, bu hesap sistemdeki tüm kullanıcılar tarafından görülebilir olacaktır'
        ],
        'options_help' => 'Neleri seçmeniz gerektiğini bilmiyorsanız aşağıdaki seçenekleri boş bırakabilirsiniz. En yaygın kullanılan değerler uygulanacaktır.',
        'alternative_methods' => 'Alternatif yöntem',
        'spaces_are_ignored' => 'İstenmeyen boşluklar otomatik olarak kaldırılacaktır'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Canlı tarama başlatılamıyor :(',
        'need_grant_permission' => [
            'reason' => '2FAuth\'un kameranıza ulaşmak için izni yok',
            'solution' => 'Cihaz kamerasını kullanma izni vermeniz gerekiyor. Eğer zaten reddettiyseniz ve tarayıcınız tekrar sormuyorsa, izni nasıl vereceğiniz hakkında tarayıcı belgelerine başvurun.',
            'click_camera_icon' => 'Genellikle tarayıcının adres çubuğunun yanında veya içinde çizili bir kamera simgesine tıklayarak yapılır',
        ],
        'not_readable' => [
            'reason' => 'Tarayıcı yüklenemedi',
            'solution' => 'Kamera zaten kullanımda mı? Başka bir uygulamanın kameranızı kullanmadığından emin olun ve tekrar deneyin'
        ],
        'no_cam_on_device' => [
            'reason' => 'Bu cihazda kamera yok',
            'solution' => 'Belki web kamerasını takmayı unuttunuz'
        ],
        'secured_context_required' => [
            'reason' => 'Güvenli bağlam gereklidir',
            'solution' => 'Canlı tarama için HTTPS gereklidir. Eğer 2FAuth\'u bilgisayarınızdan çalıştırıyorsanız, localhost dışında sanal bir ana bilgisayar kullanmayın'
        ],
        'https_required' => 'Kamera yayını için HTTPS gereklidir',
        'camera_not_suitable' => [
            'reason' => 'Yüklenen kameralar uygun değil',
            'solution' => 'Lütfen başka bir cihaz/kamera kullanın'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Bu tarayıcıda Akış API\'si desteklenmiyor',
            'solution' => 'Modern bir tarayıcı kullanmalısınız'
        ],
    ],
    'confirm' => [
        'delete' => 'Bu hesabı silmek istediğinizden emin misiniz?',
        'cancel' => 'Değişiklikler kaybolacak. Emin misiniz?',
        'discard' => 'Bu hesabı silmek istediğinizden emin misiniz?',
        'discard_all' => 'Tüm hesapları silmek istediğinizden emin misiniz?',
        'discard_duplicates' => 'Tüm kopyaları silmek istediğinizden emin misiniz?',
    ],
    'import' => [
        'import' => 'İçe Aktar',
        'to_import' => 'İçe Aktar',
        'import_legend' => '2FAuth, çeşitli 2FA uygulamalarından veri alabilir.',
        'import_legend_afterpart' => 'Kullandığınız uygulamanın Dışa Aktarma özelliğini kullanarak bir QR kodu veya JSON dosyası oluşturun ve buraya yükleyin.',
        'upload' => 'Yükle',
        'scan' => 'Tara',
        'supported_formats_for_qrcode_upload' => 'Kabul Edilen: jpg, jpeg, png, bmp, gif, svg veya webp',
        'supported_formats_for_file_upload' => 'Kabul Edilen: Düz metin, json, 2fas',
        'expected_format_for_direct_input' => 'Beklenen: Her biri bir satırda bir otpauth URI listesi',
        'supported_migration_formats' => 'Desteklenen taşıma biçimleri',
        'qr_code' => 'QR Kodu',
        'text_file' => 'Metin dosyası',
        'direct_input' => 'Doğrudan giriş',
        'plain_text' => 'Düz metin',
        'parsing_data' => 'Veri analiz ediliyor...',
        'issuer' => 'Yayınlayan',
        'imported' => 'İçe aktarıldı',
        'failure' => 'Başarısız',
        'x_valid_accounts_found' => 'Toplam geçerli hesap sayısı: :count',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'Taşıma kaynağında aşağıdaki 2FA hesapları bulundu. Şimdiye kadar hiçbiri 2FAuth\'a eklenmedi.',
        'use_buttons_to_save_or_discard' => 'Kullanılabilir düğmeleri kullanarak bunları 2FA koleksiyonunuza kalıcı olarak kaydedin veya bunları silin.',
        'import_all' => 'Tümünü içe aktar',
        'import_this_account' => 'Hesabı içe aktar',
        'discard_all' => 'Hepsini iptal et',
        'discard_duplicates' => 'Aynı olanları yoksay',
        'discard_this_account' => 'Bu hesabı yoksay',
        'generate_a_test_password' => 'Bir deneme parolası oluştur',
        'possible_duplicate' => 'Birebir aynı verilere sahip başkan bir hesap mevcut',
        'invalid_account' => '- geçersiz hesap -',
        'invalid_service' => '- geçersiz servis -',
        'do_not_set_password_or_encryption' => 'Başka bir 2FA uygulamasından verileri dışarı aktarırken şifreleme veya şifre ile koruma seçeneklerini kullanmayın, aksi takdirde 2FAuth bu verileri açamaz.',
    ],

];