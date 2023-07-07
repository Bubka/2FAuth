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

    'resource_not_found' => 'Resource nicht gefunden',
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
    'browser_does_not_support_webauthn' => 'Ihr Gerät unterstützt nicht webauthn. Versuchen Sie es später mit einem moderneren Browser erneut',
    'aborted_by_user' => 'Vom Benutzer abgebrochen',
    'security_device_unsupported' => 'Gerät wird nicht unterstützt oder gerade verwendet',
    'not_allowed_operation' => 'Vorgang nicht erlaubt',
    'unsupported_operation' => 'Vorgang wird nicht unterstützt',
    'unknown_error' => 'Unbekannter Fehler',
    'security_error_check_rpid' => 'Sicherheitsfehler<br/>Prüfen Sie die Umgebungsvariable WEBAUTHN_ID',
    'unsupported_with_reverseproxy' => 'Nicht anwendbar, wenn ein Auth-Proxy benutzt wird',
    'user_deletion_failed' => 'Löschen des Benutzerkontos fehlgeschlagen, es wurden keine Daten gelöscht',
    'auth_proxy_failed' => 'Proxy-Anmeldung scheitern',
    'auth_proxy_failed_legend' => '2Fauth ist so konfiguriert, dass es hinter einem Authentifizierungs-Proxy läuft, aber Ihr Proxy gibt nicht den erwarteten Header zurück. Überprüfen Sie Ihre Konfiguration und versuchen Sie es erneut.',
    'invalid_x_migration' => 'Ungültige oder nicht lesbare Daten bei :appname',
    'invalid_2fa_data' => 'Ungültige 2FA Daten',
    'unsupported_migration' => 'Daten stimmen mit keinem der unterstützten Formate überein',
    'unsupported_otp_type' => 'Nicht unterstützter OTP -Typ',
    'encrypted_migration' => 'Nicht lesbar, die Daten scheinen verschlüsselt zu sein',
    'no_logo_found_for_x' => 'Kein Logo verfügbar für {service}',
    'file_upload_failed' => 'Hochladen der Datei fehlgeschlagen',
    'unauthorized' => 'Nicht berechtigt',
    'unauthorized_legend' => 'Sie haben keine Berechtigung, diese Ressource zu sehen oder diese Aktion auszuführen',
    'cannot_delete_the_only_admin' => 'Löschen des einzigen Admin-Kontos nicht möglichen'
];