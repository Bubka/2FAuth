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

    'resource_not_found' => 'Sumber daya tidak ditemukan',
    'error_occured' => 'Terjadi kesalahan:',
    'refresh' => 'Muat Ulang',
    'no_valid_otp' => 'Tidak ada sumber OTP Valid untuk kode QR ini',
    'something_wrong_with_server' => 'Ada yang selah dengan server anda',
    'Unable_to_decrypt_uri' => 'Tidak dapat mendekripsi uri',
    'not_a_supported_otp_type' => 'Format OTP ini saat ini tidak didukung',
    'cannot_create_otp_without_secret' => 'Tidak dapat membuat sebuah OTP tanpa sebuah rahasia',
    'data_of_qrcode_is_not_valid_URI' => 'Data dari kode QR merupakan Auth URI OTP yang tidak valid. Kode QR nya mengandung:',
    'wrong_current_password' => 'Kata sandi saat ini salah, tidak ada yang diubah',
    'error_during_encryption' => 'Enkripsi gagal, database anda masih tetap tidak terlindungi.',
    'error_during_decryption' => 'Dekripsi gagal, database anda masih tetap tidak terlindungi. Hal ini utamanya terjadi karena sebuah masalah integritas enkripsi data untuk satu atau lebih akun.',
    'qrcode_cannot_be_read' => 'Kode QR ini tidak dapat dibaca',
    'too_many_ids' => 'terlalu banyak id yang yang disertakan didalam parameter kueri, maksimal yang diperbolehkan 100',
    'delete_user_setting_only' => 'Only user-created setting can be deleted',
    'indecipherable' => '*indecipherable*',
    'cannot_decipher_secret' => 'The secret cannot be deciphered. This is mainly caused by a wrong APP_KEY set in the .env configuration file of 2Fauth or a corrupted data stored in database.',
    'https_required' => 'HTTPS context required',
    'browser_does_not_support_webauthn' => 'Your device does not support webauthn. Try again later using a more modern browser',
    'aborted_by_user' => 'Aborted by user',
    'security_device_already_registered' => 'Device already registered',
    'not_allowed_operation' => 'Operation not allowed',
    'no_authenticator_support_specified_algorithms' => 'No authenticators support specified algorithms',
    'authenticator_missing_discoverable_credential_support' => 'Authenticator missing discoverable credential support',
    'authenticator_missing_user_verification_support' => 'Authenticator missing user verification support',
    'unknown_error' => 'Unknown error',
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