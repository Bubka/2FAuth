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
    'tokens' => 'Tokens',
    'options' => 'Einstellungen',
    'confirm' => [

    ],
    'general' => 'Allgemein',
    'security' => 'Sicherheit',
    'profile' => 'Profile',
    'change_password' => 'Change password',
    'personal_access_tokens' => 'Personal access tokens',
    'token_legend' => 'Personal Access Tokens allow any app to authenticate to the 2Fauth API. You should specify the access token as a Bearer token in the authorization header of consumer apps requests.',
    'generate_new_token' => 'Generate a new token',
    'revoke' => 'Revoke',
    'token_revoked' => 'Token successfully revoked',
    'revoking_a_token_is_permanent' => 'Revoking a token is permanent',
    'confirm' => [
        'revoke' => 'Are you sure you want to revoke this token?',
    ],
    'make_sure_copy_token' => 'Make sure to copy your personal access token now. You won’t be able to see it again!',
    'data_input' => 'Daten-Eingabe',
    'forms' => [
        'edit_settings' => 'Einstellungen bearbeiten',
        'setting_saved' => 'Einstellungen gespeichert',
        'new_token' => 'New token',
        'some_translation_are_missing' => 'Some translations are missing using the browser preferred language?',
        'help_translate_2fauth' => 'Help translate 2FAuth',
        'language' => [
            'label' => 'Sprache',
            'help' => 'Language used to translate the 2FAuth user interface. Named languages are complete, set the one of your choice to override your browser preference.'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated one-time passwords as dot',
            'help' => 'Replace generated password caracters with *** to ensure confidentiality. Do not affect the copy/paste feature.'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close OTP after copy',
            'help' => 'Automatically close the popup showing the generated password after it has been copied'
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
            'help' => 'Log out the user automatically in case of inactivity. Has no effect when authentication is handled by a proxy and no custom logout url is specified.'
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
        'on_otp_copy' => 'On security code copy',
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