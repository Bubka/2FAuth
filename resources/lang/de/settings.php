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

    'settings' => 'Einstellungen',
    'account' => 'Account',
    'password' => 'Passwort',
    'options' => 'Einstellungen',
    'confirm' => [

    ],
    'general' => 'Allgemein',
    'security' => 'Sicherheit',
    'data_input' => 'Daten-Eingabe',
    'forms' => [
        'edit_settings' => 'Einstellungen bearbeiten',
        'setting_saved' => 'Einstellungen gespeichert',
        'language' => [
            'label' => 'Sprache',
            'help' => 'Ändern Sie die Sprache, in der die App-Oberfläche angezeigt wird.'
        ],
        'show_token_as_dot' => [
            'label' => 'Generiertes Token als Punkte anzeigen',
            'help' => 'Tokenzeichen werden als *** angezeigt, um die Vertraulichkeit zu gewährleisten. Dies beeinflusst nicht die Kopieren/Einfügen Funktion.'
        ],
        'close_token_on_copy' => [
            'label' => 'Token nach dem Kopieren schließen',
            'help' => 'Schließe automatisch das Popup-Fenster mit dem generierten Token nachdem es kopiert wurde'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Benutze den einfachen QR-Codeleser',
            'help' => 'Wenn bei der Erfassung von QR-Codes Probleme auftreten können Sie mit dieser Option zu einem einfacheren, aber zuverlässigeren QR-Codeleser wechseln'
        ],
        'display_mode' => [
            'label' => 'Anzeigemodus',
            'help' => 'Wählen Sie, ob Konten als Liste oder als Raster angezeigt werden sollen'
        ],
        'grid' => 'Raster',
        'list' => 'Liste',
        'show_accounts_icons' => [
            'label' => 'Symbole anzeigen',
            'help' => 'Kontosymbole in der Hauptansicht anzeigen'
        ],
        'auto_lock' => [
            'label' => 'Automatische Sperrung',
            'help' => 'Bei Inaktivität automatisch abmelden'
        ],
        'use_encryption' => [
            'label' => 'Sensible Daten schützen',
            'help' => 'Vertrauliche Daten, die 2FA-Geheimnisse und E-Mails, werden verschlüsselt in der Datenbank gespeichert. Erstellen Sie ein Backup von der APP_KEY Variablen in der .env Datei (oder der gesamten Datei), da sie als Schlüssel zur gesicherten Datenbank dient. Es gibt keine Möglichkeit, verschlüsselte Daten ohne diesen Schlüssel zu wiederherzustellen.',
        ],
        'default_group' => [
            'label' => 'Standardgruppe',
            'help' => 'Die Gruppe, der neu erstellte Konten zugeordnet werden',
        ],
        'useDirectCapture' => [
            'label' => 'Direkteingabe',
            'help' => 'Wählen Sie aus, ob Sie einen Eingabemodus unter den Verfügbaren wählen möchten oder ob Sie direkt den Standard-Eingabemodus verwenden möchten',
        ],
        'defaultCaptureMode' => [
            'label' => 'Standard-Eingabemodus',
            'help' => 'Standard-Eingabemodus, der verwendet wird, falls die Direkteingabe aktiviert ist',
        ],
        'remember_active_group' => [
            'label' => 'Gruppenfilter merken',
            'help' => 'Speichert den letzten Gruppenfilter und stellt ihn bei Ihrem nächsten Besuch wieder her',
        ],
        'never' => 'Niemals',
        'on_token_copy' => 'Beim Kopieren des Tokens',
        '1_minutes' => 'Nach 1 Minute',
        '5_minutes' => 'Nach 5 Minuten',
        '10_minutes' => 'Nach 10 Minuten',
        '15_minutes' => 'Nach 15 Minuten',
        '30_minutes' => 'Nach 30 Minuten',
        '1_hour' => 'Nach 1 Stunde',
        '1_day' => 'Nach 1 Tag',
        'livescan' => 'QR-Code scannen',
        'upload' => 'QR-Code hochladen',
        'advanced_form' => 'Erweitertes Formular',
    ],

];
