<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'admin' => 'Administrator',
    'app_setup' => 'App-Einstellungen',
    'registrations' => 'Registrierungen',
    'users' => 'Benutzer',
    'users_legend' => 'Benutzer verwalten, die bereits registriert sind, oder neue Benutzer erstellen.',
    'admin_settings' => 'Administrator-Einstellungen',
    'create_new_user' => 'Benutzer erstellen',
    'new_user' => 'Neuer Benutzer',
    'search_user_placeholder' => 'Benutzername, E-Mail...',
    'quick_filters_colons' => 'Schnellfilter:',
    'user_created' => 'Benutzer erfolgreich erstellt',
    'confirm' => [
        'delete_user' => 'Sind Sie sicher, dass Sie diesen Benutzer löschen möchten? Es gibt kein Zurück mehr.',
        'request_password_reset' => 'Sind Sie sicher, dass Sie das Passwort dieses Benutzers zurücksetzen möchten?',
        'purge_password_reset_request' => 'Sind Sie sicher, dass Sie die vorherige Anfrage widerrufen möchten?',
        'delete_account' => 'Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?',
        'edit_own_account' => 'Dies ist Ihr eigener Account. Sind Sie sicher?',
        'change_admin_role' => 'Dies wird gravierende Auswirkungen auf die Berechtigungen dieses Benutzers haben. Sind Sie sicher?',
        'demote_own_account' => 'Sie werden kein Administrator mehr sein. Wirklich sicher?'
    ],
    'logs' => 'Protokolle',
    'administration_legend' => 'Die folgenden Einstellungen sind global und gelten für alle Benutzer.',
    'user_management' => 'Benutzerverwaltung',
    'oauth_provider' => 'OAuth-Provider',
    'account_bound_to_x_via_oauth' => 'Dieses Konto ist mit einem :provider Konto über OAuth verbunden',
    'last_seen_on_date' => 'Zuletzt gesehen :date',
    'registered_on_date' => 'Registriert: :date',
    'updated_on_date' => 'Aktualisiert :date',
    'access' => 'Zugang',
    'password_requested_on_t' => 'Für diesen Benutzer existiert eine Anfrage zum Zurücksetzen des Passworts (Anfrage um :datetime), was bedeutet, dass der Benutzer sein Passwort noch nicht geändert hat, aber der Link, den er erhalten hat, weiterhin gültig ist. Dies könnte eine Anfrage des Benutzers selbst oder eines Administrators sein.',
    'password_request_expired' => 'Für diesen Benutzer existiert eine Anfrage zum Zurücksetzen des Passworts, die aber abgelaufen ist, was bedeutet, dass der Benutzer sein Passwort nicht rechtzeitig geändert hat. Dies kann eine Anfrage des Benutzers selbst oder eines Administrators sein.',
    'resend_email' => 'E-Mail erneut senden',
    'resend_email_title' => 'Eine E-Mail zum Zurücksetzen des Passworts erneut senden',
    'resend_email_help' => 'Verwenden Sie <b>E-Mail</b> erneut senden, um eine neue E-Mail zum Zurücksetzen des Passworts an den Benutzer zu senden, damit er ein neues Passwort festlegen kann. Dies wird sein aktuelles Passwort unverändert lassen und jede vorherige Anfrage wird widerrufen.',
    'reset_password' => 'Passwort zurücksetzen',
    'reset_password_help' => 'Verwenden Sie <b>Passwort zurücksetzen</b> um ein Passwort zurückzusetzen (dies wird ein temporäres Passwort setzen), bevor Sie eine E-Mail an den Benutzer senden, damit er ein neues Passwort setzen kann. Alle vorherigen Anfragen werden widerrufen.',
    'reset_password_title' => 'Passwort des Benutzers zurücksetzen',
    'password_successfully_reset' => 'Passwort erfolgreich zurückgesetzt',
    'user_has_x_active_pat' => ':count aktive Token',
    'user_has_x_security_devices' => ':count Sicherheitsgerät(e) (Passkeys)',
    'revoke_all_pat_for_user' => 'Alle Benutzer-Token widerrufen',
    'revoke_all_devices_for_user' => 'Alle Sicherheitsgeräte des Benutzers widerrufen',
    'danger_zone' => 'Gefahrenzone',
    'delete_this_user_legend' => 'Das Benutzerkonto sowie alle 2FA-Daten werden gelöscht.',
    'this_is_not_soft_delete' => 'Dies ist kein einfacher Löschvorgang, es gibt kein Zurück mehr.',
    'delete_this_user' => 'Benutzer löschen',
    'user_role_updated' => 'Benutzerrolle aktualisiert',
    'pats_succesfully_revoked' => 'Benutzer-PATs erfolgreich widerrufen',
    'security_devices_succesfully_revoked' => 'Sicherheitsgeräte des Benutzers wurden erfolgreich widerrufen',
    'variables' => 'Variablen',
    'cache_cleared' => 'Cache geleert',
    'cache_optimized' => 'Cache optimiert',
    'check_now' => 'Jetzt prüfen',
    'view_on_github' => 'Auf GitHub anzeigen',
    'x_is_available' => ':version ist verfügbar',
    'successful_login_on' => 'Erfolgreiche Anmeldung auf <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => 'Erfolgreiche Abmeldung auf <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => 'Anmeldung fehlgeschlagen auf <span class="light-or-darker">:login_at</span>',
    'viewed_on' => 'Angesehen auf <span class="light-or-darker">:login_at</span>',
    'last_accesses' => 'Letzte Zugriffe',
    'see_full_log' => 'Gesamtes Zugriffsprotokoll anzeigen',
    'browser_on_platform' => ':browser auf :platform',
    'access_log_has_more_entries' => 'Das Zugriffsprotokoll enthält weitere Einträge.',
    'access_log_legend_for_user' => 'Vollständiges Zugriffsprotokoll für Benutzer :username',
    'show_last_month_log' => 'Einträge aus dem letzten Monat anzeigen',
    'show_three_months_log' => 'Einträge aus den letzten 3 Monaten anzeigen',
    'show_six_months_log' => 'Einträge aus den letzten 6 Monaten anzeigen',
    'show_one_year_log' => 'Einträge aus dem letzten Jahr anzeigen',
    'sort_by_date_asc' => 'Älteste zuerst anzeigen',
    'sort_by_date_desc' => 'Aktuellste zuerst anzeigen',
    'forms' => [
        'use_encryption' => [
            'label' => 'Sensible Daten schützen',
            'help' => 'Vertrauliche Daten, die 2FA-Geheimnisse und E-Mails, werden verschlüsselt in der Datenbank gespeichert. Erstellen Sie ein Backup der APP_KEY-Variablen der .env Datei (oder der gesamten Datei), da sie als Schlüssel zur gesicherten Datenbank dient. Es gibt keine Möglichkeit, verschlüsselte Daten ohne diesen Schlüssel zu wiederherzustellen.',
        ],
        'restrict_registration' => [
            'label' => 'Registrierung einschränken',
            'help' => 'Die Registrierung nur für eine begrenzte Anzahl von E-Mail-Adressen verfügbar machen. Beide Regeln können gleichzeitig verwendet werden. Dies hat keinen Einfluss auf die Registrierung über SSO.',
        ],
        'restrict_list' => [
            'label' => 'Filterliste',
            'help' => 'E-Mails in dieser Liste können sich registrieren. Adressen mit einem Pfeil trennen ("|")',
        ],
        'restrict_rule' => [
            'label' => 'Filterregel',
            'help' => 'E-Mails mit diesem regulären Ausdruck dürfen sich registrieren',
        ],
        'disable_registration' => [
            'label' => 'Registrierung deaktivieren',
            'help' => 'Verhindert eine neue Benutzerregistrierung. Sofern nicht überschrieben (siehe unten) wirkt sich dies auch auf SSO aus, so dass neue Benutzer sich nicht via SSO anmelden können',
        ],
        'enable_sso' => [
            'label' => 'Single Sign-On (SSO) aktivieren',
            'help' => 'Besuchern die Authentifizierung mit einer externen ID über das Single Sign-On Schema erlauben',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'SSO-Registrierung aktiviert lassen',
            'help' => 'Neuen Benutzern erlauben, sich zum ersten Mal über SSO anzumelden, während die Registrierung deaktiviert ist',
        ],
        'is_admin' => [
            'label' => 'Ist Admininstrator',
            'help' => 'Geben Sie dem Benutzer Administratorrechte. Administratoren haben die Berechtigung, die gesamte App zu verwalten, d. h. Einstellungen und andere Benutzer, können aber kein Passwort für eine 2FA erstellen, die sie nicht besitzen.'
        ],
        'test_email' => [
            'label' => 'E-Mail-Konfigurationstest',
            'help' => 'Senden Sie eine Test-E-Mail, um die E-Mail-Konfiguration Ihrer Instanz zu kontrollieren. Es ist wichtig, eine funktionierende Konfiguration zu haben, sonst können Benutzer kein Zurücksetzen des Passworts anfordern.',
            'email_will_be_send_to_x' => 'Diese E-Mail wird an <span class="is-family-code has-text-info">:email</span> gesendet',
        ],
        'cache_management' => [
            'label' => 'Cache-Verwaltung',
            'help' => 'Manchmal muss der Cache geleert werden, zum Beispiel nach einer Änderung an Umgebungsvariablen oder einer Aktualisierung. Sie können es von hier aus tun.',
        ]
    ],

];