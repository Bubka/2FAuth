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

    'resource_not_found' => 'Ressource nicht gefunden',
    'error_occured' => 'Ein Fehler ist aufgetreten:',
    'refresh' => 'Aktualisieren',
    'no_valid_otp' => 'Keine gültige OTP-Ressource in diesem QR-Code',
    'something_wrong_with_server' => 'Etwas stimmt mit Ihrem Server nicht',
    'Unable_to_decrypt_uri' => 'URI konnte nicht entschlüsselt werden',
    'not_a_supported_otp_type' => 'Dieses OTP-Format wird derzeit nicht unterstützt',
    'cannot_create_otp_without_secret' => 'Das OTP kann nicht ohne Geheimnis erstellt werden',
    'data_of_qrcode_is_not_valid_URI' => 'Dieser QR-Code enthält keine gültige OTP Auth URI. Inhalt des QR-Codes:',
    'wrong_current_password' => 'Aktuelles Passwort falsch, nichts wurde geändert',
    'error_during_encryption' => 'Verschlüsselung fehlgeschlagen, Ihre Datenbank bleibt ungeschützt.',
    'error_during_decryption' => 'Entschlüsselung fehlgeschlagen, Ihre Datenbank bleibt geschützt. Dies wird hauptsächlich durch eine Integritätsproblem verschlüsselter Daten für ein oder mehrere Konten verursacht.',
    'qrcode_cannot_be_read' => 'Dieser QR-Code ist unlesbar',
    'too_many_ids' => 'zu viele Ids wurden in den Abfrageparameter eingefügt, maximal 100 erlaubt',
    'delete_user_setting_only' => 'Nur benutzerdefinierte Einstellungen können gelöscht werden',
    'indecipherable' => '*nicht lesbar*',
    'cannot_decipher_secret' => 'Das Geheimnis kann nicht entschlüsselt werden. Dies wird hauptsächlich durch einen falsch gesetzten APP_KEY in der .env-Konfigurationsdatei von 2Fauth oder durch beschädigte Daten in der Datenbank verursacht.',
    'https_required' => 'HTTPS-Kontext erforderlich',
    'browser_does_not_support_webauthn' => 'Ihr Gerät unterstützt nicht Webauthn. Versuchen Sie es später mit einem moderneren Browser erneut',
    'aborted_by_user' => 'Vom Benutzer abgebrochen',
    'security_device_already_registered' => 'Gerät ist bereits registriert',
    'not_allowed_operation' => 'Vorgang nicht erlaubt',
    'no_authenticator_support_specified_algorithms' => 'Keine Authentifikatoren unterstützen die angegebenen Algorithmen',
    'authenticator_missing_discoverable_credential_support' => 'Authentifikator fehlt erkennbare Anmeldeinformationen',
    'authenticator_missing_user_verification_support' => 'Authentifikator fehlt Unterstützung für die Benutzerüberprüfung',
    'unknown_error' => 'Unbekannter Fehler',
    'security_error_check_rpid' => 'Sicherheitsfehler<br/>Prüfen Sie die Umgebungsvariable WEBAUTHN_ID',
    '2fauth_has_not_a_valid_domain' => '2FAuths Domain ist keine gültige Domain',
    'user_id_not_between_1_64' => 'Benutzer-ID war nicht zwischen 1 und 64 Zeichen',
    'no_entry_was_of_type_public_key' => 'Kein Eintrag vom Typ "public-key"',
    'unsupported_with_reverseproxy' => 'Nicht anwendbar, wenn ein Auth-Proxy oder SSO benutzt wird',
    'unsupported_with_sso_only' => 'Diese Authentifizierungsmethode ist nur für Administratoren. Benutzer müssen sich mit SSO anmelden.',
    'user_deletion_failed' => 'Löschen des Benutzerkontos fehlgeschlagen, es wurden keine Daten gelöscht',
    'auth_proxy_failed' => 'Proxy-Authentifizierung fehlgeschlagen',
    'auth_proxy_failed_legend' => '2Fauth ist so konfiguriert, dass es hinter einem Authentifizierungs-Proxy läuft, aber Ihr Proxy gibt nicht den erwarteten Header zurück. Überprüfen Sie Ihre Konfiguration und versuchen Sie es erneut.',
    'invalid_x_migration' => 'Ungültige oder nicht lesbare Daten bei :appname',
    'invalid_2fa_data' => 'Ungültige 2FA-Daten',
    'unsupported_migration' => 'Daten stimmen mit keinem der unterstützten Formate überein',
    'unsupported_otp_type' => 'Nicht unterstützter OTP-Typ',
    'encrypted_migration' => 'Nicht lesbar, die Daten scheinen verschlüsselt zu sein',
    'no_logo_found_for_x' => 'Kein Logo verfügbar für :service',
    'file_upload_failed' => 'Hochladen der Datei fehlgeschlagen',
    'unauthorized' => 'Nicht berechtigt',
    'unauthorized_legend' => 'Sie haben keine Berechtigung, diese Ressource zu sehen oder diese Aktion auszuführen',
    'cannot_delete_the_only_admin' => 'Löschen des einzigen Admin-Kontos nicht möglichen',
    'cannot_demote_the_only_admin' => 'Der einzige Admin-Account kann nicht degradiert werden',
    'error_during_data_fetching' => '💀 Während des Datenabrufs ist etwas schief gelaufen',
    'check_failed_try_later' => 'Überprüfung fehlgeschlagen, bitte später erneut versuchen',
    'sso_disabled' => 'SSO ist deaktiviert',
    'sso_bad_provider_setup' => 'Dieser SSO-Provider ist nicht vollständig in Ihrer .env-Datei eingerichtet',
    'sso_failed' => 'Authentifizierung über SSO abgelehnt',
    'sso_no_register' => 'Registrierungen sind deaktiviert',
    'sso_email_already_used' => 'Ein Benutzerkonto mit der gleichen E-Mail-Adresse existiert bereits, aber es stimmt nicht mit Ihrer externen Konto-ID überein. Verwenden Sie kein SSO, wenn Sie bereits bei 2FAuth mit dieser E-Mail registriert sind.',
    'account_managed_by_external_provider' => 'Konto von einem externen Anbieter verwaltet',
    'data_cannot_be_refreshed_from_server' => 'Daten können nicht vom Server aktualisiert werden',
    'no_pwd_reset_for_this_user_type' => 'Passwort zurücksetzen für diesen Benutzer nicht verfügbar',
    'cannot_detect_qrcode_in_image' => 'Es kann kein QR-Code im Bild erkannt werden. Bitte das Bild zuschneiden',
    'cannot_decode_detected_qrcode' => 'Kann den QR-Code nicht dekodieren. Bitte das Bild zuschneiden oder schärfen',
    'qrcode_has_invalid_checksum' => 'QR-Code hat eine ungültige Prüfsumme',
    'no_readable_qrcode' => 'Kein lesbarer QR-Code',
    'failed_icon_store_database_toggling' => 'Migration of icons failed. The setting has been restored to its previous value.',
];