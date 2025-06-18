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

    'settings' => 'Instellingen',
    'preferences' => 'Voorkeuren',
    'account' => 'Account',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Opties',
    'user_preferences' => 'Gebruikersvoorkeuren',
    'admin_settings' => 'Beheerdersinstellingen',
    'confirm' => [

    ],
    'you_are_administrator' => 'U bent een beheerder',
    'account_linked_to_sso_x_provider' => 'U bent ingelogd via SSO met behulp van uw :provider account. Uw gegevens kunnen hier niet worden gewijzigd, maar op :provider.',
    'general' => 'Algemeen',
    'security' => 'Beveiliging',
    'notifications' => 'Meldingen',
    'profile' => 'Profiel',
    'change_password' => 'Wachtwoord wijzigen',
    'personal_access_tokens' => 'Persoonlijke toegangstokens',
    'token_legend' => 'Persoonlijke toegangstokens staan elke app toe om te verifiëren via de 2Fauth API. U moet het toegangstoken opgeven als een Bearer token in de autorisatie-header van verzoeken om autorisatie apps.',
    'generate_new_token' => 'Nieuwe token genereren',
    'revoke' => 'Intrekken',
    'token_revoked' => 'Token succesvol ingetrokken',
    'revoking_a_token_is_permanent' => 'Het intrekken van een token is permanent',
    'confirm' => [
        'revoke' => 'Weet u zeker dat u deze token wilt intrekken?',
    ],
    'make_sure_copy_token' => 'Zorg ervoor dat je het persoonlijke toegangs-token nu kopieert of opschrijft. Je kunt het later niet meer zien!',
    'data_input' => 'Gegevensinvoer',
    'settings_managed_by_administrator' => 'Some settings are being managed by your administrator',
    'forms' => [
        'edit_settings' => 'Wijzig instellingen',
        'setting_saved' => 'Instellingen opgeslagen',
        'new_token' => 'Nieuwe token',
        'some_translation_are_missing' => 'Sommige vertalingen ontbreken met behulp van de voorkeurstaal van de browser?',
        'help_translate_2fauth' => 'Help 2FAuth te vertalen',
        'language' => [
            'label' => 'Taal',
            'help' => 'De taal die wordt gebruikt om de 2FAuth gebruikersinterface te vertalen. Benoemde talen zijn compleet, stel een van uw keuze in om de voorkeur van uw browser te overschrijven.'
        ],
        'timezone' => [
            'label' => 'Tijdzone',
            'help' => 'De tijdzone is van toepassing op alle datums en tijden die in de applicatie worden weergegeven'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated OTP as dot',
            'help' => 'Replace generated password characters with *** to ensure confidentiality. Does not affect the copy/paste feature'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured OTP',
            'help' => 'Laat de mogelijkheid om tijdelijk Dot-Obscured wachtwoorden te onthullen'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close OTP after copy',
            'help' => 'Klik op een gegenereerd wachtwoord om het automatisch te kopiëren op het scherm'
        ],
        'show_next_otp' => [
            'label' => 'Show next OTP',
            'help' => 'Preview the next password, i.e. the password that will replace the current password when it expires. Preferences set for the current OTP also apply to the next one (formatting, show as dot)'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close OTP',
            'help' => 'Automatisch het scherm-wachtwoord verbergen na een time-out. Dit voorkomt onnodige verzoeken voor nieuwe wachtwoorden als u de wachtwoordweergave vergeet te sluiten.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Zoekopdracht wissen bij kopiëren',
            'help' => 'Leeg het zoekvak rechts nadat een code is gekopieerd naar het klembord'
        ],
        'sort_case_sensitive' => [
            'label' => 'Soort hoofdlettergevoelig',
            'help' => 'Wanneer aangedrongen, forceer de sorteerfunctie om rekeningen op een hoofdgevoelige basis te sorteren'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copy OTP on display',
            'help' => 'Automatisch een gegenereerd wachtwoord kopiëren direct nadat het op het scherm verschijnt. Vanwege de beperkingen van browsers wordt alleen het eerste <abbr title="Time-based One-Time Password">TOTP</abbr> wachtwoord gekopieerd, niet de roterende wachtwoord'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Gebruik standaard QR code lezer',
            'help' => 'Als u problemen ondervindt bij het opnemen van QR-codes kunt u deze optie overschakelen naar een meer fundamentele maar betrouwbaardere QR code lezer'
        ],
        'display_mode' => [
            'label' => 'Weergavemodus',
            'help' => 'Kies of je accounts wilt weergeven als lijst of als raster'
        ],
        'password_format' => [
            'label' => 'Wachtwoord opmaak',
            'help' => 'Verander hoe de wachtwoorden worden weergegeven door cijfers te groeperen om leesbaarheid en geheugen te vergemakkelijken'
        ],
        'pair' => 'bij Paar',
        'pair_legend' => 'Groepeer cijfers twee door twee',
        'trio_legend' => 'Groepeer cijfers twee door twee',
        'half_legend' => 'Splits cijfers in twee gelijkwaardige groepen',
        'trio' => 'door drie',
        'half' => 'door de helft',
        'grid' => 'Raster',
        'list' => 'Lijst',
        'theme' => [
            'label' => 'Thema',
            'help' => 'Forceer een specifiek thema of pas het thema toe dat is gedefinieerd in uw systeem/browser voorkeuren'
        ],
        'light' => 'Licht',
        'dark' => 'Donker',
        'automatic' => 'Auto',
        'show_accounts_icons' => [
            'label' => 'Toon symbolen',
            'help' => 'Show account icons in the main view'
        ],
        'get_official_icons' => [
            'label' => 'Officiële iconen verkrijgen',
            'help' => '(Probeer om) Krijg het officiële icoon van de 2FA-uitgever bij het toevoegen van een account'
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
            'label' => 'Autom. vergrendelen',
            'help' => 'Log de gebruiker automatisch uit bij inactiviteit. Heeft geen effect wanneer authenticatie wordt afgehandeld door een proxy en er geen aangepaste logout-url is opgegeven.'
        ],
        'default_group' => [
            'label' => 'Standaard groep',
            'help' => 'De groep waaraan de nieuwe accounts zijn gekoppeld',
        ],
        'view_default_group_on_copy' => [
            'label' => 'Standaardgroep bij kopiëren weergeven',
            'help' => 'Altijd terugkeren naar de standaardgroep wanneer een OTP is gekopieerd',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Accounts automatisch opslaan',
            'help' => 'Nieuwe accounts worden automatisch geregistreerd na het scannen of uploaden van een QR-code, het is niet nodig op de knop Opslaan te klikken',
        ],
        'useDirectCapture' => [
            'label' => 'Directe invoer',
            'help' => 'Kies of u gevraagd wilt worden om een invoermodus te kiezen tussen de beschikbare of dat u direct de standaard invoermodus wilt gebruiken',
        ],
        'defaultCaptureMode' => [
            'label' => 'Standaard input mode',
            'help' => 'Standaard invoermodus wordt gebruikt wanneer de optie voor directe invoer ingeschakeld is',
        ],
        'remember_active_group' => [
            'label' => 'Groepfilter onthouden',
            'help' => 'Sla het laatste groepsfilter op en herstel het tijdens uw volgende bezoek',
        ],
        'otp_generation' => [
            'label' => 'Toon wachtwoord',
            'help' => 'Stel in hoe en wanneer <abbr title="One-Time Passwords">OTPs</abbr> worden weergegeven.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'Op een nieuw apparaat',
            'help' => 'Ontvang een e-mail wanneer een nieuw apparaat voor de eerste keer verbinding maakt met je 2FAuth account'
        ],
        'notify_on_failed_login' => [
            'label' => 'Bij mislukte aanmelding',
            'help' => 'Ontvang een e-mail telkens wanneer een poging om verbinding te maken met je 2FAuth account mislukt'
        ],
        'show_email_in_footer' => [
            'label' => 'Show email in footer',
            'help' => 'Display the logged-in user\'s email in the footer instead of direct navigation links. The links are then available in a menu behind a click/tap on the email address.'
        ],
        'otp_generation_on_request' => 'Na een klik/tik',
        'otp_generation_on_request_legend' => 'Alleen, in zijn eigen weergave',
        'otp_generation_on_request_title' => 'Klik op een account om een wachtwoord te krijgen in een speciale weergave',
        'otp_generation_on_home' => 'Constant',
        'otp_generation_on_home_legend' => 'Alles, op start',
        'otp_generation_on_home_title' => 'Alle wachtwoorden in de hoofdweergave weergeven, zonder iets te doen',
        'never' => 'Nooit',
        'on_otp_copy' => 'Bij beveiligingscode kopiëren',
        '1_minutes' => 'Na 1 minuut',
        '2_minutes' => 'Na 2 minuten',
        '5_minutes' => 'Na 5 minuten',
        '10_minutes' => 'Na 5 minuten',
        '15_minutes' => 'Na 15 minuten',
        '30_minutes' => 'Na 30 minuten',
        '1_hour' => 'Na 1 uur',
        '1_day' => 'Na 1 dag',
        'livescan' => 'QR code livescan',
        'upload' => 'QR code uploaden',
        'advanced_form' => 'Geavanceerd formulier',
    ],

];