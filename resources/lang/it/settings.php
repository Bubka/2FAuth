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

    'settings' => 'Impostazioni',
    'preferences' => 'Preferenze',
    'account' => 'Account',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Opzioni',
    'user_preferences' => 'Preferenze utente',
    'admin_settings' => 'Impostazioni amministratore',
    'confirm' => [

    ],
    'you_are_administrator' => 'Sei un amministratore',
    'account_linked_to_sso_x_provider' => 'Hai effettuato l\'accesso tramite SSO utilizzando il tuo account :provider. Le tue informazioni non possono essere modificate qui ma su :provider.',
    'general' => 'Generale',
    'security' => 'Sicurezza',
    'notifications' => 'Notifiche',
    'profile' => 'Profilo',
    'change_password' => 'Cambia password',
    'personal_access_tokens' => 'Token di accesso personali',
    'token_legend' => 'I token di accesso personale consentono a qualsiasi app di autenticarsi all\'API 2Fauth. È necessario specificare il token di accesso come token Bearer nell\'intestazione di autorizzazione delle richieste delle app consumer.',
    'generate_new_token' => 'Genera un nuovo token',
    'revoke' => 'Revoca',
    'token_revoked' => 'Token revocato con successo',
    'revoking_a_token_is_permanent' => 'Revocare un token è permanente',
    'confirm' => [
        'revoke' => 'Sei sicuro di voler revocare questo token?',
    ],
    'make_sure_copy_token' => 'Assicurati di copiare il tuo token di accesso personale ora. Non sarai in grado di vederlo di nuovo!',
    'data_input' => 'Inserire i dati',
    'settings_managed_by_administrator' => 'Some settings are being managed by your administrator',
    'forms' => [
        'edit_settings' => 'Modifica impostazioni',
        'setting_saved' => 'Impostazioni salvate!',
        'new_token' => 'Nuovo token',
        'some_translation_are_missing' => 'Mancano alcune traduzioni usando la lingua preferita del browser?',
        'help_translate_2fauth' => 'Aiutaci a tradurre 2FAuth',
        'language' => [
            'label' => 'Lingua',
            'help' => 'Lingua utilizzata per tradurre l\'interfaccia utente 2FAuth. Le lingue con nome sono complete, imposta quella scelta per sovrascrivere le preferenze del browser.'
        ],
        'timezone' => [
            'label' => 'Fuso orario',
            'help' => 'Il fuso orario è applicato a tutte le date e gli orari mostrati nell\'applicazione'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated OTP as dot',
            'help' => 'Replace generated password characters with *** to ensure confidentiality. Does not affect the copy/paste feature'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured OTP',
            'help' => 'Lascia la possibilità di rivelare temporaneamente le password oscurate'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close OTP after copy',
            'help' => 'Fare clic su una password generata per copiarla automaticamente nascondendola dallo schermo'
        ],
        'show_next_otp' => [
            'label' => 'Show next OTP',
            'help' => 'Preview the next password, i.e. the password that will replace the current password when it expires. Preferences set for the current OTP also apply to the next one (formatting, show as dot)'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close OTP',
            'help' => 'Nascondi automaticamente la password sullo schermo dopo un timeout. Questo evita richieste non necessarie di nuove password se dimentichi di chiudere la vista password.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Svuota ricerca sulla copia',
            'help' => 'Svuota la casella di ricerca subito dopo che un codice è stato copiato negli appunti'
        ],
        'sort_case_sensitive' => [
            'label' => 'Ordina maiuscole e minuscole',
            'help' => 'Quando invocato, forza la funzione Ordina per ordinare gli account in base a maiuscolo/minuscolo'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copy OTP on display',
            'help' => 'Copia automaticamente una password generata subito dopo che appare sullo schermo. A causa delle limitazioni del browser, solo la prima password <abbr title="Time-based One-Time Password">TOTP</abbr> verrà copiata, non quelle rotanti'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Usa lettore di codice QR di base',
            'help' => 'Se si verificano problemi durante la cattura di codici QR consente questa opzione di passare a un lettore di codice QR più semplice ma più affidabile'
        ],
        'display_mode' => [
            'label' => 'Modalità Display',
            'help' => 'Choose whether you want accounts to be displayed as a list or as a grid'
        ],
        'password_format' => [
            'label' => 'Password formatting',
            'help' => 'Change how the passwords are displayed by grouping digits to ease readability and memorization'
        ],
        'pair' => 'by Pair',
        'pair_legend' => 'Group digits two by two',
        'trio_legend' => 'Group digits three by three',
        'half_legend' => 'Split digits into two equals groups',
        'trio' => 'by Trio',
        'half' => 'by Half',
        'grid' => 'Griglia',
        'list' => 'Lista',
        'theme' => [
            'label' => 'Tema',
            'help' => 'Force a specific theme or apply the theme defined in your system/browser preferences'
        ],
        'light' => 'Chiaro',
        'dark' => 'Scuro',
        'automatic' => 'Automatico',
        'show_accounts_icons' => [
            'label' => 'Mostra icone',
            'help' => 'Show account icons in the main view'
        ],
        'get_official_icons' => [
            'label' => 'Ottieni icone ufficiali',
            'help' => '(Try to) Get the official icon of the 2FA issuer when adding an account'
        ],
        'icon_collection' => [
            'label' => 'Favorite icon source',
            'help' => 'The icons collection to be queried at first when an official icon is required. Changing this setting does not refresh icons that have already been fetched.'
        ],
        'icon_variant' => [
            'label' => 'Icon variant',
            'help' => 'Some icons are available in different flavors to best suit dark or light user interfaces. Set the one you want to look for first. The regular variant will automatically be fetched as a fallback.'
        ],
        'icon_variant_strict_fetch' => [
            'label' => 'Strict fetch',
            'help' => 'Narrow the fetch to the specified variant. If the variant is missing, 2FAuth will not try to fallback to the regular variant.'
        ],
        'auto_lock' => [
            'label' => 'Blocco automatico',
            'help' => 'Log out the user automatically in case of inactivity. Has no effect when authentication is handled by a proxy and no custom logout url is specified.'
        ],
        'default_group' => [
            'label' => 'Gruppo predefinito',
            'help' => '',
        ],
        'view_default_group_on_copy' => [
            'label' => 'View default group on copy',
            'help' => 'Always return to the default group when an OTP is copied',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Auto-salvataggio account',
            'help' => 'New accounts are automatically registered after scanning or uploading a QR code, no need to click a Save button',
        ],
        'useDirectCapture' => [
            'label' => 'Input diretto',
            'help' => 'Choose whether you want to be prompted to choose an input mode among those available or if you want to directly use the default input mode',
        ],
        'defaultCaptureMode' => [
            'label' => 'Default input mode',
            'help' => 'Default input mode used when the Direct input option is On',
        ],
        'remember_active_group' => [
            'label' => 'Remember group filter',
            'help' => 'Save the last group filter applied and restore it on your next visit',
        ],
        'otp_generation' => [
            'label' => 'Mostra Password',
            'help' => 'Set how and when <abbr title="One-Time Passwords">OTPs</abbr> are displayed.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'On new device',
            'help' => 'Get an email when a new device connects to your 2FAuth account for the first time'
        ],
        'notify_on_failed_login' => [
            'label' => 'On failed login',
            'help' => 'Get an email each time an attempt to connect to your 2FAuth account fails'
        ],
        'show_email_in_footer' => [
            'label' => 'Show email in footer',
            'help' => 'Display the logged-in user\'s email in the footer instead of direct navigation links. The links are then available in a menu behind a click/tap on the email address.'
        ],
        'otp_generation_on_request' => 'After a click/tap',
        'otp_generation_on_request_legend' => 'Alone, in its own view',
        'otp_generation_on_request_title' => 'Click an account to get a password in a dedicated view',
        'otp_generation_on_home' => 'Constantly',
        'otp_generation_on_home_legend' => 'All of them, on home',
        'otp_generation_on_home_title' => 'Show all passwords in the main view, without doing anything',
        'never' => 'Mai',
        'on_otp_copy' => 'Alla copia del codice di sicurezza',
        '1_minutes' => 'Dopo 1 minuto',
        '2_minutes' => 'Dopo 2 minuti',
        '5_minutes' => 'Dopo 5 minuti',
        '10_minutes' => 'Dopo 10 minuti',
        '15_minutes' => 'Dopo 15 minuti',
        '30_minutes' => 'Dopo 30 minuti',
        '1_hour' => 'Dopo 1 ora',
        '1_day' => 'Dopo 1 giorno',
        'livescan' => 'Livescan codice QR',
        'upload' => 'Caricamento del codice QR',
        'advanced_form' => 'Modulo avanzato',
    ],

];