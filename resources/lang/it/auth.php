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
   
    // Laravel
    'failed' => 'Credenziali non valide.',
    'password' => 'La password fornita non è corretta.',
    'throttle' => 'Troppi tentativi di accesso. Riprova tra :seconds secondi.',

    // 2FAuth
    'sign_out' => 'Disconnettiti',
    'sign_in' => 'Accedi',
    'sign_in_using' => 'Accedi con',
    'or_continue_with' => 'You can also continue with:',
    'sign_in_using_security_device' => 'Accedi tramite un dispositivo di sicurezza',
    'login_and_password' => 'nome utente e password',
    'register' => 'Registrati',
    'welcome_to_2fauth' => 'Benvenuto su 2FAuth',
    'autolock_triggered' => 'Blocco automatico attivato',
    'autolock_triggered_punchline' => 'Blocco automatico attivato, sei stato disconnesso',
    'already_authenticated' => 'Già autenticato',
    'authentication' => 'Autenticazione',
    'maybe_later' => 'Magari più tardi',
    'user_account_controlled_by_proxy' => 'Account utente reso disponibile da un proxy di autenticazione.<br />Gestisci l\'account a livello di proxy.',
    'auth_handled_by_proxy' => 'Autenticazione gestita da un proxy inverso, le impostazioni sottostanti sono disabilitate.<br />Gestisci l\'autenticazione a livello di proxy.',
    'confirm' => [
        'logout' => 'Sei sicuro di volerti disconnettere?',
        'revoke_device' => 'Sei sicuro di voler eliminare questo dispositivo?',
        'delete_account' => 'Sei sicuro di voler eliminare il tuo account?',
    ],
    'webauthn' => [
        'security_device' => 'un dispositivo di sicurezza',
        'security_devices' => 'Dispositivi di sicurezza',
        'security_devices_legend' => 'Dispositivi di autenticazione che puoi usare per accedere a 2FAuth, come le chiavi di sicurezza (come Yubikey) o gli smartphone con funzionalità biometriche (es. Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'Puoi migliorare la sicurezza del tuo account 2FAuth abilitando l\'autenticazione WebAuthn.<br /><br />
            WebAuthn consente di utilizzare dispositivi fidati (come Yubikey o smartphone con funzionalità biometriche) per accedere in modo rapido e sicuro.',
        'use_security_device_to_sign_in' => 'Preparati ad autenticarti usando uno dei dispositivi di sicurezza. Collega la tua chiave, rimuovi la mascherina o i guanti, ecc.',
        'lost_your_device' => 'Hai perso il tuo dispositivo?',
        'recover_your_account' => 'Recupera il tuo account',
        'account_recovery' => 'Recupero dell\'account',
        'recovery_punchline' => '2FAuth ti invierà un link di recupero a questo indirizzo email. Clicca sul link nell\'email ricevuta e segui le istruzioni.<br /><br />Assicurati di aprire l\'email su un dispositivo fidato.',
        'send_recovery_link' => 'Invia link di recupero password',
        'account_recovery_email_sent' => 'Email di recupero account inviata!',
        'disable_all_security_devices' => 'Disabilita tutti i dispositivi di sicurezza',
        'disable_all_security_devices_help' => 'Tutti i tuoi dispositivi di sicurezza saranno rimossi. Usa questa opzione se ne hai perso uno o se la sua sicurezza è stata compromessa.',
        'register_a_new_device' => 'Aggiungi dispositivo',
        'register_a_device' => 'Registra un dispositivo',
        'device_successfully_registered' => 'Dispositivo registrato con successo',
        'device_revoked' => 'Dispositivo revocato con successo',
        'revoking_a_device_is_permanent' => 'Revocare un dispositivo è permanente',
        'recover_account_instructions' => 'Per recuperare il tuo account, 2FAuth ripristina alcune impostazioni Webauthn in modo che tu possa essere in grado di accedere utilizzando la tua email e password.',
        'invalid_recovery_token' => 'Token di recupero non valido',
        'webauthn_login_disabled' => 'Login Webauthn disabilitato',
        'invalid_reset_token' => 'Questo token di reset non è valido.',
        'rename_device' => 'Rinomina dispositivo',
        'my_device' => 'Il mio dispositivo',
        'unknown_device' => 'Dispositivo sconosciuto',
        'use_webauthn_only' => [
            'label' => 'Usa solo WebAuthn',
            'help' => 'Rendi WebAuthn l\'unico metodo autorizzato per accedere al tuo account 2FAuth. Questa è la configurazione consigliata per sfruttare la sicurezza potenziata di WebAuthn.<br /><br />
                In caso di perdita del dispositivo, sarai in grado di recuperare il tuo account ripristinando questa opzione e accedendo utilizzando la tua email e password.<br /><br />
                Attenzione! Il modulo Email e Password rimane disponibile nonostante questa opzione sia abilitata, ma restituirà sempre una risposta \'Autenticazione non riuscita\'.'
        ],
        'need_a_security_device_to_enable_options' => 'Imposta almeno un dispositivo per abilitare le seguenti opzioni',
        'options' => 'Opzioni',
    ],
    'forms' => [
        'name' => 'Nome',
        'login' => 'Login',
        'webauthn_login' => 'WebAuthn login',
        'email' => 'Email',
        'password' => 'Password',
        'reveal_password' => 'Mostra password',
        'hide_password' => 'Nascondi password',
        'confirm_password' => 'Conferma password',
        'new_password' => 'Nuova password',
        'confirm_new_password' => 'Conferma nuova password',
        'dont_have_account_yet' => 'Non hai ancora un account?',
        'already_register' => 'Sei già registrato?',
        'authentication_failed' => 'Autenticazione fallita',
        'forgot_your_password' => 'Hai dimenticato la password?',
        'request_password_reset' => 'Reimpostala',
        'reset_your_password' => 'Reimposta la password',
        'reset_password' => 'Ripristina password',
        'disabled_in_demo' => 'Funzione disabilitata in modalità demo',
        'new_password' => 'New password',
        'current_password' => [
            'label' => 'Password attuale',
            'help' => 'Inserisci la tua password attuale per confermare che sei tu'
        ],
        'change_password' => 'Cambia password',
        'send_password_reset_link' => 'Invia link per reset password',
        'password_successfully_reset' => 'Password successfully reset',
        'edit_account' => 'Modifica account',
        'profile_saved' => 'Profilo aggiornato con successo!',
        'welcome_to_demo_app_use_those_credentials' => 'Benvenuto nella demo di 2Fauth.<br><br>Puoi connetterti utilizzando l\'indirizzo email <strong>demo@2fauth.app</strong> e la password <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Benvenuto nell\'istanza test di 2Fauth. <br><br>Utilizza l\'indirizzo email <strong>testing@2fauth.app</strong> e la password <strong>password</strong>',
        'register_punchline' => 'Benvenuto su <b>2FAuth</b>.<br/>Hai bisogno di un account per proseguire, per favore creane uno.',
        'reset_punchline' => '2Fauth ha iniviato il link per il reset password al tuo indirizzo. Clicca sul link ricevuto nell\'email per impostare una nuova password.',
        'name_this_device' => 'Nome di questo dispositivo',
        'delete_account' => 'Elimina account',
        'delete_your_account' => 'Elimina il tuo account',
        'delete_your_account_and_reset_all_data' => 'Il tuo account utente verrà eliminato insieme a tutti i dati di 2FA. Non puoi tornare indietro.',
        'reset_your_password_to_delete_your_account' => 'Se hai sempre usato SSO per accedere, disconnettiti quindi utilizza la funzione di reset della password per ottenere una password in modo da poter compilare questo modulo.',
        'deleting_2fauth_account_does_not_impact_provider' => 'L\'eliminazione del tuo account 2FAuth non influenza in alcun modo il tuo account SSO esterno.',
        'user_account_successfully_deleted' => 'Account utente eliminato correttamente',
        'has_lower_case' => 'Contiene minuscole',
        'has_upper_case' => 'Contiene maiuscole',
        'has_special_char' => 'Contiene caratteri speciali',
        'has_number' => 'Contiene numeri',
        'is_long_enough' => '8 caratteri minimo',
        'mandatory_rules' => 'Richiesto',
        'optional_rules_you_should_follow' => 'Raccomandato (fortemente)',
        'caps_lock_is_on' => 'Il blocco maiuscole è attivo',
    ],

];
