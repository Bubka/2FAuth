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
    'failed' => 'Deze inloggegevens zijn niet bij ons bekend.',
    'password' => 'Het opgegeven wachtwoord is onjuist.',
    'throttle' => 'Te veel inlogpogingen. Probeer het opnieuw over :seconds seconden.',

    // 2FAuth
    'sign_out' => 'Uitloggen',
    'sign_in' => 'Inloggen',
    'sign_in_using' => 'Inloggen met',
    'if_administrator' => 'Administrator?',
    'sign_in_here' => 'You can sign without SSO',
    'or_continue_with' => 'Je kunt ook doorgaan met:',
    'password_login_and_webauthn_are_disabled' => 'Password login and WebAuthn are disabled.',
    'sign_in_using_sso' => 'Pick an SSO provider to sign in with:',
    'no_provider' => 'no provider',
    'no_sso_provider_or_provider_is_missing' => 'Provider is missing?',
    'see_how_to_enable_sso' => 'See how to enable a provider',
    'sign_in_using_security_device' => 'Log in met een beveiligingsapparaat',
    'login_and_password' => 'lnloggen & wachtwoord',
    'register' => 'Registreren',
    'welcome_to_2fauth' => 'Welkom bij 2FAuth',
    'autolock_triggered' => 'Automatisch vergrendelen geactiveerd',
    'autolock_triggered_punchline' => 'Automatisch vergrendelen geactiveerd, je bent uitgelogd',
    'already_authenticated' => 'Al geauthenticeerd, log eerst uit',
    'authentication' => 'Authenticatie',
    'maybe_later' => 'Misschien later',
    'user_account_controlled_by_proxy' => 'Gebruikersaccount beschikbaar gesteld door een authenticatieproxy.<br />Beheer het account op proxyniveau.',
    'auth_handled_by_proxy' => 'Verificatie die wordt afgehandeld door een reverse proxy, hieronder zijn uitgeschakeld.<br />Authenticatie beheren op proxyniveau.',
    'sso_only_x_settings_are_disabled' => 'Authentication is restricted to SSO only, :auth_method is disabled',
    'confirm' => [
        'logout' => 'Weet je zeker dat je wilt uitloggen?',
        'revoke_device' => 'Weet u zeker dat u dit apparaat wilt verwijderen?',
        'delete_account' => 'Weet je zeker dat je je account wilt verwijderen?',
    ],
    'webauthn' => [
        'security_device' => 'een beveiligingsapparaat',
        'security_devices' => 'Beveiligingsapparaten',
        'security_devices_legend' => 'Verificatieapparaten die u kunt gebruiken om in 2FAuth, zoals beveiligingstoetsen (bijv. e Yubikey) of smartphones met biometrische mogelijkheden (zoals Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'U kunt de beveiliging van uw 2FAuth-account verbeteren door WebAuthn authenticatie in te schakelen.<br /><br />
            WebAuthn stelt u in staat om snel en veiliger beveiligde apparaten (zoals Yubikeys of smartphones met biometrische mogelijkheden) te gebruiken.',
        'use_security_device_to_sign_in' => 'Maak je klaar om te verifiëren met (één van) je beveiligingsapparaten. Steek je beveiligingsapparaat in, verwijder gezicht masker of handschoenen etc.',
        'lost_your_device' => 'Je apparaat verloren?',
        'recover_your_account' => 'Herstel je account',
        'account_recovery' => 'Accountherstel',
        'recovery_punchline' => '2FAuth stuurt u een herstel-link naar dit e-mailadres. Klik op de link in de ontvangen e-mail en volg de instructies.<br /><br />Zorg ervoor dat u de e-mail opent op een apparaat dat u volledig bezit.',
        'send_recovery_link' => 'Herstellink verzenden',
        'account_recovery_email_sent' => 'Account herstel e-mail verzonden!',
        'disable_all_security_devices' => 'Alle beveiligingsapparaten uitschakelen',
        'disable_all_security_devices_help' => 'Al uw beveiligingsapparaten zullen worden ingetrokken. Gebruik deze optie als u één hebt verloren, of als de beveiliging is aangetast.',
        'register_a_new_device' => 'Registreer een nieuw apparaat',
        'register_a_device' => 'Apparaat registreren',
        'device_successfully_registered' => 'Apparaat succesvol geregistreerd',
        'device_revoked' => 'Apparaat succesvol ingetrokken',
        'revoking_a_device_is_permanent' => 'Een apparaat intrekken is permanent',
        'recover_account_instructions' => 'Om uw account te herstellen, stelt 2FAuth een aantal Webauthn instellingen opnieuw in, zodat u kunt inloggen met uw e-mailadres en wachtwoord.',
        'invalid_recovery_token' => 'Ongeldige herstelcode',
        'webauthn_login_disabled' => 'Webauthn inloggen uitgeschakeld',
        'invalid_reset_token' => 'Deze reset token is ongeldig.',
        'rename_device' => 'Apparaat hernoemen',
        'my_device' => 'Mijn apparaat',
        'unknown_device' => 'Onbekend apparaat',
        'use_webauthn_only' => [
            'label' => 'Gebruik alleen WebAuthn',
            'help' => 'Maak WebAuthn de enige geautoriseerde methode om in te loggen op uw 2FAuth account. Dit is de aanbevolen instelling om gebruik te maken van de verbeterde WebAuthn beveiliging.<br /><br />
                In geval van verlies van apparaat, u kunt uw account herstellen door deze optie te resetten en in te loggen met uw e-mailadres en wachtwoord.<br /><br />
                Opgelet! Het e-mail & wachtwoord formulier blijft beschikbaar ondanks deze optie ingeschakeld, maar het zal altijd een \'Authenticatie mislukt\' antwoord teruggeven.'
        ],
        'need_a_security_device_to_enable_options' => 'Stel ten minste één apparaat in om de volgende opties in te schakelen',
        'options' => 'Opties',
    ],
    'forms' => [
        'name' => 'Naam',
        'login' => 'Inloggen',
        'webauthn_login' => 'WebAuthn inloggen',
        'sso_login' => 'SSO login',
        'email' => 'E-mail',
        'password' => 'Wachtwoord',
        'reveal_password' => 'Toon wachtwoord',
        'hide_password' => 'Verberg wachtwoord',
        'confirm_password' => 'Bevestig wachtwoord',
        'new_password' => 'Nieuw wachtwoord',
        'confirm_new_password' => 'Bevestig nieuw wachtwoord',
        'dont_have_account_yet' => 'Heb je nog geen account?',
        'already_register' => 'Al geregistreerd?',
        'authentication_failed' => 'Authenticatie mislukt',
        'forgot_your_password' => 'Je wachtwoord vergeten?',
        'request_password_reset' => 'Herstellen',
        'reset_your_password' => 'Herstel je wachtwoord',
        'reset_password' => 'Wachtwoord herstellen',
        'disabled_in_demo' => 'Functie uitgeschakeld in Demo modus',
        'sso_only_form_restricted_to_admin' => 'Regular users must sign in with SSO. Other methods are for administrators only.',
        'new_password' => 'Nieuw wachtwoord',
        'current_password' => [
            'label' => 'Huidig wachtwoord',
            'help' => 'Vul je huidige wachtwoord in om te bevestigen dat jij het bent'
        ],
        'change_password' => 'Wijzig wachtwoord',
        'send_password_reset_link' => 'Wachtwoord herstel link sturen',
        'password_successfully_reset' => 'Wachtwoord succesvol hersteld',
        'edit_account' => 'Wijzig account',
        'profile_saved' => 'Profiel succesvol bijgewerkt!',
        'welcome_to_demo_app_use_those_credentials' => 'Welkom op de 2FAuth demo.<br><br>U kunt verbinden met het e-mailadres <strong>demo@2fauth.app</strong> en het wachtwoord <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Welkom bij de 2FAuth test instantie.<br><br>Gebruik e-mailadres <strong>testing@2fauth.app</strong> en wachtwoord <strong>wachtwoord</strong>',
        'register_punchline' => 'Welkom bij <b>2FAuth</b>.<br/>U heeft een account nodig om verder te gaan, registreer uzelf.',
        'reset_punchline' => '2FAuth zal u een wachtwoord reset link sturen naar dit adres. Klik op de link in de ontvangen e-mail om een nieuw wachtwoord in te stellen.',
        'name_this_device' => 'Naam van dit apparaat',
        'delete_account' => 'Account verwijderen',
        'delete_your_account' => 'Verwijder je account',
        'delete_your_account_and_reset_all_data' => 'Je gebruikers account wordt verwijderd, evenals al uw 2FA-gegevens. Er is geen weg terug.',
        'reset_your_password_to_delete_your_account' => 'Als je SSO altijd hebt gebruikt om in te loggen, log uit en gebruik de reset wachtwoord functie om een wachtwoord te krijgen zodat u dit formulier kunt invullen.',
        'deleting_2fauth_account_does_not_impact_provider' => 'Het verwijderen van je 2FAuth account heeft geen invloed op je externe SSO-account.',
        'user_account_successfully_deleted' => 'Gebruikersaccount succesvol verwijderd',
        'has_lower_case' => 'Heeft kleine letters',
        'has_upper_case' => 'Heeft hoofdletters',
        'has_special_char' => 'Heeft speciale teken',
        'has_number' => 'Heeft nummers',
        'is_long_enough' => 'Minimaal 8 karakters.',
        'mandatory_rules' => 'Verplicht',
        'optional_rules_you_should_follow' => 'Aanbevolen (hoog)',
        'caps_lock_is_on' => 'Caps lock is aan',
    ],
    'sso_providers' => [
        'unknown' => 'unknown',
        'github' => 'Github',
        'openid' => 'OpenID'
    ]
];
