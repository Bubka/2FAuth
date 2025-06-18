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
    'admin_panel' => 'Panel administratora',
    'app_setup' => 'Ustawienia aplikacji',
    'auth' => 'Autoryzacja',
    'registrations' => 'Rejestracje',
    'users' => 'Users',
    'users_legend' => 'Zarządzaj użytkownikami zarejestrowanymi w Twojej instancji lub twórz nowych.',
    'admin_settings' => 'Ustawienia administratora',
    'create_new_user' => 'Utwórz użytkownika',
    'new_user' => 'Nowy użytkownik',
    'search_user_placeholder' => 'Nazwa użytkownika, e-maill...',
    'quick_filters_colons' => 'Szybkie filtrowanie',
    'user_created' => 'Szybkie filtrowanie',
    'confirm' => [
        'delete_user' => 'Czy na pewno chcesz usunąć tego użytkownika? Nie ma od tego odwrotu.',
        'request_password_reset' => 'Czy na pewno chcesz zresetować hasło tego użytkownika?',
        'purge_password_reset_request' => 'Czy na pewno chcesz odwołać poprzednią prośbę?',
        'delete_account' => 'Czy na pewno chcesz usunąć tego użytkownika?',
        'edit_own_account' => 'To jest Twoje własne konto. Czy na pewno?',
        'change_admin_role' => 'Będzie to miało poważny wpływ na uprawnienia tego użytkownika. Czy na pewno?',
        'demote_own_account' => 'Nie będziesz już administratorem. Naprawdę na pewno?'
    ],
    'logs' => 'Logi',
    'administration_legend' => 'Poniższe ustawienia są globalne i mają zastosowanie do wszystkich użytkowników.',
    'user_management' => 'Zarządzanie użytkownikiem',
    'oauth_provider' => 'Dostawca zabezpieczeń OAuth',
    'account_bound_to_x_via_oauth' => 'This account is bound to a :provider account via OAuth',
    'last_seen_on_date' => 'Ostatnio widziany :date',
    'registered_on_date' => 'Zarejestrowany :date',
    'updated_on_date' => 'Zaaktualizowany :date',
    'access' => 'Dostęp',
    'password_requested_on_t' => 'Dla tego użytkownika istnieje żądanie zresetowania hasła (żądanie wysłane o godzinie :d atetime), co oznacza, że użytkownik nie zmienił jeszcze hasła, ale otrzymany link jest nadal ważny. Może to być prośba od samego użytkownika lub od administratora.',
    'password_request_expired' => 'Dla tego użytkownika istnieje żądanie zresetowania hasła, ale wygasło, co oznacza, że użytkownik nie zmienił swojego hasła na czas. Może to być prośba od samego użytkownika lub od administratora.',
    'resend_email' => 'Wyślij ponownie wiadomość e-mail',
    'resend_email_title' => 'Ponowne wysyłanie wiadomości e-mail z prośbą o zresetowanie hasła do użytkownika',
    'resend_email_help' => 'Użyj opcji <b>Wyślij ponownie wiadomość e-mail</b>, aby wysłać nową wiadomość e-mail dotyczącą resetowania hasła do użytkownika, aby mógł ustawić nowe hasło. Spowoduje to pozostawienie obecnego hasła bez zmian, a wszelkie poprzednie żądania zostaną odwołane.',
    'reset_password' => 'Resetuj hasło',
    'reset_password_help' => 'Użyj opcji <b>Resetuj hasło</b>, aby wymusić zresetowanie hasła (spowoduje to ustawienie hasła tymczasowego) przed wysłaniem wiadomości e-mail z resetowaniem hasła do użytkownika, aby mógł ustawić nowe hasło. Wszelkie wcześniejsze prośby zostaną odwołane.',
    'reset_password_title' => 'Reset the user\'s password',
    'password_successfully_reset' => 'Password successfully reset',
    'user_has_x_active_pat' => ':count active token(s)',
    'user_has_x_security_devices' => ':count security device(s) (passkeys)',
    'revoke_all_pat_for_user' => 'Revoke all user\'s tokens',
    'revoke_all_devices_for_user' => 'Revoke all user\'s security devices',
    'danger_zone' => 'Danger Zone',
    'delete_this_user_legend' => 'The user account will be deleted as well as all its 2FA data.',
    'this_is_not_soft_delete' => 'This is not a soft delete, there is no going back.',
    'delete_this_user' => 'Delete this user',
    'user_role_updated' => 'Uprawnienia użytkownika zmodyfikowane',
    'pats_succesfully_revoked' => 'User\'s PATs successfully revoked',
    'security_devices_succesfully_revoked' => 'User\'s security devices successfully revoked',
    'variables' => 'Variables',
    'cache_cleared' => 'Cache cleared',
    'cache_optimized' => 'Zoptymalizowana pamięć podręczna',
    'check_now' => 'Sprawdź teraz',
    'view_on_github' => 'View on Github',
    'x_is_available' => ':version is available',
    'successful_login_on' => 'Successful login on <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => 'Successful logout on <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => 'Failed login on <span class="light-or-darker">:login_at</span>',
    'viewed_on' => 'Viewed on <span class="light-or-darker">:login_at</span>',
    'last_accesses' => 'Last accesses',
    'see_full_log' => 'See full log',
    'browser_on_platform' => ':browser on :platform',
    'access_log_has_more_entries' => 'The access log contains more entries.',
    'access_log_legend_for_user' => 'Full access log for user :username',
    'show_last_month_log' => 'Show entries from the last month',
    'show_three_months_log' => 'Show entries from the last 3 months',
    'show_six_months_log' => 'Show entries from the last 6 months',
    'show_one_year_log' => 'Show entries from the last year',
    'sort_by_date_asc' => 'Show least recent first',
    'sort_by_date_desc' => 'Show most recent first',
    'single_sign_on' => 'Single Sign-On (SSO)',
    'database' => 'Database',
    'file_system' => 'File system',
    'storage' => 'Storage',
    'forms' => [
        'use_encryption' => [
            'label' => 'Protect sensitive data',
            'help' => 'Sensitive data, the 2FA secrets and emails, are stored encrypted in database. Be sure to backup the APP_KEY value of your .env file (or the whole file) as it serves as key encryption. There is no way to decypher encrypted data without this key.',
        ],
        'restrict_registration' => [
            'label' => 'Restrict registration',
            'help' => 'Make registration only available to a limited range of email addresses. Both rules can be used simultaneously. This has no effect on registration via SSO.',
        ],
        'restrict_list' => [
            'label' => 'Filtering list',
            'help' => 'Emails in this list will be allowed to register. Separate addresses with a pipe ("|")',
        ],
        'restrict_rule' => [
            'label' => 'Filtering rule',
            'help' => 'Emails matching this regular expression will be allowed to register',
        ],
        'disable_registration' => [
            'label' => 'Disable registration',
            'help' => 'Prevent new user registration. Unless overridden (see below), this affects SSO as well, so new users won\'t be able to sign in via SSO',
        ],
        'enable_sso' => [
            'label' => 'Enable SSO',
            'help' => 'Allow visitors to authenticate using an external ID via the Single Sign-On scheme',
        ],
        'use_sso_only' => [
            'label' => 'Use SSO only',
            'help' => 'Make SSO the only available method to log in to 2FAuth. Password login and Webauthn are then disabled for regular users. Administrators are not affected by this restriction.',
        ],
        'allow_pat_in_sso_only' => [
            'label' => 'Allow PAT usage',
            'help' => 'Let users create personal access tokens and use them to authenticate with third party application like the 2FAuth web extension',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'Keep SSO registration enabled',
            'help' => 'Allow new users to sign in for the first time via SSO whereas registration is disabled',
        ],
        'is_admin' => [
            'label' => 'Is administrator',
            'help' => 'Give administrator rights to the user. Administrators have permissions to manage the whole app, i.e. settings and other users, but cannot generate password for a 2FA they don\'t own.'
        ],
        'test_email' => [
            'label' => 'Email configuration test',
            'help' => 'Send a test email to control your instance\'s email configuration. It is important to have a working configuration, otherwise users will not be able to request a reset password.',
            'email_will_be_send_to_x' => 'The email will be send to <span class="is-family-code has-text-info">:email</span>',
        ],
        'health_endpoint' => [
            'label' => 'Health endpoint',
            'help' => 'URL you can visit to check the health of this 2FAuth instance. This URL can be used to set up a Docker HEALTHCHECK or a Kubernetes HTTPS Liveness probe.',
        ],
        'cache_management' => [
            'label' => 'Cache management',
            'help' => 'Sometimes cache needs to be cleared, for instance after a change to environment variables or an update. You can do it from here.',
        ],
        'store_icon_to_database' => [
            'label' => 'Store icons to database',
            'help' => 'Przesłane ikony są rejestrowane w bazie danych poza pamięcią systemową plików, która jest następnie używana tylko jako pamięć podręczna. To sprawia, że tworzenie kopii zapasowej 2FAuth jest znacznie łatwiejsze, ponieważ tylko baza danych musi być w kopii zapasowej.<br /><br />Ale zapamiętaj, może to mieć pewne wady: rozmiar bazy danych może znacznie wzrosnąć, jeśli instancja posiada wiele dużych ikon. Może to również mieć wpływ na wydajność aplikacji.',
        ],
    ],

];