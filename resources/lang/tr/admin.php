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

    'admin' => 'Yönetici',
    'app_setup' => 'Uygulama Kurulumu',
    'registrations' => 'Kayıt',
    'users' => 'Kullanıcılar',
    'users_legend' => 'Uygulamanızda kayıtlı kullanıcılar yönetin veya yeni kullanıcı oluşturun.',
    'admin_settings' => 'Yönetici ayarları',
    'create_new_user' => 'Kullanıcı oluştur',
    'new_user' => 'Yeni kullanıcı',
    'search_user_placeholder' => 'Kullanıcı adı, ePosta...',
    'quick_filters_colons' => 'Hızlı filtreler:',
    'user_created' => 'kullanıcı başarıyla oluşturuldu',
    'confirm' => [
        'delete_user' => 'Bu kullanıcıyı silmek istediğinizden emin misiniz? Geri dönüşü olmayacak.',
        'request_password_reset' => 'Bu kullanıcının şifresini sıfırlamak istediğinizden emin misiniz?',
        'purge_password_reset_request' => 'Bir önceki talebi iptal etmek istediğinize emin misiniz?',
        'delete_account' => 'Bu kullanıcıyı silmek istediğinize emin misiniz?',
        'edit_own_account' => 'Bu sizin kendi hesabınız. Emin misiniz?',
        'change_admin_role' => 'Bu, kullanıcının izinlerinde ciddi etkiler yaratacaktır. Emin misiniz?',
        'demote_own_account' => 'Bundan sonra yönetici olmayacaksınız. Gerçekten emin misiniz?'
    ],
    'logs' => 'Günlükler',
    'administration_legend' => 'Aşağıdaki ayarlar genel ayarlardır ve her kullanıcı için geçerli olur.',
    'user_management' => 'Kullanıcı yönetimi',
    'oauth_provider' => 'OAuth sağlayıcı',
    'account_bound_to_x_via_oauth' => 'Bu hesap OAuth ile bir :provider hesabına bağlı',
    'last_seen_on_date' => 'Son görülme :date',
    'registered_on_date' => 'Kayıt tarihi :date',
    'updated_on_date' => 'Güncelleme tarihi :date',
    'access' => 'Erişim',
    'password_requested_on_t' => 'Bu kullanıcı için bir parola sıfırlama talebi bulunmakta (talep tarihi :datetimi), yani kullanıcı henüz parolasını değiştirmemiş ancak gönderilen parola değiştirme bağlantısı hala geçerli. Bu talep, kullanıcı tarafından veya bir yönetici tarafından yapılmış olabilir.',
    'password_request_expired' => 'Bu kullanıcı için bir parola sıfırlama talebi bulunmakta ama talebin süresi geçmiş yani kullanıcı, süresi içerisinde parolasını değiştirmemiş. Bu talep, kullanıcı tarafından veya bir yönetici tarafından yapılmış olabilir.',
    'resend_email' => 'ePosta\'yı yeniden gönder',
    'resend_email_title' => 'Parola sıfırlama ePostasını kullanıcıya yeniden gönder',
    'resend_email_help' => 'Kullanıcının yeni bir parola belirleyebilmesi için yeni bir parola sıfırlama e-postası göndermek üzere <b>E-postayı yeniden gönder</b>\'i kullanın. Bu, mevcut şifreyi olduğu gibi bırakacak ve önceki tüm talepler iptal edilecektir.',
    'reset_password' => 'Parolayı sıfırla',
    'reset_password_help' => 'Kullanıcıya yeni bir parola belirleyebilmesi için bir parola sıfırlama e-postası göndermeden önce, parola sıfırlama işlemini zorunlu kılmak için <b>Parolayı sıfırla</b> seçeneğini kullanın (bu, geçici bir parola oluşturacaktır). Daha önce yapılan tüm talepler iptal edilecektir.',
    'reset_password_title' => 'Kullanıcının parolasını sıfırla',
    'password_successfully_reset' => 'Parola başarıyla sıfırlandı',
    'user_has_x_active_pat' => ':count aktif token',
    'user_has_x_security_devices' => ':count güvenlik cihazı (parola anahtarı)',
    'revoke_all_pat_for_user' => 'Tüm kullanıcı token\'larını iptal et',
    'revoke_all_devices_for_user' => 'Tüm kullanıcı güvenlik cihazlarını iptal et',
    'danger_zone' => 'Tehlikeli Bölge',
    'delete_this_user_legend' => 'Kullanıcı hesabı ve tüm 2FA verileri silinecektir.',
    'this_is_not_soft_delete' => 'Bu kalıcı bir silme işlemidir, geri dönüşü olmayacak.',
    'delete_this_user' => 'Bu kullanıcıyı sil',
    'user_role_updated' => 'Kullanıcı rolü güncellendi',
    'pats_succesfully_revoked' => 'Kullanıcının KET\'leri başarıyla iptal edildi',
    'security_devices_succesfully_revoked' => 'Kullanıcının güvenlik cihazları başarıyla iptal edildi',
    'variables' => 'Değişkenler',
    'cache_cleared' => 'Önbellek temizlendi',
    'cache_optimized' => 'Önbellek iyileştirildi',
    'check_now' => 'Kontrol et',
    'view_on_github' => 'Github\'da görüntüle',
    'x_is_available' => ':version bulunuyor',
    'successful_login_on' => 'Successful login on <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => 'Successful logout on <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => 'Failed login on <span class="light-or-darker">:login_at</span>',
    'viewed_on' => 'Viewed on <span class="light-or-darker">:login_at</span>',
    'last_accesses' => 'Son erişimler',
    'see_full_log' => 'Tüm günlüğü gör',
    'browser_on_platform' => ':platform üzerinde :browser',
    'access_log_has_more_entries' => 'The access log contains more entries.',
    'access_log_legend_for_user' => ':username kullanıcısının tüm erişim günlüğü',
    'show_last_month_log' => 'Geçen aydan girdiler göster',
    'show_three_months_log' => 'Geçen 3 aydan girdiler göster',
    'show_six_months_log' => 'Geçen 6 aydan girdiler göster',
    'show_one_year_log' => 'Geçen yıldan girdiler göster',
    'sort_by_date_asc' => 'Eskileri önce göster',
    'sort_by_date_desc' => 'Yenileri önce göster',
    'forms' => [
        'use_encryption' => [
            'label' => 'Hassas verileri koru',
            'help' => '2FA sırları ve ePosta gibi hassas veriler, şifrelenmiş veritabanında tutulur. .env dosyanızda bulunan APP_KEY değeri anahtar şifreleme işlevi gördüğü için bu değeri (veya tüm dosyanızı) yedeklediğinizden emin olun. Şifrelenmiş veriyi bu anahtar olmadan açmanız mümkün değildir.',
        ],
        'restrict_registration' => [
            'label' => 'Kayıtları sınırla',
            'help' => 'Kayıt işleminin yalnızca sınırlı sayıda e-posta adresi için geçerli olmasını sağlayın. Her iki kural da aynı anda kullanılabilir. Bunun SSO aracılığıyla kayıt üzerinde hiçbir etkisi yoktur.',
        ],
        'restrict_list' => [
            'label' => 'Filtre listesi',
            'help' => 'Bu listede yer alan ePosta adreslerinin kayıt olmasına izin verilecektir. Adresleri dikey çizgi ("|") ile ayırın',
        ],
        'restrict_rule' => [
            'label' => 'Filtreleme kuralları',
            'help' => 'Bu betimlemelere (RegEx) uyan ePostaların kayıt olmasına izin verilecektir',
        ],
        'disable_registration' => [
            'label' => 'Kayıt olmayı devre dışı bırak',
            'help' => 'Yeni kullanıcı kaydını kapatır. Geçersiz kılınmadığı sürece (aşağıya bakın), bu seçenek SSO\'yu da etkiler ve yeni kullanıcıların SSO ile girişini engeller',
        ],
        'enable_sso' => [
            'label' => 'Single Sign-On (SSO) etkileştir',
            'help' => 'Kullanıcıların Single Sign-On ile harici bir hesap üzerinden oturum açmasını sağlar',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'SSO ile kayıt olmayı açık tut',
            'help' => 'Kayıt olma devre dışıyken yeni kullanıcıların SSO ile ilk kez oturum açmasına izin verir',
        ],
        'is_admin' => [
            'label' => 'Yönetici',
            'help' => 'Kullanıcıya "Yönetici" rolü verir. Yöneticiler uygulamanın tamamını, yani ayarları ve diğer kullanıcıları yönetme izinlerine sahiptir, ancak sahip olmadıkları bir 2FA için şifre oluşturamazlar.'
        ],
        'test_email' => [
            'label' => 'ePosta ayarlarını kontrol et',
            'help' => 'Uygulamadaki ePosta ayarlarını kontrol etmek için bir kontrol ePostası gönder. Çalışan bir sistemin olması önemlidir aksi takdirde kullanıcılar parola sıfırlama talebi gönderemezler.',
            'email_will_be_send_to_x' => 'ePosta <span class="is-family-code has-text-info">:email</span> adresine iletilecektir',
        ],
        'cache_management' => [
            'label' => 'Önbellek yönetimi',
            'help' => 'Önbelleğin zaman zaman, örneğin bir değişkenin değiştirilmesinden veya uygulamanın güncellenmesinden sonra, temizlenmesi gerekir. Buradan yapabilirsiniz.',
        ]
    ],

];