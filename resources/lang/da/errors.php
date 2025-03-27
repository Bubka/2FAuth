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

    'resource_not_found' => 'Ressource ikke fundet',
    'error_occured' => 'En fejl opstod:',
    'refresh' => 'Opdater',
    'no_valid_otp' => 'Ingen gyldig OTP-ressource i denne QR-kode',
    'something_wrong_with_server' => 'Noget er galt med din server',
    'Unable_to_decrypt_uri' => 'Kan ikke dekryptere uri',
    'not_a_supported_otp_type' => 'Dette OTP-format understøttes ikke i øjeblikket',
    'cannot_create_otp_without_secret' => 'Kan ikke oprette en OTP uden hemmelighed',
    'data_of_qrcode_is_not_valid_URI' => 'Dataene for denne QR-kode er ikke en gyldig OTP Auth URI. QR-koden indeholder:',
    'wrong_current_password' => 'Forkert nuværende adgangskode, intet er ændret',
    'error_during_encryption' => 'Kryptering mislykkedes, din database forbliver ubeskyttet.',
    'error_during_decryption' => 'Dekryptering mislykkedes, din database er stadig beskyttet. Dette skyldes primært et integritetsproblem af krypterede data for en eller flere konti.',
    'qrcode_cannot_be_read' => 'Denne QR-kode kan ikke læses',
    'too_many_ids' => 'for mange id\'er var inkluderet i query parameter, max 100 tilladt',
    'delete_user_setting_only' => 'Kun brugeroprettede indstillinger kan slettes',
    'indecipherable' => '*indecipherable*',
    'cannot_decipher_secret' => 'Hemmeligheden kan ikke dechifreres. Dette er hovedsageligt forårsaget af en forkert APP_KEY sat i . nv konfigurationsfil af 2Fauth eller en beskadiget data gemt i databasen.',
    'https_required' => 'HTTPS kontekst påkrævet',
    'browser_does_not_support_webauthn' => 'Din enhed understøtter ikke webauthn. Prøv igen senere ved hjælp af en mere moderne browser',
    'aborted_by_user' => 'Afbrudt af brugeren',
    'security_device_already_registered' => 'Enhed allerede registreret',
    'not_allowed_operation' => 'Handling ikke tilladt',
    'no_authenticator_support_specified_algorithms' => 'Ingen autentifikatorer understøtter angivne algoritmer',
    'authenticator_missing_discoverable_credential_support' => 'Autentificering mangler synlig legitimationssupport',
    'authenticator_missing_user_verification_support' => 'Autentificering mangler brugerbekræftelsesunderstøttelse',
    'unknown_error' => 'Ukendt fejl',
    'security_error_check_rpid' => 'Sikkerhedsfejl<br/>Tjek din WEBAUTHN_ID env var',
    '2fauth_has_not_a_valid_domain' => '2FAuth\'s domæne er ikke et gyldigt domæne',
    'user_id_not_between_1_64' => 'Bruger-ID var ikke mellem 1 og 64 tegn',
    'no_entry_was_of_type_public_key' => 'Ingen indgang var af typen "public- key"',
    'unsupported_with_reverseproxy' => 'Ikke relevant ved brug af en auth proxy eller SSO',
    'unsupported_with_sso_only' => 'Denne godkendelsesmetode er kun for administratorer. Brugere skal logge ind med SSO.',
    'user_deletion_failed' => 'Sletning af brugerkonto mislykkedes, ingen data er blevet slettet',
    'auth_proxy_failed' => 'Proxygodkendelse mislykkedes',
    'auth_proxy_failed_legend' => '2Fauth er konfigureret til at køre bag en godkendelsesproxy, men din proxy returnerer ikke den forventede header. Tjek din konfiguration og prøv igen.',
    'invalid_x_migration' => 'Ugyldig eller ulæselig :appname data',
    'invalid_2fa_data' => 'Ugyldige 2FA-data',
    'unsupported_migration' => 'Data matcher ikke noget understøttet format',
    'unsupported_otp_type' => 'Ikke understøttet OTP-type',
    'encrypted_migration' => 'Ulæselig, dataene synes krypteret',
    'no_logo_found_for_x' => 'Intet logo tilgængeligt for :service',
    'file_upload_failed' => 'Upload af fil mislykkedes',
    'unauthorized' => 'Ikke Godkendt',
    'unauthorized_legend' => 'Du har ikke rettigheder til at se denne ressource eller til at udføre denne handling',
    'cannot_delete_the_only_admin' => 'Kan ikke slette den eneste admin konto',
    'cannot_demote_the_only_admin' => 'Kan ikke degradere den eneste administratorkonto',
    'error_during_data_fetching' => '💀 Noget gik galt under hentning af data',
    'check_failed_try_later' => 'Kontrollen mislykkedes, prøv igen senere',
    'sso_disabled' => 'SSO er deaktiveret',
    'sso_bad_provider_setup' => 'Denne SSO-udbyder er ikke fuldt opsat i din .env-fil',
    'sso_failed' => 'Godkendelse via SSO afvist',
    'sso_no_register' => 'Registreringer er deaktiveret',
    'sso_email_already_used' => 'En brugerkonto med den samme e-mailadresse findes allerede, men den matcher ikke dit eksterne konto-id. Brug ikke SSO hvis du allerede er registreret på 2FAuth med denne e-mail.',
    'account_managed_by_external_provider' => 'Konto administreres af en ekstern udbyder',
    'data_cannot_be_refreshed_from_server' => 'Data kan ikke opdateres fra server',
    'no_pwd_reset_for_this_user_type' => 'Nulstilling af adgangskode ikke tilgængelig for denne bruger',
    'cannot_detect_qrcode_in_image' => 'Kan ikke registrere en QR-kode i billedet, prøv at beskære billedet',
    'cannot_decode_detected_qrcode' => 'Kan ikke afkode detekteret QR-kode, prøv at beskære eller skærpe billedet',
    'qrcode_has_invalid_checksum' => 'QR-kode har ugyldig checksum',
    'no_readable_qrcode' => 'Ingen læsbar QR-kode',
    'failed_icon_store_database_toggling' => 'Migration af ikoner mislykkedes. Indstillingen er blevet gendannet til dens tidligere værdi.',
    'failed_to_retrieve_app_settings' => 'Failed to retrieve application settings',
    'reserved_name_please_choose_something_else' => 'Reserved name, please choose something else',
];