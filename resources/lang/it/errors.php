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

    'resource_not_found' => 'Risorsa non trovata',
    'error_occured' => 'Si è verificato un errore:',
    'refresh' => 'Ricarica',
    'no_valid_otp' => 'Nessuna risorsa OTP valida in questo codice QR',
    'something_wrong_with_server' => 'Qualcosa non va con il tuo server',
    'Unable_to_decrypt_uri' => 'Impossibile decifrare uri',
    'not_a_supported_otp_type' => 'Questo formato OTP non è attualmente supportato',
    'cannot_create_otp_without_secret' => 'Impossibile creare un OTP senza un segreto',
    'data_of_qrcode_is_not_valid_URI' => 'I dati di questo codice QR non sono un valido OTP Auth URI. Il codice QR contiene:',
    'wrong_current_password' => 'Password corrente errata, nulla è cambiato',
    'error_during_encryption' => 'Cifratura non riuscita, il database rimane non protetto.',
    'error_during_decryption' => 'Decifratura non riuscita, il database è ancora protetto. Questo è causato principalmente da un problema di integrità dei dati crittografati per uno o più account.',
    'qrcode_cannot_be_read' => 'Questo codice QR è illeggibile',
    'too_many_ids' => 'troppi ID sono stati inclusi nel parametro di query, max 100 consentiti',
    'delete_user_setting_only' => 'Solo le impostazioni create dall\'utente possono essere eliminate',
    'indecipherable' => '*indecifrabile*',
    'cannot_decipher_secret' => 'Il segreto non può essere decifrato. Questo è causato principalmente da un set APP_KEY sbagliato nel . nv file di configurazione di 2Fauth o dati corrotti memorizzati nel database.',
    'https_required' => 'Contesto HTTPS necessario',
    'browser_does_not_support_webauthn' => 'Il tuo dispositivo non supporta webauthn. Riprova più tardi usando un browser più moderno',
    'aborted_by_user' => 'Annullato dall\'utente.',
    'security_device_already_registered' => 'Dispositivo già registrato',
    'not_allowed_operation' => 'Operazione non consentita',
    'no_authenticator_support_specified_algorithms' => 'Nessun autenticatore supporta gli algoritmi specificati',
    'authenticator_missing_discoverable_credential_support' => 'Supporto credenziali scopribili mancante per l\'autenticazione',
    'authenticator_missing_user_verification_support' => 'Authenticator missing user verification support',
    'unknown_error' => 'Errore sconosciuto',
    'security_error_check_rpid' => 'Security error<br/>Check your WEBAUTHN_ID env var',
    '2fauth_has_not_a_valid_domain' => '2FAuth\'s domain is not a valid domain',
    'user_id_not_between_1_64' => 'User ID was not between 1 and 64 chars',
    'no_entry_was_of_type_public_key' => 'No entry was of type "public-key"',
    'unsupported_with_reverseproxy' => 'Not applicable when using an auth proxy or SSO',
    'unsupported_with_sso_only' => 'This authentication method is for administrators only. Users must log in with SSO.',
    'user_deletion_failed' => 'User account deletion failed, no data have been deleted',
    'auth_proxy_failed' => 'Proxy authentication failed',
    'auth_proxy_failed_legend' => '2Fauth is configured to run behind an authentication proxy but your proxy does not return the expected header. Check your configuration and try again.',
    'invalid_x_migration' => 'Invalid or unreadable :appname data',
    'invalid_2fa_data' => 'Invalid 2FA data',
    'unsupported_migration' => 'Data do not match any supported format',
    'unsupported_otp_type' => 'Unsupported OTP type',
    'encrypted_migration' => 'Unreadable, the data seem encrypted',
    'no_icon_for_this_variant' => 'No icon available in this variant',
    'file_upload_failed' => 'File upload failed',
    'unauthorized' => 'Unauthorized',
    'unauthorized_legend' => 'You do not have permissions to view this resource or to perform this action',
    'cannot_delete_the_only_admin' => 'Cannot delete the only admin account',
    'cannot_demote_the_only_admin' => 'Cannot demote the only admin account',
    'error_during_data_fetching' => '💀 Something went wrong during data fetching',
    'check_failed_try_later' => 'Check failed, please retry later',
    'sso_disabled' => 'SSO is disabled',
    'sso_bad_provider_setup' => 'This SSO provider is not fully setup in your .env file',
    'sso_failed' => 'Authentication via SSO rejected',
    'sso_no_register' => 'Registrations are disabled',
    'sso_email_already_used' => 'A user account with the same email address already exists but it does not match your external account ID. Do not use SSO if you are already registered on 2FAuth with this email.',
    'account_managed_by_external_provider' => 'Account managed by an external provider',
    'data_cannot_be_refreshed_from_server' => 'Data cannot be refreshed from server',
    'no_pwd_reset_for_this_user_type' => 'Password reset unavailable for this user',
    'cannot_detect_qrcode_in_image' => 'Cannot detect a QR code in the image, try to crop the image',
    'cannot_decode_detected_qrcode' => 'Cannot decode detected QR code, try to crop or sharpen the image',
    'qrcode_has_invalid_checksum' => 'QR code has invalid checksum',
    'no_readable_qrcode' => 'No readable QR code',
    'failed_icon_store_database_toggling' => 'Migration of icons failed. The setting has been restored to its previous value.',
    'failed_to_retrieve_app_settings' => 'Failed to retrieve application settings',
    'reserved_name_please_choose_something_else' => 'Reserved name, please choose something else',
];