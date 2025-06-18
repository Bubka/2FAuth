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

    'admin' => 'Amministratore',
    'admin_panel' => 'Pannello amministratore',
    'app_setup' => 'Impostazioni App',
    'auth' => 'Autenticazione',
    'registrations' => 'Registrazioni',
    'users' => 'Utenti',
    'users_legend' => 'Gestisci gli utenti registrati nella tua istanza o creane nuovi.',
    'admin_settings' => 'Impostazioni amministratore',
    'create_new_user' => 'Creare un utente',
    'new_user' => 'Nuovo utente',
    'search_user_placeholder' => 'Nome utente, email...',
    'quick_filters_colons' => 'Filtri rapidi',
    'user_created' => 'utente creato con successo',
    'confirm' => [
        'delete_user' => 'Sei sicuro di voler eliminare questo utente? Non è possibile annulare.',
        'request_password_reset' => 'Sei sicuro di voler reimpostare la password dell\'utente?',
        'purge_password_reset_request' => 'Sei sicuro di voler eliminare questo dispositivo?',
        'delete_account' => 'Sei sicuro che vuoi cancellare quest\'utente?',
        'edit_own_account' => 'Questo è il tuo account. Sei sicuro?',
        'change_admin_role' => 'Questo avrà gravi ripercussioni sui permessi di questo utente. Sei sicuro?',
        'demote_own_account' => 'Non sarai più un amministratore. Sei sicuro?'
    ],
    'logs' => 'Logs',
    'administration_legend' => 'Le seguenti impostazioni sono globali e si applicano a tutti gli utenti.',
    'user_management' => 'Gestione utenti',
    'oauth_provider' => 'Provider OAuth',
    'account_bound_to_x_via_oauth' => 'Questo account è associato a un account :provider tramite OAuth',
    'last_seen_on_date' => 'Ultimo accesso :date',
    'registered_on_date' => 'Registrato :date',
    'updated_on_date' => 'Aggiornato :date',
    'access' => 'Accesso',
    'password_requested_on_t' => '.',
    'password_request_expired' => 'Esiste una richiesta di reimpostazione della password per questo utente ma è scaduta, il che significa che l\'utente non ha cambiato la sua password in tempo. Questa può essere una richiesta da parte dell\'utente stesso o da un amministratore.',
    'resend_email' => 'Reinvia email',
    'resend_email_title' => 'Reinvia una email di reimpostazione password all\'utente',
    'resend_email_help' => 'Usa <b>Reinvia email</b> per inviare una nuova email di reimpostazione della password all\'utente in modo che possa impostare una nuova password. Questo lascerà la sua password attuale come è e ogni richiesta precedente verrà revocata.',
    'reset_password' => 'Reimposta password',
    'reset_password_help' => 'Usa <b>Reimposta password</b> per forzare il reset della password (questo imposterà una password temporanea) prima di inviare una email di reimpostazione della password all\'utente in modo che possa impostare una nuova password. Qualsiasi richiesta precedente sarà revocata.',
    'reset_password_title' => 'Reimposta la password dell\'utente',
    'password_successfully_reset' => 'Password reimpostata con successo',
    'user_has_x_active_pat' => ':count token attivi',
    'user_has_x_security_devices' => ':count dispositivi di sicurezza (passkeys)',
    'revoke_all_pat_for_user' => 'Revoca tutti i token dell\'utente',
    'revoke_all_devices_for_user' => 'Revoca tutti i dispositivi di sicurezza dell\'utente',
    'danger_zone' => 'Zona Pericolosa',
    'delete_this_user_legend' => 'L\'account utente verrà eliminato così come tutti i suoi dati 2FA.',
    'this_is_not_soft_delete' => 'Questa non è una cancellazione morbida, non è possibile annullare.',
    'delete_this_user' => 'Eliminare questo utente',
    'user_role_updated' => 'Ruolo dell\'utente aggiornato',
    'pats_succesfully_revoked' => 'Pat dell\'utente revocati con successo',
    'security_devices_succesfully_revoked' => 'Dispositivi di sicurezza dell\'utente revocati con successo',
    'variables' => 'Variabili',
    'cache_cleared' => 'Cache cancellata',
    'cache_optimized' => 'Cache ottimizzata',
    'check_now' => 'Controlla ora',
    'view_on_github' => 'Vedi su GitHub',
    'x_is_available' => ':version is available',
    'successful_login_on' => 'Successful login on <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => 'Successful logout on <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => 'Failed login on <span class="light-or-darker">:login_at</span>',
    'viewed_on' => 'Viewed on <span class="light-or-darker">:login_at</span>',
    'last_accesses' => 'Ultimo accesso',
    'see_full_log' => 'Vedi il registro completo',
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
    'storage' => 'Spazio di archiviazione',
    'forms' => [
        'use_encryption' => [
            'label' => 'Proteggi i dati sensibili',
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
            'label' => 'Disabilita la registrazione',
            'help' => 'Prevent new user registration. Unless overridden (see below), this affects SSO as well, so new users won\'t be able to sign in via SSO',
        ],
        'enable_sso' => [
            'label' => 'Abilita SSO',
            'help' => 'Allow visitors to authenticate using an external ID via the Single Sign-On scheme',
        ],
        'use_sso_only' => [
            'label' => 'Usa solo SSO',
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
            'label' => 'E` amministratore',
            'help' => 'Give administrator rights to the user. Administrators have permissions to manage the whole app, i.e. settings and other users, but cannot generate password for a 2FA they don\'t own.'
        ],
        'test_email' => [
            'label' => 'Test di configurazione email',
            'help' => 'Send a test email to control your instance\'s email configuration. It is important to have a working configuration, otherwise users will not be able to request a reset password.',
            'email_will_be_send_to_x' => 'The email will be send to <span class="is-family-code has-text-info">:email</span>',
        ],
        'health_endpoint' => [
            'label' => 'Health endpoint',
            'help' => 'URL you can visit to check the health of this 2FAuth instance. This URL can be used to set up a Docker HEALTHCHECK or a Kubernetes HTTPS Liveness probe.',
        ],
        'cache_management' => [
            'label' => 'Gestione cache',
            'help' => 'Sometimes cache needs to be cleared, for instance after a change to environment variables or an update. You can do it from here.',
        ],
        'store_icon_to_database' => [
            'label' => 'Store icons to database',
            'help' => 'Uploaded icons are registered in the database in addition to the file system storage, which is then used only as a cache. This makes creating a 2FAuth backup much easier, as only the database has to be backed up.<br /><br />But beware, this may has some drawbacks: The database size may increase significantly if the instance hosts many large icons. It may also affect the application performance because the file system is hit more often to ensure it is synchronised with the database.',
        ],
    ],

];