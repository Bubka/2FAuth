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

    'service' => 'Dienst',
    'account' => 'Benutzerkonto',
    'accounts' => 'Benutzerkonten',
    'icon' => 'Symbol',
    'no_account_here' => 'Noch keine 2FA!',
    'add_first_account' => 'Erstes Konto hinzufügen',
    'use_full_form' => 'Oder nutzen Sie das vollständige Formular',
    'add_one' => 'Konto hinzufügen',
    'show_qrcode' => 'QR-Code anzeigen',
    'no_service' => '- kein Service -',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'Max Mustermann',
        ],
        'new_account' => 'Neues Konto',
        'edit_account' => 'Konto bearbeiten',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'QR-Code scannen',
        'upload_qrcode' => 'QR-Code hochladen',
        'use_advanced_form' => 'Erweitertes Formular verwenden',
        'prefill_using_qrcode' => 'Mit einem QR-Code ausfüllen',
        'use_qrcode' => [
            'val' => 'QR-Code verwenden',
            'title' => 'Verwenden Sie einen QR-Code, um das Formular auszufüllen',
        ],
        'unlock' => [
            'val' => 'Entsperren',
            'title' => 'Entsperren (auf eigene Gefahr)',
        ],
        'lock' => [
            'val' => 'Sperren',
            'title' => 'Sperren',
        ],
        'choose_image' => 'Upload',
        'i_m_lucky' => 'I\'m lucky',
        'i_m_lucky_legend' => 'The "I\'m lucky" button try to get the official icon of the given service. Enter actual service name without ".xyz" extension and try to avoid typo. (beta feature)',
        'test' => 'Test',
        'secret' => [
            'label' => 'Geheimnis',
            'help' => 'Der Schlüssel, der zur Generierung Ihrer Sicherheitscodes verwendet wird'
        ],
        'plain_text' => 'Klartext',
        'otp_type' => [
            'label' => 'Wählen Sie die Art des zu erstellenden OTP',
            'help' => 'Zeit- oder HMAC-basiertes OTP'
        ],
        'digits' => [
            'label' => 'Ziffern',
            'help' => 'Die Anzahl der Ziffern der generierten Sicherheitscodes'
        ],
        'algorithm' => [
            'label' => 'Algorithmus',
            'help' => 'Der Algorithmus, der zur Sicherung Ihrer Sicherheitscodes verwendet wird'
        ],
        'period' => [
            'label' => 'Gültigkeitsdauer',
            'placeholder' => 'Standard ist 30',
            'help' => 'Die Gültigkeitsdauer der generierten Sicherheitscodes in Sekunden'
        ],
        'counter' => [
            'label' => 'Zähler',
            'placeholder' => 'Standard ist 0',
            'help' => 'Der Zählerwert am Anfang',
            'help_lock' => 'It is risky to edit the counter as you can desynchronize the account with the verification server of the service. Use the lock icon to enable modification, but only if you know for you are doing'
        ],
        'image' => [
            'label' => 'Bild',
            'placeholder' => 'http://...',
            'help' => 'The url of an external image to use as the account icon'
        ],
        'options_help' => 'Sie können die folgenden Einstellungen leer lassen, wenn Sie nicht wissen, wie Sie sie einstellen. In dem Fall werden die Standardwerte verwendet.',
        'alternative_methods' => 'Alternative Methoden',
    ],
    'stream' => [
        'live_scan_cant_start' => 'Der Live-Scan kann nicht gestartet werden :(',
        'need_grant_permission' => [
            'reason' => '2FAuth hat keine Berechtigung auf Ihre Kamera zuzugreifen',
            'solution' => 'Sie müssen die Erlaubnis erteilen, um Ihre Gerätekamera zu verwenden. Falls Sie bereits verweigert haben und Ihr Browser Sie nicht erneut anfragt, verweisen Sie bitte auf die Browser-Dokumentation, um herauszufinden, wie Sie die Erlaubnis erteilen können.'
        ],
        'not_readable' => [
            'reason' => 'Fehler beim Laden des Scanners',
            'solution' => 'Wird die Kamera bereits verwendet? Stellen Sie sicher, dass keine andere App Ihre Kamera verwendet und versuchen Sie es erneut'
        ],
        'no_cam_on_device' => [
            'reason' => 'Keine Kamera in diesem Gerät',
            'solution' => 'Maybe you forgot to plug in your webcam'
        ],
        'secured_context_required' => [
            'reason' => 'Sichere Umgebung erforderlich',
            'solution' => 'HTTPS wird für den Live-Scan benötigt. Wenn Sie 2FAuth auf Ihrem Computer ausführen, verwenden Sie nur localhost und keinen anderen virtuellen Host'
        ],
        'https_required' => 'HTTPS für Kamera-Übertragung erforderlich',
        'camera_not_suitable' => [
            'reason' => 'Die verbauten Kameras sind nicht geeignet',
            'solution' => 'Bitte verwenden Sie ein anderes Gerät/Kamera'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Stream API wird von diesem Browser nicht unterstützt',
            'solution' => 'Sie sollten einen modernen Browser verwenden'
        ],
    ],
    'confirm' => [
        'delete' => 'Sind Sie sicher, dass Sie dieses Konto löschen möchten?',
        'cancel' => 'Das Konto wird gelöscht. Sind Sie sicher?',
        'discard' => 'Are you sure you want to discard this account?',
        'discard_all' => 'Are you sure you want to discard all accounts?',
        'discard_duplicates' => 'Möchten Sie wirklich alle Duplikate wegwerfen?',
    ],
    'import' => [
        'import' => 'Import',
        'to_import' => 'Import',
        'import_legend' => 'Importieren Sie Ihre Google Authenticator-Konten.',
        'use_the_gauth_qr_code' => 'Laden ein G-Auth QR code',
        'issuer' => 'Aussteller',
        'imported' => 'Importiert',
        'failure' => 'Fehler',
        'x_valid_accounts_found' => '{count} gültige Konten gefunden',
        'import_all' => 'Alle importieren',
        'import_this_account' => 'Importiere dieses Konto',
        'discard_all' => 'Alles wegwerfen',
        'discard_duplicates' => 'Duplikate wegwerfen',
        'discard_this_account' => 'Dieses Konto wegwerfen',
        'generate_a_test_password' => 'Ein Testpasswort generieren',
        'possible_duplicate' => 'Ein Konto mit der gleichen Daten ist bereits vorhanden',
    ],

];