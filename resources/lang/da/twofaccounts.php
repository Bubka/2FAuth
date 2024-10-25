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

    'service' => 'Tjeneste',
    'account' => 'Konto',
    'icon' => 'Ikon',
    'icon_to_illustrate_the_account' => 'Ikon som illustrerer kontoen',
    'remove_icon' => 'Fjern ikon',
    'no_account_here' => 'Ingen 2FA her!',
    'add_first_account' => 'Vælg en metode og tilføj din første konto',
    'use_full_form' => 'Eller brug manuel indtastning',
    'add_one' => 'Tilføj én',
    'show_qrcode' => 'Vis QR kode',
    'no_service' => '- ingen tjeneste -',
    'account_created' => 'Konto oprettet',
    'account_updated' => 'Kontor opdateret',
    'accounts_deleted' => 'Konto(i) slettet',
    'accounts_moved' => 'Konto(i) flyttet',
    'export_selected_to_json' => 'Download a json export of selected accounts',
    'reveal' => 'afslør',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'John DOE',
        ],
        'new_account' => 'Ny konto',
        'edit_account' => 'Rediger konto',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'Scan QR kode',
        'upload_qrcode' => 'Upload QR kode',
        'use_advanced_form' => 'Brug avanceret form',
        'prefill_using_qrcode' => 'Udfyld via en QR kode',
        'use_qrcode' => [
            'val' => 'Brug en qrkode',
            'title' => 'Brug en QR-kode til at udfylde formularen automagisk',
        ],
        'unlock' => [
            'val' => 'Lås op',
            'title' => 'Unlock it (at your own risk)',
        ],
        'lock' => [
            'val' => 'Lås',
            'title' => 'Lock it',
        ],
        'choose_image' => 'Upload',
        'i_m_lucky' => 'Try my luck',
        'i_m_lucky_legend' => 'The "Try my luck" button try to get the official icon of the given service. Enter actual service name without ".xyz" extension and try to avoid typo. (beta feature)',
        'test' => 'Test',
        'group' => [
            'label' => 'Gruppe',
            'help' => 'The group to which the account is to be assigned'
        ],
        'secret' => [
            'label' => 'Secret',
            'help' => 'Nøgle til generering af koder'
        ],
        'plain_text' => 'Plain text',
        'otp_type' => [
            'label' => 'Choose the type of <abbr title="One-Time Password">OTP</abbr> to create',
            'help' => 'Time-based OTP or HMAC-based OTP or Steam OTP'
        ],
        'digits' => [
            'label' => 'Cifre',
            'help' => 'Antal cifre i de genererede sikkerhedskoder'
        ],
        'algorithm' => [
            'label' => 'Algoritme',
            'help' => 'Den algoritme, der bruges til at sikre dine sikkerhedskoder'
        ],
        'period' => [
            'label' => 'Periode',
            'placeholder' => 'Standard er 30',
            'help' => 'Gyldighedsperioden for de genererede sikkerhedskoder i sekunder'
        ],
        'counter' => [
            'label' => 'Tæller',
            'placeholder' => 'Standard er 0',
            'help' => 'The initial counter value',
            'help_lock' => 'It is risky to edit the counter as you can desynchronize the account with the verification server of the service. Use the lock icon to enable modification, but only if you know for you are doing'
        ],
        'image' => [
            'label' => 'Image',
            'placeholder' => 'http://...',
            'help' => 'The url of an external image to use as the account icon'
        ],
        'options_help' => 'You can leave the following options blank if you don\'t know how to set them. The most commonly used values will be applied.',
        'alternative_methods' => 'Alternative metoder',
        'spaces_are_ignored' => 'Uønskede mellemrum vil automatisk blive fjernet'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Live scan can\'t start :(',
        'need_grant_permission' => [
            'reason' => '2FAuth does not have permission to access your camera',
            'solution' => 'You need to grant permission to use your device camera. If you already denied and your browser do not prompt you again, please refers to the browser documentation to find out how to grant permission.',
            'click_camera_icon' => 'It is usually done by clicking on a slashed camera icon in or next to the browser\'s address bar',
        ],
        'not_readable' => [
            'reason' => 'Kunne ikke indlæse scanner',
            'solution' => 'Er kameraet allerede i brug? Sørg for, at ingen anden app bruger dit kamera og prøv igen'
        ],
        'no_cam_on_device' => [
            'reason' => 'Intet kamera på denne enhed',
            'solution' => 'Maybe you forgot to plug in your webcam'
        ],
        'secured_context_required' => [
            'reason' => 'Secure context required',
            'solution' => 'HTTPS is required for live scan. If you run 2FAuth from your computer, do not use virtual host other than localhost'
        ],
        'https_required' => 'HTTPS required for camera streaming',
        'camera_not_suitable' => [
            'reason' => 'Installed cameras are not suitable',
            'solution' => 'Brug venligst en anden enhed/kamera'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Stream API is not supported in this browser',
            'solution' => 'Du skal bruge en moderne browser'
        ],
    ],
    'confirm' => [
        'delete' => 'Er du sikker på, at du vil slette denne konto?',
        'cancel' => 'Ændringer vil gå tabt. Er du sikker?',
        'discard' => 'Er du sikker på, at du vil kassere denne konto?',
        'discard_all' => 'Er du sikker på du vil kassere alle konti?',
        'discard_duplicates' => 'Er du sikker på, at du vil kassere alle dubletter?',
    ],
    'import' => [
        'import' => 'Import',
        'to_import' => 'Importer',
        'import_legend' => '2FAuth kan importere data fra forskellige 2FA apps.',
        'import_legend_afterpart' => 'Brug eksportfunktionen for disse apps til at få en migrationsressource som en QR-kode eller en JSON-fil og derefter indlæse den her.',
        'upload' => 'Upload',
        'scan' => 'Scan',
        'supported_formats_for_qrcode_upload' => 'Accepteret: jpg, jpeg, png, bmp, gif, svg eller webp',
        'supported_formats_for_file_upload' => 'Accepted: Plain text, json, 2fas',
        'expected_format_for_direct_input' => 'Expected: A list of otpauth URI, one by line',
        'supported_migration_formats' => 'Supported migration formats',
        'qr_code' => 'QR Kode',
        'text_file' => 'Tekst fil',
        'direct_input' => 'Direkte indtastning',
        'plain_text' => 'Simpel tekst',
        'parsing_data' => 'Fortolker data...',
        'issuer' => 'Udsteder',
        'imported' => 'Importeret',
        'failure' => 'Fejl',
        'x_valid_accounts_found' => ':count gyldige konti fundet',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'Følgende 2FA-konti blev fundet i migrationsressourcen. Indtil videre er ingen af dem blevet tilføjet til 2FAuth.',
        'use_buttons_to_save_or_discard' => 'Brug de tilgængelige knapper til permanent at gemme dem til din 2FA-samling eller kassere dem.',
        'import_all' => 'Importer alle',
        'import_this_account' => 'Importer denne konto',
        'discard_all' => 'Kassér alle',
        'discard_duplicates' => 'Kassér dubletter',
        'discard_this_account' => 'Kasser denne konto',
        'generate_a_test_password' => 'Generer et testkodeord',
        'possible_duplicate' => 'En konto med nøjagtig samme data findes allerede',
        'invalid_account' => '- invalid account -',
        'invalid_service' => '- invalid service -',
        'do_not_set_password_or_encryption' => 'Do NOT enable Password protection or Encryption when you export data from a 2FA app otherwise 2FAuth will not be able to decipher them.',
    ],

];