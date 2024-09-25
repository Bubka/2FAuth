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

    'service' => 'Service',
    'account' => 'Account',
    'icon' => 'Icoon',
    'icon_to_illustrate_the_account' => 'Icoon dat de account illustreert',
    'remove_icon' => 'Verwijder icoon',
    'no_account_here' => 'Geen 2FA hier!',
    'add_first_account' => 'Kies een methode en voeg je eerste account toe',
    'use_full_form' => 'Of gebruik het volledige formulier',
    'add_one' => 'Eén toevoegen',
    'show_qrcode' => 'Toon QR-code',
    'no_service' => '- geen service -',
    'account_created' => 'Account succesvol aangemaakt',
    'account_updated' => 'Account succesvol bijgewerkt',
    'accounts_deleted' => 'Account(s) succesvol verwijderd',
    'accounts_moved' => 'Account(s) succesvol verplaatst',
    'export_selected_to_json' => 'Download een json export van geselecteerde accounts',
    'reveal' => 'toon',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'John DOE',
        ],
        'new_account' => 'Nieuw account',
        'edit_account' => 'Account bewerken',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'Scan een QR code',
        'upload_qrcode' => 'Upload een QR code',
        'use_advanced_form' => 'Gebruik het geavanceerd formulier',
        'prefill_using_qrcode' => 'Prefill met behulp van een QR Code',
        'use_qrcode' => [
            'val' => 'Gebruik een qrcode',
            'title' => 'Gebruik een QR code om het formulier magisch in te vullen',
        ],
        'unlock' => [
            'val' => 'Ontgrendel',
            'title' => 'Ontgrendel het (op eigen risico)',
        ],
        'lock' => [
            'val' => 'Vergrendel',
            'title' => 'Vergrendel het',
        ],
        'choose_image' => 'Upload',
        'i_m_lucky' => 'Probeer mijn geluk',
        'i_m_lucky_legend' => 'De "Probeer mijn geluk" knop probeert het officiële icoon te krijgen van de gegeven service. Voer de werkelijke servicenaam in zonder ".xyz" extensie en probeer een typefout te vermijden. (bèta-functie)',
        'test' => 'Test',
        'group' => [
            'label' => 'Group',
            'help' => 'The group to which the account is to be assigned'
        ],
        'secret' => [
            'label' => 'Geheim',
            'help' => 'De sleutel die gebruikt wordt om uw beveiligingscodes te genereren'
        ],
        'plain_text' => 'Platte tekst',
        'otp_type' => [
            'label' => 'Kies het type <abbr title="One-Time Password">OTP</abbr> om te maken',
            'help' => 'Op tijd gebaseerde OTP of HMAC-gebaseerde OTP of Steam OTP'
        ],
        'digits' => [
            'label' => 'Cijfers',
            'help' => 'Het aantal cijfers van de gegenereerde beveiligingscodes'
        ],
        'algorithm' => [
            'label' => 'Algoritme',
            'help' => 'Het algoritme dat wordt gebruikt om uw beveiligingscodes te beveiligen'
        ],
        'period' => [
            'label' => 'Periode',
            'placeholder' => 'Standaardwaarde is 30',
            'help' => 'De geldigheidsduur van de gegenereerde beveiligingscodes in de tweede'
        ],
        'counter' => [
            'label' => 'Teller',
            'placeholder' => 'Standaardwaarde is 0',
            'help' => 'De initiële tellerwaarde',
            'help_lock' => 'Het is riskant om de teller te bewerken omdat je het account kunt desynchroniseren met de verificatieserver van de service. Gebruik het vergrendelingspictogram om wijziging in te schakelen, maar alleen als u weet dat u dit doet'
        ],
        'image' => [
            'label' => 'Afbeelding',
            'placeholder' => 'http://...',
            'help' => 'De url van een externe afbeelding om te gebruiken als account icoon'
        ],
        'options_help' => 'Je kunt de volgende opties leeg laten als je niet weet hoe ze in te stellen. De meest gebruikte waarden zullen worden toegepast.',
        'alternative_methods' => 'Alternatieve methodes',
        'spaces_are_ignored' => 'Ongewenste spaties worden automatisch verwijderd'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Live scan kan niet starten :(',
        'need_grant_permission' => [
            'reason' => '2FAuth heeft geen toegang tot je camera',
            'solution' => 'Je moet toestemming geven om je apparaat camera te gebruiken. Als u het al heeft geweigerd en uw browser u niet opnieuw vraagt verwijst u naar de browserdocumentatie om erachter te komen hoe u toestemming kunt verlenen.',
            'click_camera_icon' => 'Meestal wordt dit gedaan door te klikken op een schuin camerapictogram in of naast de adresbalk van de browser',
        ],
        'not_readable' => [
            'reason' => 'Laden scanner mislukt',
            'solution' => 'Is de camera al in gebruik? Zorg ervoor dat geen andere app je camera gebruikt en probeer het opnieuw'
        ],
        'no_cam_on_device' => [
            'reason' => 'Geen camera op dit apparaat',
            'solution' => 'Misschien ben je vergeten je webcam aan te sluiten'
        ],
        'secured_context_required' => [
            'reason' => 'Veilige context vereist',
            'solution' => 'HTTPS is vereist voor live scan. Als je 2FAuth vanaf je computer draait, gebruik dan geen andere virtuele host dan localhost'
        ],
        'https_required' => 'HTTPS vereist voor camera streaming',
        'camera_not_suitable' => [
            'reason' => 'Geïnstalleerde camera\'s zijn niet geschikt',
            'solution' => 'Gebruik a.u.b. een ander apparaat/camera'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Stream API wordt niet ondersteund in deze browser',
            'solution' => 'Je moet een moderne browser gebruiken'
        ],
    ],
    'confirm' => [
        'delete' => 'Weet je zeker dat je dit account wilt verwijderen?',
        'cancel' => 'Wijzigingen zullen verloren gaan. Weet je het zeker?',
        'discard' => 'Weet je zeker dat je dit account wilt verwijderen?',
        'discard_all' => 'Weet je zeker dat je alle accounts wilt verwijderen?',
        'discard_duplicates' => 'Weet je zeker dat je alle duplicaten wilt verwijderen?',
    ],
    'import' => [
        'import' => 'Importeren',
        'to_import' => 'Importeren',
        'import_legend' => '2FAuth kan gegevens importeren uit verschillende 2FA apps.',
        'import_legend_afterpart' => 'Gebruik de Exportfunctie van deze apps om een migratiebron zoals een QR code of een JSON-bestand te krijgen en het hier te laden.',
        'upload' => 'Upload',
        'scan' => 'Scan',
        'supported_formats_for_qrcode_upload' => 'Geaccepteerd: jpg, jpeg, png, bmp, gif, svg of webp',
        'supported_formats_for_file_upload' => 'Geaccepteerd: platte tekst, json, 2fas',
        'expected_format_for_direct_input' => 'Verwachte lijst van otpauth URI, een voor regel',
        'supported_migration_formats' => 'Ondersteunde migratie formaten',
        'qr_code' => 'QR code',
        'text_file' => 'Tekst bestand',
        'direct_input' => 'Directe invoer',
        'plain_text' => 'Platte tekst',
        'parsing_data' => 'Gegevens verwerken...',
        'issuer' => 'Verstrekker',
        'imported' => 'Geïmporteerd',
        'failure' => 'Mislukt',
        'x_valid_accounts_found' => ':count geldige accounts gevonden',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'De volgende 2FA-accounts werden gevonden in de migratie-bron. Tot nu toe zijn geen van deze aan 2FAuth toegevoegd.',
        'use_buttons_to_save_or_discard' => 'Gebruik de beschikbare knoppen om ze permanent op uw 2FA-collectie te slaan of ze te negeren.',
        'import_all' => 'Importeer alles',
        'import_this_account' => 'Importeer dit account',
        'discard_all' => 'Alles annuleren',
        'discard_duplicates' => 'Duplicaten verwijderen',
        'discard_this_account' => 'Dit account verwijderen',
        'generate_a_test_password' => 'Genereer een test wachtwoord',
        'possible_duplicate' => 'Een account met dezelfde gegevens bestaat al',
        'invalid_account' => '- ongeldig account -',
        'invalid_service' => '- ongeldige service -',
        'do_not_set_password_or_encryption' => 'Schakel wachtwoordbescherming of versleuteling NIET in als je gegevens van een 2FA-app exporteert, anders kunnen 2FAuth ze niet ontcijferen.',
    ],

];