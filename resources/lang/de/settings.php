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
    'preferences' => 'Vorlieben',
    'account' => 'Account',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Token',
    'options' => 'Einstellungen',
    'user_preferences' => 'Benutzereinstellungen',
    'admin_settings' => 'Admin-Einstellungen',
    'confirm' => [

    ],
    'you_are_administrator' => 'Du bist ein Administrator',
    'account_linked_to_sso_x_provider' => 'Sie haben sich mit Ihrem :provider-Konto via SSO angemeldet. Ihre Daten können hier nicht geändert werden, sondern auf :provider.',
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
            'help' => 'Passwortzeichen werden als *** angezeigt, um die Vertraulichkeit zu gewährleisten. Dies beeinflusst nicht die Kopieren/Einfügen Funktion'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Decke verdecktes <abbr title="One-Time Password">OTP</abbr> auf',
            'help' => 'Lass die Fähigkeit Punkt-verdeckte Passwörter temporär freigeben'
        ],
        'close_otp_on_copy' => [
            'label' => 'Schließe <abbr title="One-Time Password">OTP</abbr> nach dem Kopieren',
            'help' => 'Bei einem Klick auf das generierte Passwort wird es automatisch auf dem Bildschirm ausgeblendet'
        ],
        'clear_search_on_copy' => [
            'label' => 'Suche beim Kopieren löschen',
            'help' => 'Leeren das Suchfeld, nachdem ein Code in die Zwischenablage kopiert wurde'
        ],
        'copy_otp_on_display' => [
            'label' => 'Das angezeigte, einmaliges Passwort (OTP) kopieren',
            'help' => 'Kopiert automatisch ein generiertes Passwort bei Anzeige auf dem Bildschirm. Aufgrund der Einschränkungen des Browsers wird nur das erste <abbr title="Time-based One-Time Password">TOTP</abbr> Passwort kopiert, nicht das rotierende Passwort'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Benutze den einfachen QR-Codeleser',
            'help' => 'Wenn bei der Erfassung von QR-Codes Probleme auftreten können Sie mit dieser Option zu einem einfacheren, aber zuverlässigeren QR-Codeleser wechseln'
        ],
        'display_mode' => [
            'label' => 'Anzeigemodus',
            'help' => 'Wählen Sie, ob Konten als Liste oder als Raster angezeigt werden sollen'
        ],
        'password_format' => [
            'label' => 'Passwortformatierung',
            'help' => 'Anzeige der Passwörter ändern durch Gruppierung der Ziffern. Verbessert die Lesbarkeit und Passwörter lassen sich einfacher merken'
        ],
        'pair' => 'nach Paar',
        'pair_legend' => 'Ziffern in zweistellige Gruppen aufteilen',
        'trio_legend' => 'Ziffern in dreistellige Gruppen aufteilen',
        'half_legend' => 'Ziffern in zwei gleiche Gruppen aufteilen',
        'trio' => 'durch Trio',
        'half' => 'nach Hälfte',
        'grid' => 'Raster',
        'list' => 'Liste',
        'theme' => [
            'label' => 'Erscheinungsbild',
            'help' => 'Erzwinge eine bestimmte Darstellung oder wende die in deinen System/Browser-Einstellungen definierte Darstellung an'
        ],
        'light' => 'Hell',
        'dark' => 'Dunkel',
        'automatic' => 'Automatisch',
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
        'default_group' => [
            'label' => 'Standardgruppe',
            'help' => 'Die Gruppe, der neu erstellte Konten zugeordnet werden',
        ],
        'view_default_group_on_copy' => [
            'label' => 'Standardgruppe beim Kopieren anzeigen',
            'help' => 'Immer zur Standardgruppe zurückkehren, wenn ein OTP kopiert wird',
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
        'otp_generation' => [
            'label' => 'Passwort zeigen',
            'help' => 'Legelt fest, wie und wann <abbr title="One-Time Passwords">OTPs</abbr> angezeigt werden.<br/>',
        ],
        'otp_generation_on_request' => 'Nach einem Klick/Tippen',
        'otp_generation_on_request_legend' => 'Einzeln, in eigener Ansicht',
        'otp_generation_on_request_title' => 'Konto anklicken um ein Passwort in einer eigenen Ansicht zu erhalten',
        'otp_generation_on_home' => 'Ständig',
        'otp_generation_on_home_legend' => 'Alle auf dem Startbildschirm anzeigen',
        'otp_generation_on_home_title' => 'Alle Passwörter auf dem Startbildschirm anzeigen, ohne etwas tun zu müssen',
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