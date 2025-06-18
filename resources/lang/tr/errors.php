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

    'resource_not_found' => 'Kaynak bulunamadı',
    'error_occured' => 'Bir hata oluştu:',
    'refresh' => 'Yenile',
    'no_valid_otp' => 'Bu QR kodunda geçerli bir OTP kaynağı yok',
    'something_wrong_with_server' => 'Sunucunuzda bir sorun var',
    'Unable_to_decrypt_uri' => 'URI şifresi çözülemedi',
    'not_a_supported_otp_type' => 'Bu OTP formatı şu anda desteklenmiyor',
    'cannot_create_otp_without_secret' => 'Gizli anahtar olmadan OTP oluşturulamaz',
    'data_of_qrcode_is_not_valid_URI' => 'Bu QR kodunun verisi geçerli bir OTP Auth URI değil. QR kodu şunu içeriyor:',
    'wrong_current_password' => 'Mevcut şifre yanlış, hiçbir şey değişmedi',
    'error_during_encryption' => 'Şifreleme başarısız oldu, veritabanınız korumasız kaldı.',
    'error_during_decryption' => 'Şifre çözme başarısız oldu, veritabanınız hala korunuyor. Bu, genellikle bir veya daha fazla hesaba ait şifrelenmiş verilerin bütünlük sorunlarından kaynaklanır.',
    'qrcode_cannot_be_read' => 'Bu QR kodu okunamıyor',
    'too_many_ids' => 'sorgu parametresine çok fazla kimlik eklendi, en fazla 100 izin verilir',
    'delete_user_setting_only' => 'Sadece kullanıcı tarafından oluşturulan ayarlar silinebilir',
    'indecipherable' => '*anlaşılmaz*',
    'cannot_decipher_secret' => 'Gizli anahtar çözülemedi. Bu, genellikle 2Fauth\'un .env yapılandırma dosyasında yanlış bir APP_KEY ayarlanmasından veya veritabanında bozulmuş verilerin saklanmasından kaynaklanır.',
    'https_required' => 'HTTPS bağlamı gereklidir',
    'browser_does_not_support_webauthn' => 'Cihazınız webauthn\'yi desteklemiyor. Daha modern bir tarayıcı kullanarak daha sonra tekrar deneyin',
    'aborted_by_user' => 'Kullanıcı tarafından iptal edildi',
    'security_device_already_registered' => 'Cihaz zaten kayıtlı',
    'not_allowed_operation' => 'İşleme izin verilmiyor',
    'no_authenticator_support_specified_algorithms' => 'Hiçbir doğrulayıcı belirtilen algoritmaları desteklemiyor',
    'authenticator_missing_discoverable_credential_support' => 'Doğrulayıcıda keşfedilebilir kimlik bilgisi desteği bulunmuyor',
    'authenticator_missing_user_verification_support' => 'Doğrulayıcıda kullanıcı doğrulama desteği bulunmuyor',
    'unknown_error' => 'Bilinmeyen hata',
    'security_error_check_rpid' => 'Güvenlik hatası<br/>WEBAUTHN_ID env değişkeninizi kontrol edin',
    '2fauth_has_not_a_valid_domain' => '2FAuth\'un alan adı geçerli bir alan adı değil',
    'user_id_not_between_1_64' => 'Kullanıcı ID\'si 1 ile 64 karakter arasında değildi',
    'no_entry_was_of_type_public_key' => 'Hiçbir giriş "public-key" türünde değildi',
    'unsupported_with_reverseproxy' => 'Bir kimlik doğrulama proxy\'si veya SSO kullanırken geçerli değildir',
    'unsupported_with_sso_only' => 'Bu kimlik doğrulama metodu yalnızca yöneticiler içindir. Kullanıcılar SSO ile giriş yapmalıdır.',
    'user_deletion_failed' => 'Kullanıcı hesabı silme işlemi başarısız oldu, hiçbir veri silinmedi',
    'auth_proxy_failed' => 'Proxy kimlik doğrulaması başarısız oldu',
    'auth_proxy_failed_legend' => '2Fauth, bir kimlik doğrulama proxy\'si arkasında çalışacak şekilde yapılandırılmış, ancak proxy\'niz beklenen başlığı döndürmüyor. Yapılandırmanızı kontrol edin ve tekrar deneyin.',
    'invalid_x_migration' => 'Geçersiz veya okunamaz :appname verisi',
    'invalid_2fa_data' => 'Geçersiz 2FA verisi',
    'unsupported_migration' => 'Veriler desteklenen hiçbir formata uymuyor',
    'unsupported_otp_type' => 'Desteklenmeyen OTP türü',
    'encrypted_migration' => 'Okunamaz, veri şifrelenmiş gibi görünüyor',
    'no_icon_for_this_variant' => 'No icon available in this variant',
    'file_upload_failed' => 'Dosya yüklemesi başarısız oldu',
    'unauthorized' => 'Yetkisiz',
    'unauthorized_legend' => 'Bu kaynağı görüntülemek veya bu işlemi gerçekleştirmek için izniniz yok',
    'cannot_delete_the_only_admin' => 'Tek admin hesabını silemezsiniz',
    'cannot_demote_the_only_admin' => 'Tek admin hesabını görevden alamazsınız',
    'error_during_data_fetching' => '💀 Veri alımı sırasında bir sorun oluştu',
    'check_failed_try_later' => 'Kontrol başarısız oldu, lütfen daha sonra tekrar deneyin',
    'sso_disabled' => 'SSO devre dışı bırakıldı',
    'sso_bad_provider_setup' => 'Bu SSO sağlayıcısı .env dosyanızda tam olarak yapılandırılmamış',
    'sso_failed' => 'SSO aracılığıyla kimlik doğrulama reddedildi',
    'sso_no_register' => 'Kayıtlar devre dışı bırakıldı',
    'sso_email_already_used' => 'Aynı e-posta adresine sahip bir kullanıcı hesabı zaten mevcut, ancak dış hesap kimliğinizle eşleşmiyor. Bu e-posta ile zaten 2FAuth\'a kayıtlıysanız SSO kullanmayın.',
    'account_managed_by_external_provider' => 'Hesap dış bir sağlayıcı tarafından yönetiliyor',
    'data_cannot_be_refreshed_from_server' => 'Veri sunucudan yenilenemiyor',
    'no_pwd_reset_for_this_user_type' => 'Bu kullanıcı için parola sıfırlama kullanılamıyor',
    'cannot_detect_qrcode_in_image' => 'Görüntüde bir QR kodu algılanamıyor, görüntüyü kırpmayı deneyin',
    'cannot_decode_detected_qrcode' => 'Algılanan QR kodu çözülemiyor, görüntüyü kırpmayı veya netleştirmeyi deneyin',
    'qrcode_has_invalid_checksum' => 'QR kodu geçersiz bir checksum\'a sahip',
    'no_readable_qrcode' => 'Okunabilir bir QR kodu yok',
    'failed_icon_store_database_toggling' => 'Simgeler içe aktarılamadı. Seçenek eski haline döndürüldü.',
    'failed_to_retrieve_app_settings' => 'Failed to retrieve application settings',
    'reserved_name_please_choose_something_else' => 'Reserved name, please choose something else',
];