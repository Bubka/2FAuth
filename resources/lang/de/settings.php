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
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Token',
    'options' => 'Einstellungen',
    'user_options' => 'Benutzeroptionen',
    'confirm' => [

    ],
    'general' => 'Allgemein',
    'security' => 'Sicherheit',
    'profile' => 'Profil',
    'change_password' => 'Passwort ändern',
    'personal_access_tokens' => 'Persönliche Zugriffsstokens',
    'token_legend' => 'Persönliche Zugriffstoken ermöglichen es jeder Anwendung, sich bei der 2Fauth-API zu authentifizieren. Sie sollten das Zugriffs-Token als Bearer-Token im Autorisierungs-Header der Anfragen von Verbraucher-Apps angeben.',
    'generate_new_token' => 'Neuen Token generieren',
    'revoke' => 'Zurückziehen',
    'token_revoked' => 'Token erfolgreich widerrufen',
    'revoking_a_token_is_permanent' => 'Widerruf eines Token ist dauerhaft',
    'confirm' => [
        'revoke' => 'Sind Sie sicher, dass Sie diesen Token widerrufen möchten?',
    ],
    'make_sure_copy_token' => 'Kopieren Sie Ihren persönlichen Zugangs-Token jetzt. Sie werden ihn nicht mehr sehen können!',
    'data_input' => 'Daten-Eingabe',
    'forms' => [
        'edit_settings' => 'Einstellungen bearbeiten',
        'setting_saved' => 'Einstellungen gespeichert',
        'new_token' => 'Neues Token',
        'some_translation_are_missing' => 'Einige Übersetzungen fehlen bei Verwendung der bevorzugten Sprache des Browsers?',
        'help_translate_2fauth' => 'Hilf 2FAuth zu übersetzen',
        'language' => [
            'label' => 'Sprache',
            'help' => 'Sprache, die zur Übersetzung der 2FAuth-Benutzeroberfläche verwendet wird. Benannte Sprachen sind vollständig, stellen Sie die Sprache Ihrer Wahl ein, um Ihre Browserpräferenz zu überschreiben.'
        ],
        'show_otp_as_dot' => [
            'label' => 'Generierte Einmalpasswörter als Punkte anzeigen',
            'help' => 'Replace generated password caracters with *** to ensure confidentiality. Do not affect the copy/paste feature'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close <abbr title="One-Time Password">OTP</abbr> after copy',
            'help' => 'Clicking a generated password to copy it automatically hide it from the screen'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copy <abbr title="One-Time Password">OTP</abbr> on display',
            'help' => 'Automatically copy a generated password right after it appears on screen. Due to browsers limitations, only the first <abbr title="Time-based One-Time Password">TOTP</abbr> password will be copied, not the rotating ones'
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
        'get_official_icons' => [
            'label' => 'Offizielle Icons erhalten',
            'help' => '(Versuch) Das offizielle Symbol des 2FA-Ausstellers beim Hinzufügen eines Kontos zu erhalten'
        ],
        'auto_lock' => [
            'label' => 'Automatische Sperrung',
            'help' => 'Meldet den Benutzer bei Inaktivität automatisch ab. Hat keine Auswirkung, wenn die Authentifizierung über einen Proxy erfolgt und keine benutzerdefinierte Logout-URL angegeben ist.'
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
        'on_otp_copy' => 'Beim Kopieren des Tokens',
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