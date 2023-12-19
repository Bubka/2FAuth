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
   
    // Laravel
    'failed' => 'Тези идентификационни данни не съответстват на нашите записи.',
    'password' => 'Предоставената парола е неправилна.',
    'throttle' => 'Твърде много опити за влизане. Опитайте се пак след :seconds секунди.',

    // 2FAuth
    'sign_out' => 'Изход',
    'sign_in' => 'Вход',
    'sign_in_using' => 'Влизане с',
    'or_continue_with' => 'You an also continue with:',
    'sign_in_using_security_device' => 'Влезте с помощта на защитно устройство',
    'login_and_password' => 'потребител и парола',
    'register' => 'Регистрация',
    'welcome_to_2fauth' => 'Добре дошли в 2FAuth',
    'autolock_triggered' => 'Задейства се автоматично заключване',
    'autolock_triggered_punchline' => 'Задействано е автоматизно заклюване. Връзката ви е автоматично прекратена.',
    'already_authenticated' => 'Вече сте удостоверени',
    'authentication' => 'Удостоверяване',
    'maybe_later' => 'Може би по-късно',
    'user_account_controlled_by_proxy' => 'Потребителски акаунт, предоставен от прокси за удостоверяване.<br />Управлявайте акаунта на ниво прокси.',
    'auth_handled_by_proxy' => 'Удостоверяването се обработва от ревърс прокси, настройките по-долу са деактивирани.<br />Управлявайте удостоверяването на ниво прокси.',
    'confirm' => [
        'logout' => 'Сигурни ли сте, че искате да излезете?',
        'revoke_device' => 'Сигурни ли сте, че искате да анулирате това устройство?',
        'delete_account' => 'Сигурни ли сте, че искате да изтриете профила си?',
    ],
    'webauthn' => [
        'security_device' => 'устройство за сигурност',
        'security_devices' => 'Устройства за сигурност',
        'security_devices_legend' => 'Устройства за удостоверяване, които можете да използвате за влизане в 2FAuth, като ключове за сигурност (като Yubikey) или смартфони с биометрични възможности (като Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'Можете да подобрите сигурността на вашия 2FAuth акаунт, като активирате WebAuthn удостоверяване.<br /><br />
             WebAuthn ви позволява да използвате надеждни устройства (като Yubikeys или смартфони с биометрични възможности), за да влизате бързо и по-сигурно.',
        'use_security_device_to_sign_in' => 'Пригответе се за удостоверяване с помощта на (едно от) вашите устройства за сигурност. Включете ключа си, свалете маската за лице или ръкавиците и т.н.',
        'lost_your_device' => 'Загубихте устройството си?',
        'recover_your_account' => 'Възстановете профила си',
        'account_recovery' => 'Възстановяване на профил',
        'recovery_punchline' => '2FAuth ще ви изпрати връзка за възстановяване на този имейл адрес. Кликнете върху връзката получена в имейл-а и следвайте инструкциите.<br /><br />Уверете се, че отваряте имейла на устройство, което изцяло притежавате.',
        'send_recovery_link' => 'Изпратете връзка за възстановяване',
        'account_recovery_email_sent' => 'Имейлът за възстановяване на акаунта е изпратен!',
        'disable_all_security_devices' => 'Деактивирайте всички защитни устройства',
        'disable_all_security_devices_help' => 'Всички ваши устройства за сигурност ще бъдат отменени. Използвайте тази опция, ако сте загубили такова устройство или сигурността му е била компрометирана.',
        'register_a_new_device' => 'Регистрирайте ново устройство',
        'register_a_device' => 'Регистрирайте устройство',
        'device_successfully_registered' => 'Устройството е регистрирано успешно',
        'device_revoked' => 'Устройството е анулирано успешно',
        'revoking_a_device_is_permanent' => 'Анулираното на устройство е перманентно',
        'recover_account_instructions' => 'За да възстановите акаунта си, 2FAuth нулира някои настройки на Webauthn, така че да можете да влезете с вашия имейл и парола.',
        'invalid_recovery_token' => 'Невалиден токен за възстановяване',
        'webauthn_login_disabled' => 'Влизането чрез Webauthn е деактивирано',
        'invalid_reset_token' => 'Този токен за нулиране е невалиден.',
        'rename_device' => 'Преименуване на устройство',
        'my_device' => 'Моето устройство',
        'unknown_device' => 'Неразпознато устройство',
        'use_webauthn_only' => [
            'label' => 'Използвайте само WebAuthn',
            'help' => 'Направете WebAuthn единственият разрешен метод за влизане във вашия 2FAuth акаунт. Това е препоръчителната настройка, за да се възползвате от подобрената защита на WebAuthn.<br /><br />
                 В случай на загуба на устройство, ще можете да възстановите акаунта си, като нулирате тази опция и влезете с вашия имейл и парола.<br /><br />
                 внимание! Формулярът за имейл и парола остава достъпен, въпреки че тази опция е активирана, но винаги ще връща отговор „Неуспешно удостоверяване“.'
        ],
        'need_a_security_device_to_enable_options' => 'Задайте поне едно устройство, за да активирате следните опции',
    ],
    'forms' => [
        'name' => 'Име',
        'login' => 'Вход',
        'webauthn_login' => 'WebAuthn вход',
        'email' => 'Имейл',
        'password' => 'Парола',
        'reveal_password' => 'Покажи паролата',
        'hide_password' => 'Скрий паролата',
        'confirm_password' => 'Потвърждаване на паролата',
        'new_password' => 'Нова парола',
        'confirm_new_password' => 'Потвърди новата парола',
        'dont_have_account_yet' => 'Все още нямате акаунт?',
        'already_register' => 'Вече сте регистриран?',
        'authentication_failed' => 'Неуспешна идентификация',
        'forgot_your_password' => 'Забравена парола?',
        'request_password_reset' => 'Възстанови я',
        'reset_your_password' => 'Нулиране на паролата',
        'reset_password' => 'Нулиране на парола',
        'disabled_in_demo' => 'Функцията е деактивирана в демо режима',
        'new_password' => 'New password',
        'current_password' => [
            'label' => 'Текуща парола',
            'help' => 'Попълнете текущата си парола, за да потвърдите, че това сте вие'
        ],
        'change_password' => 'Промяна на паролата',
        'send_password_reset_link' => 'Изпратете връзка за нулиране на паролата',
        'password_successfully_changed' => 'Паролата е променена успешно',
        'edit_account' => 'Редактиране на профил',
        'profile_saved' => 'Профилът е актуализиран успешно!',
        'welcome_to_demo_app_use_those_credentials' => 'Добре дошли в демонстрацията на 2FAuth.<br><br>Можете да влезете с имейл <strong>demo@2fauth.app</strong> и парола <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Добре дошли в тестовата версия на 2FAuth.<br><br>Използвайте имейл <strong>testing@2fauth.app</strong> и парола <strong>password</strong>',
        'register_punchline' => 'Добре дошли в <b>2FAuth</b>.<br/>Имате нужда от акаунт, за да продължите, моля, регистрирайте се.',
        'reset_punchline' => '2FAuth ще ви изпрати връзка за нулиране на паролата на този адрес. Кликнете върху връзката в получения имейл, за да зададете нова парола.',
        'name_this_device' => 'Назовете това устройство',
        'delete_account' => 'Изтриване на профил',
        'delete_your_account' => 'Изтрийте профила си',
        'delete_your_account_and_reset_all_data' => 'Това ще нулира 2FAuth. Вашият потребителски акаунт ще бъде изтрит, както и всички 2FA данни. Няма връщане назад.',
        'reset_your_password_to_delete_your_account' => 'If you always used SSO to sign in, sign out then use the reset password feature to get a password so you can fill this form.',
        'deleting_2fauth_account_does_not_impact_provider' => 'Deleting your 2FAuth account has no impact on your external SSO account.',
        'user_account_successfully_deleted' => 'Потребителският профил е изтрит успешно',
        'has_lower_case' => 'Съдържа малки букви',
        'has_upper_case' => 'Съдържа големи букви',
        'has_special_char' => 'Съдържа символи',
        'has_number' => 'Съдържа числа',
        'is_long_enough' => 'Минимум 8 символа.',
        'mandatory_rules' => 'Задължително',
        'optional_rules_you_should_follow' => 'Силно препоръчително',
        'caps_lock_is_on' => 'Главните букви са включени',
    ],

];
