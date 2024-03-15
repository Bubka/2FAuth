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

    'resource_not_found' => 'Ресурсът не е намерен',
    'error_occured' => 'Възникна грешка:',
    'refresh' => 'Презареждане',
    'no_valid_otp' => 'Няма валиден OTP ресурс в този QR код',
    'something_wrong_with_server' => 'Нещо не е наред със сървъра ви',
    'Unable_to_decrypt_uri' => 'URIа не може да се декриптира',
    'not_a_supported_otp_type' => 'Този OTP формат не се поддържа',
    'cannot_create_otp_without_secret' => 'Не може да се създаде OTP без тайна',
    'data_of_qrcode_is_not_valid_URI' => 'Данните от този QR код не са валиден OTP Auth URI. QR кодът съдържа:',
    'wrong_current_password' => 'Wrong current password, nothing has changed',
    'error_during_encryption' => 'Шифроването е неуспешно, вашата база данни остава незащитена.',
    'error_during_decryption' => 'Дешифрирането е неуспешно, вашата база данни все още е защитена. Това се дължи главно на проблем с целостта на криптираните данни за един или повече акаунти.',
    'qrcode_cannot_be_read' => 'Този QR код е нечетлив',
    'too_many_ids' => 'твърде много идентификатори бяха включени в параметъра на заявката, разрешени са максимум 100',
    'delete_user_setting_only' => 'Само настройка създадена от потребител може да бъде изтрита',
    'indecipherable' => '*неразгадаем*',
    'cannot_decipher_secret' => 'Тайната не може да бъде разгадана. Това се причинява главно от грешен APP_KEY, зададен в конфигурационния файл .env на 2Fauth или на повредени данни в базата данни.',
    'https_required' => 'Изисква се HTTPS контекст',
    'browser_does_not_support_webauthn' => 'Вашето устройство не поддържа webauthn. Опитайте отново с по-модерен браузър',
    'aborted_by_user' => 'Прекратено от потребителя',
    'security_device_already_registered' => 'Device already registered',
    'not_allowed_operation' => 'Операцията не е разрешена',
    'no_authenticator_support_specified_algorithms' => 'No authenticators support specified algorithms',
    'authenticator_missing_discoverable_credential_support' => 'Authenticator missing discoverable credential support',
    'authenticator_missing_user_verification_support' => 'Authenticator missing user verification support',
    'unknown_error' => 'Неизвестна грешка',
    'security_error_check_rpid' => 'Грешка в сигурността<br/>Проверете вашата WEBAUTHN_ID env променлива',
    '2fauth_has_not_a_valid_domain' => '2FAuth\'s domain is not a valid domain',
    'user_id_not_between_1_64' => 'User ID was not between 1 and 64 chars',
    'no_entry_was_of_type_public_key' => 'No entry was of type "public-key"',
    'unsupported_with_reverseproxy' => 'Не е приложимо при използване на прокси за удостоверяване',
    'user_deletion_failed' => 'Изтриването на профила не бе успешно, няма изтрити данни',
    'auth_proxy_failed' => 'Неуспешно удостоверяване на проксито',
    'auth_proxy_failed_legend' => '2Fauth е конфигуриран да работи зад прокси за удостоверяване, но вашето прокси не връща очаквания хедър. Проверете конфигурацията си и опитайте отново.',
    'invalid_x_migration' => 'Невалидни или нечетливи :appname данни',
    'invalid_2fa_data' => 'Невалидни 2FA данни',
    'unsupported_migration' => 'Данните не отговарят на нито един поддържан формат',
    'unsupported_otp_type' => 'Неподдържан OTP тип',
    'encrypted_migration' => 'Нечетимо, данните изглеждат криптирани',
    'no_logo_found_for_x' => 'Няма налично лого за {service}',
    'file_upload_failed' => 'Качването на файл не бе успешно',
    'unauthorized' => 'Неразрешено',
    'unauthorized_legend' => 'Нямате права да видите този ресурс или да извършите това действие',
    'cannot_delete_the_only_admin' => 'Не може да изтрие единствения администраторски акаунт',
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
];