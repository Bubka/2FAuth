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
    'icon_for_account_x_at_service_y' => 'Icon of the {account} account at {service}',
    'icon_to_illustrate_the_account' => 'Icon that illustrates the account',
    'remove_icon' => 'Remove icon',
    'no_account_here' => 'Noch keine 2FA!',
    'add_first_account' => 'Pick a method and add your first account',
    'use_full_form' => 'Oder nutzen Sie das vollständige Formular',
    'add_one' => 'Konto hinzufügen',
    'show_qrcode' => 'QR-Code anzeigen',
    'no_service' => '- kein Service -',
    'account_created' => 'Account successfully created',
    'account_updated' => 'Account successfully updated',
    'accounts_deleted' => 'Account(s) successfully deleted',
    'accounts_moved' => 'Account(s) successfully moved',
    'export_selected_to_json' => 'Download a json export of selected accounts',
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
        'choose_image' => 'Hochladen',
        'i_m_lucky' => 'Ich habe Glück',
        'i_m_lucky_legend' => 'Mit der Schaltfläche "Ich habe Glück" wird versucht, das offizielle Symbol des jeweiligen Dienstes zu erhalten. Geben Sie den tatsächlichen Namen des Dienstes ohne die Erweiterung ".xyz" ein und versuchen Sie, Tippfehler zu vermeiden (Beta-Funktion).',
        'test' => 'Test',
        'secret' => [
            'label' => 'Geheimnis',
            'help' => 'Der Schlüssel, der zur Generierung Ihrer Sicherheitscodes verwendet wird'
        ],
        'plain_text' => 'Klartext',
        'otp_type' => [
            'label' => 'Choose the type of <abbr title="One-Time Password">OTP</abbr> to create',
            'help' => 'Time-based OTP or HMAC-based OTP or Steam OTP'
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
            'help_lock' => 'Es ist riskant, den Zähler zu ändern, da Sie das Konto mit dem Verifizierungsserver des Dienstes desynchronisieren können. Verwenden Sie das Schlosssymbol, um Änderungen zu ermöglichen, aber nur, wenn Sie wissen, was Sie tun'
        ],
        'image' => [
            'label' => 'Bild',
            'placeholder' => 'http://...',
            'help' => 'Die Url eines externen Bildes, das als Kontosymbol verwendet werden soll'
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
            'solution' => 'Vielleicht haben Sie vergessen, Ihre Webcam anzuschließen'
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
        'discard' => 'Sind Sie sicher, dass Sie dieses Konto auflösen wollen?',
        'discard_all' => 'Sind Sie sicher, dass Sie alle Konten verwerfen wollen?',
        'discard_duplicates' => 'Möchten Sie wirklich alle Duplikate wegwerfen?',
    ],
    'import' => [
        'import' => 'Import',
        'to_import' => 'Import',
        'import_legend' => '2FAuth can import data from various 2FA apps.<br />Use the Export feature of these apps to get a migration resource (a QR code or a file) and load it using your preferred method below.',
        'upload' => 'Upload',
        'scan' => 'Scan',
        'supported_formats_for_qrcode_upload' => 'Accepted: jpg, jpeg, png, bmp, gif, svg, or webp',
        'supported_formats_for_file_upload' => 'Accepted: Plain text, json, 2fas',
        'supported_migration_formats' => 'Supported migration formats',
        'qr_code' => 'QR Code',
        'plain_text' => 'Plain text',
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
        'invalid_account' => '- invalid account -',
        'invalid_service' => '- invalid service -',
        'do_not_set_password_or_encryption' => 'Do NOT enable Password protection or Encryption when you export data (from a 2FA app) you want to import into 2FAuth.',
    ],

];