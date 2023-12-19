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
    'failed' => 'Неверное имя пользователя или пароль.',
    'password' => 'Некорректный пароль.',
    'throttle' => 'Слишком много попыток входа. Пожалуйста, попробуйте еще раз через :seconds секунд.',

    // 2FAuth
    'sign_out' => 'Выйти',
    'sign_in' => 'Войти',
    'sign_in_using' => 'Войти с помощью',
    'or_continue_with' => 'You an also continue with:',
    'sign_in_using_security_device' => 'Войти, используя устройство безопасности',
    'login_and_password' => 'имя пользователя и пароль',
    'register' => 'Регистрация',
    'welcome_to_2fauth' => 'Добро пожаловать в 2FAuth',
    'autolock_triggered' => 'Сработала автоматическая блокировка',
    'autolock_triggered_punchline' => 'Произошло событие которое, отслеживалось функцией автоблокировки. Вы были автоматически отключены.',
    'already_authenticated' => 'Вы уже аутентифицирован',
    'authentication' => 'Аутентификация',
    'maybe_later' => 'Не сейчас',
    'user_account_controlled_by_proxy' => 'User account made available by an authentication proxy.<br />Manage the account at proxy level.',
    'auth_handled_by_proxy' => 'Аутентификация осуществляется на обратном прокси сервере, настройки ниже не доступны.<br />Управление аутентификацией осуществляется на прокси сервере.',
    'confirm' => [
        'logout' => 'Вы уверены, что хотите выйти?',
        'revoke_device' => 'Вы уверены, что хотите удалить это устройство?',
        'delete_account' => 'Вы уверены, что хотите удалить свою учетную запись?',
    ],
    'webauthn' => [
        'security_device' => 'устройство безопасности',
        'security_devices' => 'Устройства безопасности',
        'security_devices_legend' => 'Authentication devices you can use to sign in 2FAuth, like security keys (i.e Yubikey) or smartphones with biometric capabilities (i.e. Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'You can enhance the security of your 2FAuth account by enabling WebAuthn authentication.<br /><br />
            WebAuthn allows you to use trusted devices (like Yubikeys or smartphones with biometric capabilities) to sign in quickly and more securely.',
        'use_security_device_to_sign_in' => 'Get ready to authenticate using (one of) your security devices. Plug your key in, remove face mask or gloves, etc.',
        'lost_your_device' => 'Потеряли устройство?',
        'recover_your_account' => 'Восстановить доступ к аккаунту',
        'account_recovery' => 'Восстановление доступа к аккаунту',
        'recovery_punchline' => '2FAuth will send you a recovery link to this email address. Click the link in the received email and follow the instructions.<br /><br />Ensure you open the email on a device you fully own.',
        'send_recovery_link' => 'Отправить ссылку для восстановления',
        'account_recovery_email_sent' => 'Письмо для восстановления доступа к аккаунту отправлено!',
        'disable_all_security_devices' => 'Отключить все устройства безопасности',
        'disable_all_security_devices_help' => 'All your security devices will be revoked. Use this option if you have lost one or its security has been compromised.',
        'register_a_new_device' => 'Зарегистрировать новое устройство',
        'register_a_device' => 'Зарегистрировать устройство',
        'device_successfully_registered' => 'Устройство успешно зарегистрировано',
        'device_revoked' => 'Устройство успешно деактивировано',
        'revoking_a_device_is_permanent' => 'Удаление устройства необратимо',
        'recover_account_instructions' => 'To recover your account, 2FAuth resets some Webauthn settings so you will be able to sign in using your email and password.',
        'invalid_recovery_token' => 'Неверный код восстановления',
        'webauthn_login_disabled' => 'Webauthn login disabled',
        'invalid_reset_token' => 'This reset token is invalid.',
        'rename_device' => 'Переименовать устройство',
        'my_device' => 'Моё устройство',
        'unknown_device' => 'Неизвестное устройство',
        'use_webauthn_only' => [
            'label' => 'Use WebAuthn only',
            'help' => 'Make WebAuthn the only authorized method to log into your 2FAuth account. This is the recommended setup to take advantage of the WebAuthn enhanced security.<br /><br />
                In case of device lost, you will be able to recover your account by resetting this option and signing in using your email and password.<br /><br />
                Attention! The Email & Password form remains available despite this option being enabled, but it will always return an \'Authentication failed\' response.'
        ],
        'need_a_security_device_to_enable_options' => 'Set at least one device to enable the following options',
    ],
    'forms' => [
        'name' => 'Имя',
        'login' => 'Вход',
        'webauthn_login' => 'WebAuthn login',
        'email' => 'Адрес электронной почты',
        'password' => 'Пароль',
        'reveal_password' => 'Показать пароль',
        'hide_password' => 'Скрыть пароль',
        'confirm_password' => 'Подтверждение пароля',
        'new_password' => 'Новый пароль',
        'confirm_new_password' => 'Подтвердить новый пароль',
        'dont_have_account_yet' => 'Don\'t have your account yet?',
        'already_register' => 'Уже зарегистрированы?',
        'authentication_failed' => 'Ошибка аутентификации',
        'forgot_your_password' => 'Забыли пароль?',
        'request_password_reset' => 'Восстановить',
        'reset_your_password' => 'Восстановить пароль',
        'reset_password' => 'Восстановить пароль',
        'disabled_in_demo' => 'Функция отключена в демо режиме',
        'new_password' => 'New password',
        'current_password' => [
            'label' => 'Текущий пароль',
            'help' => 'Fill in your current password to confirm that it\'s you'
        ],
        'change_password' => 'Изменить пароль',
        'send_password_reset_link' => 'Send password reset link',
        'password_successfully_changed' => 'Password successfully changed',
        'edit_account' => 'Edit account',
        'profile_saved' => 'Profile successfully updated!',
        'welcome_to_demo_app_use_those_credentials' => 'Welcome to the 2FAuth demo.<br><br>You can connect using the email address <strong>demo@2fauth.app</strong> and the password <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Welcome to the 2FAuth testing instance.<br><br>Use email address <strong>testing@2fauth.app</strong> and password <strong>password</strong>',
        'register_punchline' => 'Welcome to <b>2FAuth</b>.<br/>You need an account to go further, please register yourself.',
        'reset_punchline' => '2FAuth will send you a password reset link to this address. Click the link in the received email to set a new password.',
        'name_this_device' => 'Name this device',
        'delete_account' => 'Удалить аккаунт',
        'delete_your_account' => 'Удалить учетную запись',
        'delete_your_account_and_reset_all_data' => 'Your user account will be deleted as well as all your 2FA data. There is no going back.',
        'reset_your_password_to_delete_your_account' => 'If you always used SSO to sign in, sign out then use the reset password feature to get a password so you can fill this form.',
        'deleting_2fauth_account_does_not_impact_provider' => 'Deleting your 2FAuth account has no impact on your external SSO account.',
        'user_account_successfully_deleted' => 'Аккаунт успешно удален',
        'has_lower_case' => 'маленькие буквы',
        'has_upper_case' => 'заглавные буквы',
        'has_special_char' => 'специальный символы',
        'has_number' => 'цифры',
        'is_long_enough' => 'Минимум 8 символов',
        'mandatory_rules' => 'Обязательно',
        'optional_rules_you_should_follow' => 'Recommanded (highly)',
        'caps_lock_is_on' => 'Caps lock включен',
    ],

];
