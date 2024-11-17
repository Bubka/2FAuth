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
    'failed' => 'Brugernavn eller password findes ikke.',
    'password' => 'Forkert password.',
    'throttle' => 'For mange forkerte indtastninger. Prøv igen om :seconds sekunder.',

    // 2FAuth
    'sign_out' => 'Log af',
    'sign_in' => 'Log på',
    'sign_in_using' => 'Log ind med',
    'if_administrator' => 'Administrator?',
    'sign_in_here' => 'Du kan looge ind uden SSO',
    'or_continue_with' => 'Du kan fortsætte med:',
    'password_login_and_webauthn_are_disabled' => 'Password og WebAuthN er deaktiveret.',
    'sign_in_using_sso' => 'Vælg din SSO udbyder for at logge ind:',
    'no_provider' => 'Ingen udbyder',
    'no_sso_provider_or_provider_is_missing' => 'Mangler udbyderen?',
    'see_how_to_enable_sso' => 'Se hvordan man aktiverer en udbyder',
    'sign_in_using_security_device' => 'Log på med en sikker enhed',
    'login_and_password' => 'Login og password',
    'register' => 'Opret',
    'welcome_to_2fauth' => 'Velkommen til 2FAuth',
    'autolock_triggered' => 'Auto-lås udført',
    'autolock_triggered_punchline' => 'Auto-lås udført, du er blevet logget ud',
    'already_authenticated' => 'Allerede autoriseret, log venligst ud først',
    'authentication' => 'Autorisation',
    'maybe_later' => 'Måske senere',
    'user_account_controlled_by_proxy' => 'Brugerkonto gjort tilgængelig af en godkendelsesproxy.<br />Administrer kontoen på proxy-niveau.',
    'auth_handled_by_proxy' => 'Godkendelse håndteret af en omvendt proxy, indstillingerne herunder er deaktiveret.<br />Administrer godkendelse på proxy niveau.',
    'sso_only_x_settings_are_disabled' => 'Authentication er begrænset til kun SSO, :auth_method er deaktiveret',
    'confirm' => [
        'logout' => 'Er du sikker på at du vil logge af?',
        'revoke_device' => 'Er du sikker på, at du vil tilbagekalde denne enhed?',
        'delete_account' => 'Er du helt sikker på, at du vil slette din konto?',
    ],
    'webauthn' => [
        'security_device' => 'en sikkerhedsenhed',
        'security_devices' => 'Sikkerhedsenheder',
        'security_devices_legend' => 'Autentificeringsenheder, du kan bruge til at logge på 2FAuth, såsom sikkerhedstaster (dvs. Yubikey) eller smartphones med biometriske funktioner (dvs. Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'Du kan forbedre sikkerheden på din 2FAuth konto ved at aktivere WebAuthn godkendelse.<br /><br />
            WebAuthn giver dig mulighed for at bruge betroede enheder (som Yubikeys eller smartphones med biometriske evner) til at logge ind hurtigt og mere sikkert.',
        'use_security_device_to_sign_in' => 'Gør dig klar til at godkende ved hjælp af (en af) dine sikkerhedsenheder. Tilslut din nøgle i, fjern ansigtsmaske eller handsker, osv.',
        'lost_your_device' => 'Har du mistet din enhed?',
        'recover_your_account' => 'Gendan din konto',
        'account_recovery' => 'Kontogendannelse',
        'recovery_punchline' => '2FAuth vil sende et link til genoprettelse af adgang via denne e-mail adresse. Tryk på linket i mailen og følg instruktionerne. <br /><br />Åben kun linket på din egen enhed.',
        'send_recovery_link' => 'Send gendannelseslink',
        'account_recovery_email_sent' => 'Mail til kontogendannelse afsendt!',
        'disable_all_security_devices' => 'Disable all security devices',
        'disable_all_security_devices_help' => 'All your security devices will be revoked. Use this option if you have lost one or its security has been compromised.',
        'register_a_new_device' => 'Registrer en ny enhed',
        'register_a_device' => 'Registrer en enhed',
        'device_successfully_registered' => 'Enhed blev registreret',
        'device_revoked' => 'Enheden blev tilbagekaldt',
        'revoking_a_device_is_permanent' => 'Tilbagekaldelse af en enhed er permanent',
        'recover_account_instructions' => 'For at gendanne din konto, nulstiller 2FAuth nogle Webauthn indstillinger, så du vil være i stand til at logge ind ved hjælp af din e-mail og adgangskode.',
        'invalid_recovery_token' => 'Ugyldig recovery token',
        'webauthn_login_disabled' => 'Webauthn er deaktiveret',
        'invalid_reset_token' => 'Denne nulstillingstoken er ugyldig.',
        'rename_device' => 'Omdøb enhed',
        'my_device' => 'Min enhed',
        'unknown_device' => 'Unknown device',
        'use_webauthn_only' => [
            'label' => 'Brug kun WebAuthn',
            'help' => 'Gør WebAuthn til den eneste autoriserede metode til at logge ind på din 2FAuth konto. Dette er den anbefalede opsætning for at drage fordel af WebAuthn forbedret sikkerhed.<br /><br />
                I tilfælde af tab af enhed du vil være i stand til at gendanne din konto ved at nulstille denne indstilling og logge på med din e-mail og adgangskode.<br /><br />
                Attention! E- mail- og adgangskodeformularen forbliver tilgængelig på trods af at dette er aktiveret, men den vil altid returnere et \'Godkendelse\' svar.'
        ],
        'need_a_security_device_to_enable_options' => 'Angiv mindst én enhed for at aktivere følgende indstillinger',
        'options' => 'Indstillinger',
    ],
    'forms' => [
        'name' => 'Navn',
        'login' => 'Login',
        'webauthn_login' => 'WebAuthn login',
        'sso_login' => 'SSO-login',
        'email' => 'E-mail',
        'password' => 'Adgangskode',
        'reveal_password' => 'Vis adgangskode',
        'hide_password' => 'Skjul adgangskode',
        'confirm_password' => 'Bekræft adgangskode',
        'new_password' => 'Ny adgangskode',
        'confirm_new_password' => 'Bekræft ny adgangskode',
        'dont_have_account_yet' => 'Har du ikke din konto endnu?',
        'already_register' => 'Allerede registreret?',
        'authentication_failed' => 'Godkendelse mislykkedes',
        'forgot_your_password' => 'Glemt din adgangskode?',
        'request_password_reset' => 'Nulstil det',
        'reset_your_password' => 'Nulstil din adgangskode',
        'reset_password' => 'Nulstil adgangskode',
        'disabled_in_demo' => 'Funktionen er deaktiveret i demotilstand',
        'sso_only_form_restricted_to_admin' => 'Almindelige brugere skal logge ind med SSO. Andre metoder er kun for administratorer.',
        'new_password' => 'Ny adgangskode',
        'current_password' => [
            'label' => 'Nuværende adgangskode',
            'help' => 'Udfyld din nuværende adgangskode for at bekræfte, at det er dig'
        ],
        'change_password' => 'Skift adgangskode',
        'send_password_reset_link' => 'Send link til nulstilling af adgangskode',
        'password_successfully_reset' => 'Adgangskoden blev ændret',
        'edit_account' => 'Rediger konto',
        'profile_saved' => 'Profilen blev opdateret.',
        'welcome_to_demo_app_use_those_credentials' => 'Velkommen til 2FAuth demo.<br><br>Du kan oprette forbindelse ved hjælp af e-mailadressen <strong>demo@2fauth.app</strong> og adgangskoden <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Velkommen til 2FAuth testinstansen.<br><br>Brug e-mail adresse <strong>testing@2fauth.app</strong> og adgangskode <strong>adgangskode</strong>',
        'register_punchline' => 'Velkommen til <b>2FAuth</b>.<br/>Du har brug for en konto for at gå videre, registrer dig selv.',
        'reset_punchline' => '2FAuth vil sende dig et link til nulstilling af adgangskode til denne adresse. Klik på linket i den modtagne e-mail for at angive en ny adgangskode.',
        'name_this_device' => 'Navngiv denne enhed',
        'delete_account' => 'Slet konto',
        'delete_your_account' => 'Slet din konto',
        'delete_your_account_and_reset_all_data' => 'Din brugerkonto vil blive slettet såvel som alle dine 2FA-data. Der er ingen vej tilbage.',
        'reset_your_password_to_delete_your_account' => 'Hvis du altid har brugt SSO til at logge på, log ud og brug derefter funktionen nulstil adgangskode til at få en adgangskode, så du kan udfylde denne formular.',
        'deleting_2fauth_account_does_not_impact_provider' => 'Sletning af din 2FAuth konto har ingen indvirkning på din eksterne SSO-konto.',
        'user_account_successfully_deleted' => 'Brugerkonto blev slettet',
        'has_lower_case' => 'Har små bogstaver',
        'has_upper_case' => 'Har store bogstaver',
        'has_special_char' => 'Har specialtegn',
        'has_number' => 'Har tal',
        'is_long_enough' => 'min. 8 karakterer',
        'mandatory_rules' => 'Obligatorisk',
        'optional_rules_you_should_follow' => 'Anbefalet (højt)',
        'caps_lock_is_on' => 'Caps lock er tændt',
    ],
    'sso_providers' => [
        'unknown' => 'ukendt',
        'github' => 'Github',
        'openid' => 'OpenID'
    ]
];
