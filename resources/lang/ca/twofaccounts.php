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

    'service' => 'Servei',
    'account' => 'Compte',
    'icon' => 'Icona',
    'icon_to_illustrate_the_account' => 'Icon that illustrates the account',
    'remove_icon' => 'Elimina icona',
    'no_account_here' => 'Sense 2FA!',
    'add_first_account' => 'Pick a method and add your first account',
    'use_full_form' => 'Or use the full form',
    'add_one' => 'Afegir un',
    'show_qrcode' => 'Mostra el codi QR',
    'no_service' => '- sense servei -',
    'account_created' => 'Compte creat correctament',
    'account_updated' => 'Compte actualitzat correctament',
    'accounts_deleted' => 'Compte(s) esborrat(s) correctament',
    'accounts_moved' => 'Compte(s) mogut(s) correctament',
    'export_selected_accounts' => 'Exportar comptes seleccionats',
    'twofauth_export_format' => 'Format 2FAuth',
    'twofauth_export_format_sub' => 'Exportar dades emprant l\'esquema json de 2FAuth',
    'twofauth_export_format_desc' => 'You should prefer this option if you need to create a backup that can be restored. This format takes care of the icons.',
    'twofauth_export_format_url' => 'The schema definition is described here:',
    'twofauth_export_schema' => '2FAuth export schema',
    'otpauth_export_format' => 'otpauth URIs',
    'otpauth_export_format_sub' => 'Export data as a list of otpauth URIs',
    'otpauth_export_format_desc' => 'otpauth URI is the most common format used to exchange 2FA data, for example in the form of a QR code when you enable 2FA on a web site. Select this if you want to switch from 2FAuth.',
    'reveal' => 'revela',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'John DOE',
        ],
        'new_account' => 'Nou compte',
        'edit_account' => 'Edita compte',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'Escaneja codi QR',
        'upload_qrcode' => 'Puja codi QR',
        'use_advanced_form' => 'Empra el formulari avançat',
        'prefill_using_qrcode' => 'Prefill using a QR Code',
        'use_qrcode' => [
            'val' => 'Empra un codi QR',
            'title' => 'Use a QR code to fill the form magically',
        ],
        'unlock' => [
            'val' => 'Desbloca',
            'title' => 'Unlock it (at your own risk)',
        ],
        'lock' => [
            'val' => 'Bloca',
            'title' => 'Tanca\'l',
        ],
        'choose_image' => 'Puja',
        'i_m_lucky' => 'Prova Sort',
        'i_m_lucky_legend' => 'The "Try my luck" button tries to get a standard icon from the selected icon collection. The simpler the Service field value, the more likely you are to get the expected icon: Do not append any extension (like ".com"), use the exact name of the service, avoid special chars.',
        'test' => 'Prova',
        'group' => [
            'label' => 'Grup',
            'help' => 'The group to which the account is to be assigned'
        ],
        'secret' => [
            'label' => 'Secret',
            'help' => 'The key used to generate your security codes'
        ],
        'plain_text' => 'Text pla',
        'otp_type' => [
            'label' => 'Choose the type of OTP to create',
            'help' => 'Time-based OTP or HMAC-based OTP or Steam OTP'
        ],
        'digits' => [
            'label' => 'Dígits',
            'help' => 'The number of digits of the generated security codes'
        ],
        'algorithm' => [
            'label' => 'Algoritme',
            'help' => 'The algorithm used to secure your security codes'
        ],
        'period' => [
            'label' => 'Període',
            'placeholder' => '30 per defecte',
            'help' => 'The period of validity of the generated security codes in second'
        ],
        'counter' => [
            'label' => 'Comptador',
            'placeholder' => '0 per defecte',
            'help' => 'The initial counter value',
            'help_lock' => 'It is risky to edit the counter as you can desynchronize the account with the verification server of the service. Use the lock icon to enable modification, but only if you know for you are doing'
        ],
        'image' => [
            'label' => 'Imatge',
            'placeholder' => 'http://...',
            'help' => 'The url of an external image to use as the account icon'
        ],
        'is_shared' => [
            'label' => 'Share this account with all users',
            'help' => 'When enabled, this account will be visible to all users in the system'
        ],
        'options_help' => 'You can leave the following options blank if you don\'t know how to set them. The most commonly used values will be applied.',
        'alternative_methods' => 'Mètodes alternatius',
        'spaces_are_ignored' => 'Unwanted spaces will be automatically removed'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Live scan can\'t start :(',
        'need_grant_permission' => [
            'reason' => '2FAuth does not have permission to access your camera',
            'solution' => 'You need to grant permission to use your device camera. If you already denied and your browser do not prompt you again, please refers to the browser documentation to find out how to grant permission.',
            'click_camera_icon' => 'It is usually done by clicking on a slashed camera icon in or next to the browser\'s address bar',
        ],
        'not_readable' => [
            'reason' => 'Fail to load scanner',
            'solution' => 'Is the camera already in use? Ensure that no other app use your camera and try again'
        ],
        'no_cam_on_device' => [
            'reason' => 'No camera on this device',
            'solution' => 'Maybe you forgot to plug in your webcam'
        ],
        'secured_context_required' => [
            'reason' => 'Secure context required',
            'solution' => 'HTTPS is required for live scan. If you run 2FAuth from your computer, do not use virtual host other than localhost'
        ],
        'https_required' => 'HTTPS required for camera streaming',
        'camera_not_suitable' => [
            'reason' => 'Installed cameras are not suitable',
            'solution' => 'Please use another device/camera'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Stream API is not supported in this browser',
            'solution' => 'You should use a modern browser'
        ],
    ],
    'confirm' => [
        'delete' => 'Are you sure you want to delete this account?',
        'cancel' => 'Changes will be lost. Are you sure?',
        'discard' => 'Are you sure you want to discard this account?',
        'discard_all' => 'Are you sure you want to discard all accounts?',
        'discard_duplicates' => 'Are you sure you want to discard all duplicates?',
    ],
    'import' => [
        'import' => 'Importa',
        'to_import' => 'Importa',
        'import_legend' => '2FAuth can import data from various 2FA apps.',
        'import_legend_afterpart' => 'Use the Export feature of these apps to get a migration resource like a QR code or a JSON file then load it here.',
        'upload' => 'Puja',
        'scan' => 'Escaneja',
        'supported_formats_for_qrcode_upload' => 'Accepted: jpg, jpeg, png, bmp, gif, svg, or webp',
        'supported_formats_for_file_upload' => 'Accepted: Plain text, json, 2fas',
        'expected_format_for_direct_input' => 'Expected: A list of otpauth URI, one by line',
        'supported_migration_formats' => 'Supported migration formats',
        'qr_code' => 'Codi QR',
        'text_file' => 'Arxiu de text',
        'direct_input' => 'Entrada directa',
        'plain_text' => 'Text pla',
        'parsing_data' => 'Analitzant dades...',
        'issuer' => 'Emisor',
        'imported' => 'Importat',
        'failure' => 'Fallida',
        'x_valid_accounts_found' => ':count valid accounts found',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'The following 2FA accounts were found in the migration resource. So far none of them have been added to 2FAuth.',
        'use_buttons_to_save_or_discard' => 'Use the available buttons to permanently save them to your 2FA collection or discard them.',
        'import_all' => 'Importa tot',
        'import_this_account' => 'Importa aquest compte',
        'discard_all' => 'Descarta tot',
        'discard_duplicates' => 'Desestima duplicats',
        'discard_this_account' => 'Descarta aquest compte',
        'generate_a_test_password' => 'Generar contrasenya de prova',
        'possible_duplicate' => 'Un compte amb les mateixes dades ja existeix',
        'invalid_account' => '- compte invàlid -',
        'invalid_service' => '- servei invàlid -',
        'do_not_set_password_or_encryption' => 'Do NOT enable Password protection or Encryption when you export data from a 2FA app otherwise 2FAuth will not be able to decipher them.',
    ],

];