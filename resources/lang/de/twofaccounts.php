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
    'icon' => 'Symbol',
    'icon_to_illustrate_the_account' => 'Symbol, das den Account darstellt',
    'remove_icon' => 'Symbol entfernen',
    'no_account_here' => 'Noch keine 2FA vorhanden!',
    'add_first_account' => 'Wählen Sie eine Methode und fügen Sie Ihren ersten Account hinzu',
    'use_full_form' => 'Oder nutzen Sie das vollständige Formular',
    'add_one' => 'Konto hinzufügen',
    'show_qrcode' => 'QR-Code anzeigen',
    'no_service' => '- kein Service -',
    'account_created' => 'Konto erfolgreich erstellt',
    'account_updated' => 'Konto erfolgreich aktualisiert',
    'accounts_deleted' => 'Konto(en) erfolgreich gelöscht',
    'accounts_moved' => 'Konto(en) erfolgreich verschoben',
    'export_selected_accounts' => 'Ausgewählte Konten exportieren',
    'twofauth_export_format' => '2FAuth-Format',
    'twofauth_export_format_sub' => 'Daten mit dem 2FAuth json Schema exportieren',
    'twofauth_export_format_desc' => 'Sie sollten diese Option bevorzugen, wenn Sie eine Sicherung erstellen müssen, die wiederhergestellt werden kann. Dieses Format kümmert sich um die Symbole.',
    'twofauth_export_format_url' => 'Die Schema-Definition ist hier beschrieben:',
    'twofauth_export_schema' => '2FAuth-Exportschema',
    'otpauth_export_format' => 'otpauth URIs',
    'otpauth_export_format_sub' => 'Daten als Liste der otpauth URIs exportieren',
    'otpauth_export_format_desc' => 'otpauth URI ist das am häufigsten verwendete Format für den Austausch von 2FA-Daten zum Beispiel in Form eines QR-Codes, wenn Sie 2FA auf einer Website aktivieren. Wählen Sie diese Option, wenn Sie von 2FAuth wechseln möchten.',
    'reveal' => 'aufdecken',
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
        'i_m_lucky' => 'Mein Glück versuchen',
        'i_m_lucky_legend' => 'Durch "Mein Glück versuchen" können Sie das offizielle Symbol des jeweiligen Dienstes erhalten. Geben Sie den tatsächlichen Namen des Dienstes ohne die Erweiterung ".xyz" ein und versuchen Sie, Tippfehler zu vermeiden (Beta-Funktion)',
        'test' => 'Test',
        'group' => [
            'label' => 'Gruppe',
            'help' => 'Die Gruppe, der das Konto zugeordnet werden soll'
        ],
        'secret' => [
            'label' => 'Geheimnis',
            'help' => 'Der Schlüssel, der zur Erzeugung Ihrer Sicherheitscodes verwendet wird'
        ],
        'plain_text' => 'Klartext',
        'otp_type' => [
            'label' => 'Wählen Sie den <abbr title="One-Time Password">OTP</abbr> Typ',
            'help' => 'Zeitbasierte OTP oder HMAC-basierte OTP oder Steam OTP'
        ],
        'digits' => [
            'label' => 'Ziffern',
            'help' => 'Die Anzahl der Ziffern der erzeugten Sicherheitscodes'
        ],
        'algorithm' => [
            'label' => 'Algorithmus',
            'help' => 'Der Algorithmus, der zur Sicherung Ihrer Sicherheitscodes verwendet wird'
        ],
        'period' => [
            'label' => 'Gültigkeitsdauer',
            'placeholder' => 'Standard ist 30',
            'help' => 'Die Gültigkeitsdauer der erzeugten Sicherheitscodes in Sekunden'
        ],
        'counter' => [
            'label' => 'Zähler',
            'placeholder' => 'Standard ist 0',
            'help' => 'Der erste Zählerwert',
            'help_lock' => 'Es ist riskant, den Zähler zu ändern, da Sie das Konto mit dem Verifizierungsserver des Dienstes desynchronisieren können. Verwenden Sie das Schlosssymbol, um Änderungen zu ermöglichen, aber nur, wenn Sie wissen, was Sie tun'
        ],
        'image' => [
            'label' => 'Bild',
            'placeholder' => 'http://...',
            'help' => 'Die Url eines externen Bildes, das als Kontosymbol verwendet werden soll'
        ],
        'options_help' => 'Sie können die folgenden Einstellungen leer lassen, wenn Sie nicht wissen, wie Sie sie einstellen. In dem Fall werden die Standardwerte verwendet.',
        'alternative_methods' => 'Alternative Methoden',
        'spaces_are_ignored' => 'Ungewollte Leerzeichen werden automatisch entfernt'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Der Live-Scan kann nicht gestartet werden :(',
        'need_grant_permission' => [
            'reason' => '2FAuth hat keine Berechtigung auf Ihre Kamera zuzugreifen',
            'solution' => 'Sie müssen die Erlaubnis erteilen, um Ihre Gerätekamera zu verwenden. Falls Sie bereits verweigert haben und Ihr Browser Sie nicht erneut anfragt, verweisen Sie bitte auf die Browser-Dokumentation, um herauszufinden, wie Sie die Erlaubnis erteilen können.',
            'click_camera_icon' => 'Dies geschieht üblicherweise durch Klicken auf ein geschlitztes Kamerasymbol in oder neben der Adressleiste des Browsers',
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
        'discard' => 'Sind Sie sicher, dass Sie dieses Konto verwerfen möchten?',
        'discard_all' => 'Sind Sie sicher, dass Sie alle Konten verwerfen möchten?',
        'discard_duplicates' => 'Möchten Sie wirklich alle Duplikate verwerfen?',
    ],
    'import' => [
        'import' => 'Import',
        'to_import' => 'Import',
        'import_legend' => '2FAuth kann Daten aus verschiedenen 2FA-Apps importieren.',
        'import_legend_afterpart' => 'Benutzen Sie die Export-Funktion dieser Apps, um eine Migrations-Ressource wie einen QR-Code oder eine JSON-Datei zu erhalten und lade Sie sie hier.',
        'upload' => 'Hochladen',
        'scan' => 'Scannen',
        'supported_formats_for_qrcode_upload' => 'Akzeptiert: jpg, jpeg, png, bmp, gif, svg oder webp',
        'supported_formats_for_file_upload' => 'Akzeptiert: Klartext, Json, 2fas',
        'expected_format_for_direct_input' => 'Erwartet: Eine Liste der otpauth URI, eins pro Zeile',
        'supported_migration_formats' => 'Unterstütze Migrationsformate',
        'qr_code' => 'QR-Code',
        'text_file' => 'Textdatei',
        'direct_input' => 'Direkte Eingabe',
        'plain_text' => 'Klartext',
        'parsing_data' => 'Daten werden verarbeitet...',
        'issuer' => 'Aussteller',
        'imported' => 'Importiert',
        'failure' => 'Fehlschlag',
        'x_valid_accounts_found' => ':count gültige Konten gefunden',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'Die folgenden 2FA-Konten wurden in der Migrations-Ressource gefunden. Bisher wurden noch keine von ihnen zu 2FAuth hinzugefügt.',
        'use_buttons_to_save_or_discard' => 'Verwenden Sie die verfügbaren Schaltflächen, um sie dauerhaft in Ihrer 2FA-Sammlung zu speichern oder zu verwerfen.',
        'import_all' => 'Alle importieren',
        'import_this_account' => 'Dieses Konto importieren',
        'discard_all' => 'Alles verwerfen',
        'discard_duplicates' => 'Duplikate verwerfen',
        'discard_this_account' => 'Dieses Konto verwerfen',
        'generate_a_test_password' => 'Ein Testpasswort erzeugen',
        'possible_duplicate' => 'Ein Konto mit den gleichen Daten ist bereits vorhanden',
        'invalid_account' => '- ungültiges Konto -',
        'invalid_service' => '- ungültiges Service -',
        'do_not_set_password_or_encryption' => 'Aktivieren Sie NICHT den Passwortschutz oder die Verschlüsselung, wenn Sie Daten aus einer anderen 2FA-App exportieren, die Sie in 2FAuth importieren möchten.',
    ],

];