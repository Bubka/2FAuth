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
    'security_device_unsupported' => 'Unsupported or in use device',
    'not_allowed_operation' => 'Operation not allowed',
    'unsupported_operation' => 'Unsupported operation',
    'unknown_error' => 'Unknown error',
    'security_error_check_rpid' => 'Security error<br/>Check your WEBAUTHN_ID env var',
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
    'unauthorized' => 'Unauthorized',
    'unauthorized_legend' => 'You do not have permissions to view this resource or to perform this action',
    'cannot_delete_the_only_admin' => 'Cannot delete the only admin account'
];