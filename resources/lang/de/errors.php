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
    'cannot_register_more_user' => 'Sie können nicht mehr als einen Benutzer registrieren.',
    'refresh' => 'Aktualisieren',
    'no_valid_otp' => 'Keine gültige OTP-Ressource in diesem QR-Code',
    'something_wrong_with_server' => 'Etwas stimmt mit Ihrem Server nicht',
    'Unable_to_decrypt_uri' => 'URI konnte nicht entschlüsselt werden',
    'not_a_supported_otp_type' => 'Dieses OTP-Format wird derzeit nicht unterstützt',
    'cannot_create_otp_without_secret' => 'Das OTP kann nicht ohne Geheimnis erstellt werden',
    'data_of_qrcode_is_not_valid_URI' => 'Die Daten dieses QR-Codes sind keine gültige OTP-Auth-URI:',
    'wrong_current_password' => 'Aktuelles Passwort falsch, nichts wurde geändert',
    'error_during_encryption' => 'Verschlüsselung fehlgeschlagen, Ihre Datenbank bleibt ungeschützt.',
    'error_during_decryption' => 'Entschlüsselung fehlgeschlagen, Ihre Datenbank bleibt geschützt. Dies wird hauptsächlich durch eine Integritätsproblem verschlüsselter Daten für ein oder mehrere Konten verursacht.',
    'qrcode_cannot_be_read' => 'Dieser QR-Code ist unlesbar',
    'too_many_ids' => 'zu viele Ids wurden in den Abfrageparameter eingefügt, maximal 100 erlaubt',
    'delete_user_setting_only' => 'Nur benutzerdefinierte Einstellungen können gelöscht werden',
    'indecipherable' => '*nicht lesbar*',
    'cannot_decipher_secret' => 'The secret cannot be deciphered. This is mainly caused by a wrong APP_KEY set in the .env configuration file of 2Fauth or a corrupted data stored in database.',
    'https_required' => 'HTTPS-Kontext erforderlich',
    'browser_does_not_support_webauthn' => 'Your device does not support webauthn. Try again later using a more modern browser',
    'aborted_by_user' => 'Vom Benutzer abgebrochen',
    'security_device_unsupported' => 'Sicherheitsgerät nicht unterstützt',
    'unsupported_with_reverseproxy' => 'Not applicable when using an auth proxy',
    'user_deletion_failed' => 'User account deletion failed, no data have been deleted',
    'auth_proxy_failed' => 'Proxy-Anmeldung scheitern',
    'auth_proxy_failed_legend' => '2Fauth is configured to run behind an authentication proxy but your proxy does not return the expected header. Check your configuration and try again.',
    'invalid_google_auth_migration' => 'Invalid or unreadable Google Authenticator data',
    'unsupported_otp_type' => 'Nicht unterstützter OTP -Typ',
    'no_logo_found_for_x' => 'No logo available for {service}'
];