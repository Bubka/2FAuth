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

    'admin' => 'Beheerder',
    'app_setup' => 'App instellingen',
    'auth' => 'Auth',
    'registrations' => 'Registraties',
    'users' => 'Gebruikers',
    'users_legend' => 'Beheer gebruikers die geregistreerd zijn op uw instantie of maak nieuwe.',
    'admin_settings' => 'Beheerders instellingen',
    'create_new_user' => 'Een gebruiker aanmaken',
    'new_user' => 'Nieuwe gebruiker',
    'search_user_placeholder' => 'Gebruikersnaam, e-mail...',
    'quick_filters_colons' => 'Snelle filters:',
    'user_created' => 'gebruiker succesvol aangemaakt',
    'confirm' => [
        'delete_user' => 'Weet je zeker dat je deze gebruiker wilt verwijderen? Er is geen weg terug.',
        'request_password_reset' => 'Weet je zeker dat je het wachtwoord van deze gebruiker opnieuw wilt instellen?',
        'purge_password_reset_request' => 'Weet je zeker dat je de vorige aanvraag wilt intrekken?',
        'delete_account' => 'Weet je zeker dat je deze gebruiker wilt verwijderen?',
        'edit_own_account' => 'Dit is je eigen account. Weet je het zeker?',
        'change_admin_role' => 'Dit zal ernstige gevolgen hebben voor de rechten van deze gebruiker. Weet je het zeker?',
        'demote_own_account' => 'Je zult niet langer beheerder zijn. Weet je het zeker?'
    ],
    'logs' => 'Logboek',
    'administration_legend' => 'De volgende instellingen zijn globaal en gelden voor alle gebruikers.',
    'user_management' => 'Gebruikersbeheer',
    'oauth_provider' => 'OAuth provider',
    'account_bound_to_x_via_oauth' => 'Dit account is gekoppeld aan een :provider account via OAuth',
    'last_seen_on_date' => 'Laatst gezien :date',
    'registered_on_date' => 'Geregistreerd: :date',
    'updated_on_date' => 'Bijgewerkt :date',
    'access' => 'Toegang',
    'password_requested_on_t' => 'Er bestaat een wachtwoord reset verzoek voor deze gebruiker (verzoek verzonden op :datetime), dit betekent dat de gebruiker zijn wachtwoord nog niet heeft gewijzigd, maar dat de link die hij heeft ontvangen nog steeds geldig is. Dit kan een verzoek zijn van de gebruiker zelf of van een beheerder.',
    'password_request_expired' => 'Er bestaat een wachtwoord reset verzoek voor deze gebruiker (verzoek verzonden op: datetime), dit betekent dat de gebruiker zijn wachtwoord nog niet heeft gewijzigd, maar dat de link die hij heeft ontvangen nog steeds geldig is. Dit kan een verzoek zijn van de gebruiker zelf of van een beheerder.',
    'resend_email' => 'E-mail opnieuw versturen',
    'resend_email_title' => 'Stuur een wachtwoord reset e-mail opnieuw naar de gebruiker',
    'resend_email_help' => 'Gebruik <b>E-mail</b> om een nieuw wachtwoord reset e-mail te sturen naar de gebruiker zodat hij een nieuw wachtwoord kan instellen. Dit laat het huidige wachtwoord zoals het is en elk eerdere verzoek wordt ingetrokken.',
    'reset_password' => 'Wachtwoord herstellen',
    'reset_password_help' => 'Gebruik <b>Reset wachtwoord</b> om een nieuw wachtwoord te forceren (dit zal een tijdelijk wachtwoord instellen) voordat u een wachtwoord reset e-mail stuurt naar de gebruiker zodat deze een nieuw wachtwoord kan instellen. Een eerdere aanvraag wordt ingetrokken.',
    'reset_password_title' => 'Herstel het wachtwoord van de gebruiker',
    'password_successfully_reset' => 'Wachtwoord succesvol hersteld',
    'user_has_x_active_pat' => ':count actieve token(s)',
    'user_has_x_security_devices' => ':count beveiligingsapparaten (passkeys)',
    'revoke_all_pat_for_user' => 'Alle tokens van gebruiker intrekken',
    'revoke_all_devices_for_user' => 'Alle beveiligingsapparaten van gebruikers intrekken',
    'danger_zone' => 'Gevarenzone',
    'delete_this_user_legend' => 'De gebruikersaccount zal worden verwijderd evenals alle 2FA-gegevens.',
    'this_is_not_soft_delete' => 'Dit is geen zachte verwijdering, er is geen weg terug.',
    'delete_this_user' => 'Deze gebruiker verwijderen',
    'user_role_updated' => 'Gebruikersrol bijgewerkt',
    'pats_succesfully_revoked' => 'PAT\'s van gebruiker succesvol ingetrokken',
    'security_devices_succesfully_revoked' => 'Toegangsapparaten van gebruiker succesvol ingetrokken',
    'variables' => 'Variabelen',
    'cache_cleared' => 'Cache geleegd',
    'cache_optimized' => 'Cache geoptimaliseerd',
    'check_now' => 'Controleer nu',
    'view_on_github' => 'Bekijk op GitHub',
    'x_is_available' => ':version is beschikbaar',
    'successful_login_on' => 'Succesvolle aanmelding op <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => 'Succesvol uitgelogd op <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => 'Mislukte aanmelding op <span class="light-or-darker">:login_at</span>',
    'viewed_on' => 'Bekeken op <span class="light-or-darker">:login_at</span>',
    'last_accesses' => 'Laatst geopend',
    'see_full_log' => 'Bekijk volledige log',
    'browser_on_platform' => ':browser op :platform',
    'access_log_has_more_entries' => 'Het toegangslogboek bevat meer vermeldingen.',
    'access_log_legend_for_user' => 'Volledige toegangslog voor gebruiker :username',
    'show_last_month_log' => 'Toon items van de laatste maand',
    'show_three_months_log' => 'Toon items van de laatste 3 maanden',
    'show_six_months_log' => 'Toon items van de laatste 6 maanden',
    'show_one_year_log' => 'Toon items van het afgelopen jaar',
    'sort_by_date_asc' => 'Toon minst recent eerst',
    'sort_by_date_desc' => 'Toon meest recente eerst',
    'single_sign_on' => 'Single Sign-On (SSO)',
    'forms' => [
        'use_encryption' => [
            'label' => 'Bescherm gevoelige gegevens',
            'help' => 'Gevoelige gegevens, de 2FA-geheimen en e-mails, worden versleuteld in de database. Zorg ervoor dat u een back-up maakt van de APP_KEY waarde van uw . nv bestand (of het hele bestand) wordt gebruikt als sleutelencryptie. Er is geen manier om gecodeerde data te decyperen zonder deze sleutel.',
        ],
        'restrict_registration' => [
            'label' => 'Registratie beperken',
            'help' => 'Maak registratie alleen beschikbaar voor een beperkt aantal e-mailadressen. Beide regels kunnen tegelijkertijd worden gebruikt. Dit heeft geen effect op de registratie via SSO.',
        ],
        'restrict_list' => [
            'label' => 'Filter lijst',
            'help' => 'E-mails in deze lijst zijn toegestaan om te registreren. Scheid adressen met een pijp ("~")',
        ],
        'restrict_rule' => [
            'label' => 'Regels filteren',
            'help' => 'E-mails die overeenkomen met deze reguliere expressie zijn toegestaan om te registreren',
        ],
        'disable_registration' => [
            'label' => 'Schakel registratie uit',
            'help' => 'Voorkom de registratie van nieuwe gebruikers. Tenzij hieronder overschreven (zie hieronder), heeft dit invloed op SSO, zodat nieuwe gebruikers niet kunnen inloggen via SSO',
        ],
        'enable_sso' => [
            'label' => 'Enable SSO',
            'help' => 'Sta bezoekers toe om zich te verifiëren met behulp van een extern ID via het Single Sign-On schema',
        ],
        'use_sso_only' => [
            'label' => 'Use SSO only',
            'help' => 'Make SSO the only available method to log in to 2FAuth. Password login and Webauthn are then disabled for regular users. Administrators are not affected by this restriction.',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'SSO-registratie ingeschakeld houden',
            'help' => 'Sta nieuwe gebruikers toe om voor de eerste keer via SSO in te loggen terwijl registratie is uitgeschakeld',
        ],
        'is_admin' => [
            'label' => 'Is beheerder',
            'help' => 'Geef beheerder rechten aan gebruiker. Beheerders hebben rechten om de hele app te beheren, o.a. Instellingen en andere gebruikers, maar kunnen geen wachtwoord genereren voor een 2FA waarvan ze geen eigenaar zijn.'
        ],
        'test_email' => [
            'label' => 'E-mail configuratie test',
            'help' => 'Stuur een test e-mail om de e-mailconfiguratie van uw instantie te beheren. Het is belangrijk om een werkende configuratie te hebben, anders kunnen gebruikers geen nieuw wachtwoord aanvragen.',
            'email_will_be_send_to_x' => 'De e-mail zal worden verzonden naar <span class="is-family-code has-text-info">:email</span>',
        ],
        'health_endpoint' => [
            'label' => 'Health endpoint',
            'help' => 'URL you can visit to check the health of this 2FAuth instance. This URL can be used to set up a Docker HEALTHCHECK or a Kubernetes HTTPS Liveness probe.',
        ],
        'cache_management' => [
            'label' => 'Cachebeheer',
            'help' => 'Soms moet de cache worden geleegd, bijvoorbeeld na een wijziging in omgevingsvariabelen of een update. Je kunt dit hier doen.',
        ]
    ],

];