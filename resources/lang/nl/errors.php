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

    'resource_not_found' => 'Bron niet gevonden',
    'error_occured' => 'Er is een fout opgetreden:',
    'refresh' => 'Vernieuwen',
    'no_valid_otp' => 'Geen geldige OTP bron in deze QR code',
    'something_wrong_with_server' => 'Er is iets mis met je server',
    'Unable_to_decrypt_uri' => 'Kan uri niet ontsleutelen',
    'not_a_supported_otp_type' => 'Dit OTP formaat wordt momenteel niet ondersteund',
    'cannot_create_otp_without_secret' => 'Kan geen OTP maken zonder een geheim',
    'data_of_qrcode_is_not_valid_URI' => 'De gegevens van deze QR code zijn geen geldige OTP Auth URI. De QR-code bevat het volgende:',
    'wrong_current_password' => 'Onjuist huidig wachtwoord, niets is veranderd',
    'error_during_encryption' => 'Versleuteling mislukt, je database blijft onbeschermd.',
    'error_during_decryption' => 'Ontsleuteling mislukt, uw database wordt nog steeds beschermd. Dit wordt voornamelijk veroorzaakt door een integriteitsprobleem van versleutelde data voor een of meer accounts.',
    'qrcode_cannot_be_read' => 'Deze QR code is onleesbaar',
    'too_many_ids' => 'te veel id\'s zijn opgenomen in de query parameter, max 100 toegestaan',
    'delete_user_setting_only' => 'Alleen gebruiker-gemaakte instelling kan worden verwijderd',
    'indecipherable' => '*onbeschrijfbaar*',
    'cannot_decipher_secret' => 'Het geheim kan niet worden ontcijferd. Dit wordt voornamelijk veroorzaakt door een verkeerde APP_KEY set in de . nv configuratiebestand van 2Fauth of beschadigde gegevens opgeslagen in de database.',
    'https_required' => 'HTTPS context vereist',
    'browser_does_not_support_webauthn' => 'Je apparaat ondersteunt geen webauthn. Probeer het later opnieuw met een modernere browser',
    'aborted_by_user' => 'Geannuleerd door gebruiker',
    'security_device_already_registered' => 'Apparaat is al geregistreerd',
    'not_allowed_operation' => 'Actie niet toegestaan',
    'no_authenticator_support_specified_algorithms' => 'Geen authenticators ondersteunen opgegeven algoritmen',
    'authenticator_missing_discoverable_credential_support' => 'Authenticator mist detecteerbare referentie ondersteuning',
    'authenticator_missing_user_verification_support' => 'Authenticator mist ondersteuning voor verificatie',
    'unknown_error' => 'Onbekende fout',
    'security_error_check_rpid' => 'Beveiligingsfout<br/>Controleer uw WEBAUTHN_ID env var',
    '2fauth_has_not_a_valid_domain' => '2FAuth\'s domein is geen geldig domein',
    'user_id_not_between_1_64' => 'Gebruikers ID was niet tussen 1 en 64 tekens',
    'no_entry_was_of_type_public_key' => 'Geen item van het type "public key"',
    'unsupported_with_reverseproxy' => 'Not applicable when using an auth proxy or SSO',
    'unsupported_with_sso_only' => 'This authentication method is for administrators only. Users must log in with SSO.',
    'user_deletion_failed' => 'Gebruikersaccount verwijderen mislukt, er zijn geen gegevens verwijderd',
    'auth_proxy_failed' => 'Proxy authenticatie mislukt',
    'auth_proxy_failed_legend' => '2Fauth is geconfigureerd om achter een authenticatie-proxy uit te voeren, maar uw proxy geeft de verwachte header niet terug. Controleer uw configuratie en probeer het opnieuw.',
    'invalid_x_migration' => 'Ongeldige of onleesbare :appname data',
    'invalid_2fa_data' => 'Ongeldige 2FA gegevens',
    'unsupported_migration' => 'Gegevens komen niet overeen met een ondersteund formaat',
    'unsupported_otp_type' => 'Niet ondersteund OTP type',
    'encrypted_migration' => 'Onleesbaar, de data lijkt versleuteld',
    'no_logo_found_for_x' => 'Geen logo beschikbaar voor :service',
    'file_upload_failed' => 'Bestand uploaden mislukt',
    'unauthorized' => 'Niet geautoriseerd',
    'unauthorized_legend' => 'U heeft geen rechten om dit document te bekijken of deze actie uit te voeren',
    'cannot_delete_the_only_admin' => 'Kan de enige admin account niet verwijderen',
    'cannot_demote_the_only_admin' => 'Kan de enige admin account niet degraderen',
    'error_during_data_fetching' => '💀 Er is iets misgegaan tijdens het ophalen van gegevens',
    'check_failed_try_later' => 'Controle mislukt, probeer het later opnieuw',
    'sso_disabled' => 'SSO is uitgeschakeld',
    'sso_bad_provider_setup' => 'Deze SSO provider is niet volledig ingesteld in uw .env bestand',
    'sso_failed' => 'Verificatie via SSO geweigerd',
    'sso_no_register' => 'Registraties zijn uitgeschakeld',
    'sso_email_already_used' => 'Een gebruikersaccount met hetzelfde e-mailadres bestaat al, maar komt niet overeen met uw account-ID. Gebruik geen SSO als je al op 2FAuth geregistreerd bent met dit e-mailadres.',
    'account_managed_by_external_provider' => 'Account wordt beheerd door een externe provider',
    'data_cannot_be_refreshed_from_server' => 'Gegevens kunnen niet worden vernieuwd vanaf server',
    'no_pwd_reset_for_this_user_type' => 'Wachtwoord herstellen niet beschikbaar voor deze gebruiker',
    'cannot_detect_qrcode_in_image' => 'Kan geen QR-code vinden op de afbeelding, probeer de afbeelding bij te snijden',
    'cannot_decode_detected_qrcode' => 'Kan QR-code niet decoderen, probeer de afbeelding bij te snijden of te verscherpen',
    'qrcode_has_invalid_checksum' => 'QR code heeft een ongeldige controlesom',
    'no_readable_qrcode' => 'Geen leesbare QR- code',
    'failed_icon_store_database_toggling' => 'Migration of icons failed. The setting has been restored to its previous value.',
];