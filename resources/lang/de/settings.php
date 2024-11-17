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
    'preferences' => 'Voreinstellungen',
    'account' => 'Benutzerkonto',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Token',
    'options' => 'Einstellungen',
    'user_preferences' => 'Benutzereinstellungen',
    'admin_settings' => 'Admin-Einstellungen',
    'confirm' => [

    ],
    'you_are_administrator' => 'Sie sind ein Administrator',
    'account_linked_to_sso_x_provider' => 'Sie haben sich mit Ihrem :provider -Konto via SSO angemeldet. Ihre Daten können hier nicht geändert werden, sondern auf :provider.',
    'general' => 'Allgemein',
    'security' => 'Sicherheit',
    'notifications' => 'Benachrichtigungen',
    'profile' => 'Profil',
    'change_password' => 'Passwort ändern',
    'personal_access_tokens' => 'Persönliche Zugriffsstoken',
    'token_legend' => 'Persönliche Zugriffstoken ermöglichen es jeder Anwendung, sich bei der 2Fauth-API zu authentifizieren. Sie sollten das Zugriffs-Token als Bearer-Token im Autorisierungs-Header der Anfragen von Verbraucher-Apps angeben.',
    'generate_new_token' => 'Neuen Token erzeugen',
    'revoke' => 'Widerrufen',
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
        'new_token' => 'Neuer Token',
        'some_translation_are_missing' => 'Einige Übersetzungen fehlen bei Verwendung der bevorzugten Sprache des Browsers?',
        'help_translate_2fauth' => 'Helfen Sie 2FAuth zu übersetzen',
        'language' => [
            'label' => 'Sprache',
            'help' => 'Sprache, die zur Übersetzung der 2FAuth-Benutzeroberfläche verwendet wird. Benannte Sprachen sind vollständig. Stellen Sie die Sprache Ihrer Wahl ein, um Ihre Browserpräferenz zu überschreiben.'
        ],
        'timezone' => [
            'label' => 'Zeitzone',
            'help' => 'Die Zeitzone wird auf alle in der Anwendung angezeigten Daten und Zeiten angewendet'
        ],
        'show_otp_as_dot' => [
            'label' => 'Erzeugte <abbr title="One-Time Password">OTP</abbr> als Punkt anzeigen',
            'help' => 'Passwortzeichen werden als *** angezeigt, um die Vertraulichkeit zu gewährleisten. Dies beeinflusst nicht die Kopieren/Einfügen-Funktion'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Verdecktes <abbr title="One-Time Password">OTP</abbr> aufdecken',
            'help' => 'Die Fähigkeit Punkt-verdeckte Passwörter temporär freigeben'
        ],
        'close_otp_on_copy' => [
            'label' => '<abbr title="One-Time Password">OTP</abbr> nach dem Kopieren schließen',
            'help' => 'Bei einem Klick auf das erzeugte Passwort wird es automatisch auf dem Bildschirm ausgeblendet'
        ],
        'auto_close_timeout' => [
            'label' => '<abbr title="One-Time Password">OTP</abbr> automatisch schließen',
            'help' => 'Passwort automatisch nach einem Timeout auf dem Bildschirm ausblenden. Dies vermeidet unnötige Anfragen nach neuen Passwörtern, wenn Sie die Passwortansicht nicht schließen.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Suche beim Kopieren löschen',
            'help' => 'Das Suchfeld leeren, nachdem ein Code in die Zwischenablage kopiert wurde'
        ],
        'sort_case_sensitive' => [
            'label' => 'Groß-/Kleinschreibung beachten',
            'help' => 'Erzwingt bei Aufruf der Funktion Sortieren die Sortierung der Konten unter Berücksichtigung der Groß- und Kleinschreibung'
        ],
        'copy_otp_on_display' => [
            'label' => '<abbr title="One-Time Password">OTP</abbr> auf Anzeige kopieren',
            'help' => 'Kopiert automatisch ein generiertes Passwort bei Anzeige auf dem Bildschirm. Aufgrund der Einschränkungen des Browsers, wird nur das erste <abbr title="Time-based One-Time Password">TOTP</abbr> Passwort kopiert, nicht das rotierende Passwort'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Einfachen QR-Codeleser benutzen',
            'help' => 'Wenn bei der Erfassung von QR-Codes Probleme auftreten, können Sie mit dieser Option zu einem einfacheren, aber zuverlässigeren QR-Codeleser wechseln'
        ],
        'display_mode' => [
            'label' => 'Anzeigemodus',
            'help' => 'Wählen Sie aus, ob Konten als Liste oder als Raster angezeigt werden sollen'
        ],
        'password_format' => [
            'label' => 'Passwort-Formatierung',
            'help' => 'Anzeige der Passwörter ändern durch Gruppierung der Ziffern. Verbessert die Lesbarkeit und Passwörter lassen sich einfacher merken'
        ],
        'pair' => 'nach Paar',
        'pair_legend' => 'Ziffern in zweistellige Gruppen aufteilen',
        'trio_legend' => 'Ziffern in dreistellige Gruppen aufteilen',
        'half_legend' => 'Ziffern in zwei gleiche Gruppen aufteilen',
        'trio' => 'nach Trio',
        'half' => 'nach Hälfte',
        'grid' => 'Raster',
        'list' => 'Liste',
        'theme' => [
            'label' => 'Design',
            'help' => 'Eine bestimmte Darstellung erzwingen oder die in Ihren System-/Browsereinstellungen definierte Darstellung anwenden'
        ],
        'light' => 'Hell',
        'dark' => 'Dunkel',
        'automatic' => 'Automatisch',
        'show_accounts_icons' => [
            'label' => 'Symbole anzeigen',
            'help' => 'Kontosymbole in der Hauptansicht anzeigen'
        ],
        'get_official_icons' => [
            'label' => 'Offizielle Symbole abrufen',
            'help' => '(Versuch) Das offizielle Symbol des 2FA-Ausstellers beim Hinzufügen eines Kontos erhalten'
        ],
        'auto_lock' => [
            'label' => 'Automatische Sperrung',
            'help' => 'Den Benutzer bei Inaktivität automatisch abmelden. Hat keine Auswirkung, wenn die Authentifizierung über einen Proxy erfolgt und keine benutzerdefinierte Logout-URL angegeben ist'
        ],
        'default_group' => [
            'label' => 'Standardgruppe',
            'help' => 'Die Gruppe, der neu erstellte Konten zugeordnet werden',
        ],
        'view_default_group_on_copy' => [
            'label' => 'Standardgruppe beim Kopieren anzeigen',
            'help' => 'Immer zur Standardgruppe zurückkehren, wenn ein OTP kopiert wird',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Konten automatisch speichern',
            'help' => 'Neue Konten werden automatisch nach dem Scannen oder Hochladen eines QR-Codes registriert, ohne dass Sie auf den Speichern-Tastr klicken müssen',
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
            'help' => 'Den letzten Gruppenfilter speichern und ihn beim nächsten Besuch wiederherstellen',
        ],
        'otp_generation' => [
            'label' => 'Passwort zeigen',
            'help' => 'Festlegen, wie und wann <abbr title="One-Time Passwords">OTPs</abbr> angezeigt werden.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'Auf neuem Gerät',
            'help' => 'Erhalten Sie eine E-Mail, wenn sich ein neues Gerät zum ersten Mal mit Ihrem 2FAuth Konto verbindet'
        ],
        'notify_on_failed_login' => [
            'label' => 'Bei fehlgeschlagener Anmeldung',
            'help' => 'Erhalten Sie jedes Mal eine E-Mail, wenn ein Verbindungsversuch zu Ihrem 2FAuth-Konto fehlschlägt'
        ],
        'show_email_in_footer' => [
            'label' => 'E-Mail in Fußzeile anzeigen',
            'help' => 'Die E-Mail des angemeldeten Benutzers in der Fußzeile anstelle von direkten Navigationslinks anzeigen. Die Links stehen dann im Menü hinter einem Klick/Tippen auf die E-Mail-Adresse zur Verfügung.'
        ],
        'otp_generation_on_request' => 'Nach einem Klick/Tippen',
        'otp_generation_on_request_legend' => 'Einzeln, in eigener Ansicht',
        'otp_generation_on_request_title' => 'Konto anklicken, um ein Passwort in einer eigenen Ansicht zu erhalten',
        'otp_generation_on_home' => 'Ständig',
        'otp_generation_on_home_legend' => 'Alle auf dem Startbildschirm anzeigen',
        'otp_generation_on_home_title' => 'Alle Passwörter auf dem Startbildschirm anzeigen, ohne etwas tun zu müssen',
        'never' => 'Niemals',
        'on_otp_copy' => 'Beim Kopieren des Token',
        '1_minutes' => 'Nach 1 Minute',
        '2_minutes' => 'Nach 2 Minuten',
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