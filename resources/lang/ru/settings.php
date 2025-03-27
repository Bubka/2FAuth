<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'settings' => 'Настройки',
    'preferences' => 'Настройки',
    'account' => 'Учётная запись',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Токены',
    'options' => 'Опции',
    'user_preferences' => 'Настройки пользователя',
    'admin_settings' => 'Настройки администратора',
    'confirm' => [

    ],
    'you_are_administrator' => 'Вы являетесь администратором',
    'account_linked_to_sso_x_provider' => 'Вы вошли через SSO с помощью учётной записи :provider. Ваша информация не может быть изменена здесь, измените данные в :provider.',
    'general' => 'Общие',
    'security' => 'Безопасность',
    'notifications' => 'Уведомления',
    'profile' => 'Профиль',
    'change_password' => 'Изменить пароль',
    'personal_access_tokens' => 'Персональные токены доступа',
    'token_legend' => 'Токены личного доступа позволяют любому приложению аутентифицироваться в API 2Fauth. Вам необходимо указать токен доступа как Bearer токен в HTTP заголовке Authorization запросов от клиентского приложения.',
    'generate_new_token' => 'Сгенерировать новый токен',
    'revoke' => 'Отозвать',
    'token_revoked' => 'Токен успешно отозван',
    'revoking_a_token_is_permanent' => 'Отзыв токена необратим',
    'confirm' => [
        'revoke' => 'Вы уверены, что хотите отозвать этот токен?',
    ],
    'make_sure_copy_token' => 'Убедитесь, что вы скопировали ваш персональный токен доступа прямо сейчас. Вы не сможете увидеть его снова!',
    'data_input' => 'Ввод данных',
    'settings_managed_by_administrator' => 'Some settings are being managed by your administrator',
    'forms' => [
        'edit_settings' => 'Изменить настройки',
        'setting_saved' => 'Настройки сохранены',
        'new_token' => 'Новый токен',
        'some_translation_are_missing' => 'Некоторые переводы отсутствуют на предпочитаемом языке?',
        'help_translate_2fauth' => 'Помогите перевести 2FAuth',
        'language' => [
            'label' => 'Язык',
            'help' => 'Язык, используемый для пользовательского интерфейса 2FAuth. Именованные языки полны, выберите язык, чтобы переопределить настройки браузера по умолчанию.'
        ],
        'timezone' => [
            'label' => 'Часовой пояс',
            'help' => 'Часовой пояс применяется ко всем датам и временам, отображаемым в приложении'
        ],
        'show_otp_as_dot' => [
            'label' => 'Show generated OTP as dot',
            'help' => 'Replace generated password characters with *** to ensure confidentiality. Does not affect the copy/paste feature'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured OTP',
            'help' => 'Разрешить временно раскрывать коды, скрытые звёздочками'
        ],
        'close_otp_on_copy' => [
            'label' => 'Close OTP after copy',
            'help' => 'Нажатие на сгенерированный код, чтобы скопировать его, автоматически скроет его с экрана'
        ],
        'show_next_otp' => [
            'label' => 'Show next OTP',
            'help' => 'Preview the next password, i.e. the password that will replace the current password when it expires. Preferences set for the current OTP also apply to the next one (formatting, show as dot)'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close OTP',
            'help' => 'Автоматически скрывать пароль с экрана после таймаута. Это позволяет избежать ненужных запросов свежих паролей, если вы забыли закрыть просмотр пароля.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Очистить поиск при копировании',
            'help' => 'Очистить строку поиска сразу после копирования кода в буфер обмена'
        ],
        'sort_case_sensitive' => [
            'label' => 'Учитывать регистр символов',
            'help' => 'При вызове принудительно сортировать учётные записи с учетом регистра символов'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copy OTP on display',
            'help' => 'Автоматически копировать сгенерированный код сразу после его появления на экране. Из-за ограничений браузеров, только первый пароль <abbr title="Time-based One-Time Password">TOTP</abbr> будет скопирован, но не последующие'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Использовать базовый сканер QR-кода',
            'help' => 'Если вы столкнулись с проблемами при захвате QR-кодов, это позволяет переключиться на более простой, но более надежный сканер QR-кодов'
        ],
        'display_mode' => [
            'label' => 'Режим отображения',
            'help' => 'Выберите, хотите ли вы отображать учётные записи как список или как сетку'
        ],
        'password_format' => [
            'label' => 'Форматирование кодов',
            'help' => 'Изменить способ отображения кодов путём группировки цифр, чтобы облегчить чтение и запоминание'
        ],
        'pair' => 'парами',
        'pair_legend' => 'Группами по 2 цифры',
        'trio_legend' => 'Группами по 3 цифры',
        'half_legend' => 'Двумя одинаковыми группами',
        'trio' => 'тройками',
        'half' => 'половинками',
        'grid' => 'Сетка',
        'list' => 'Список',
        'theme' => [
            'label' => 'Тема',
            'help' => 'Принудительно использовать тему, определённую в настройках вашей системы/браузера'
        ],
        'light' => 'Светлая',
        'dark' => 'Тёмная',
        'automatic' => 'Авто',
        'show_accounts_icons' => [
            'label' => 'Показать значки',
            'help' => 'Показывать значки учётных записей на главной'
        ],
        'get_official_icons' => [
            'label' => 'Получить официальные значки',
            'help' => '(Пробовать) Получать официальные значки эмитента 2FA при добавлении учётной записи'
        ],
        'auto_lock' => [
            'label' => 'Автоблокировка',
            'help' => 'Выйти из учётной записи автоматически в случае неактивности. Не имеет эффекта, когда аутентификация обрабатывается прокси и не задан пользовательский адрес выхода.'
        ],
        'default_group' => [
            'label' => 'Группа по умолчанию',
            'help' => 'Группа, к которой будут привязаны новые учётные данные',
        ],
        'view_default_group_on_copy' => [
            'label' => 'Показывать группу по умолчанию при копировании',
            'help' => 'Всегда возвращаться в группу по умолчанию когда код OTP скопирован',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Автосохранение учётных записей',
            'help' => 'Новые учётные записи автоматически регистрируются после сканирования или загрузки QR-кода без необходимости нажимать кнопку "Сохранить"',
        ],
        'useDirectCapture' => [
            'label' => 'Быстрый ввод',
            'help' => 'Автоматически использовать способ ввода по умолчанию или предлагать выбор из доступных способов ввода',
        ],
        'defaultCaptureMode' => [
            'label' => 'Способ ввода по умолчанию',
            'help' => 'Способ ввода по умолчанию, который будет использоваться при включенной опции быстрого ввода',
        ],
        'remember_active_group' => [
            'label' => 'Запомнить фильтр группы',
            'help' => 'Сохранить последний примененный фильтр группы и восстановить его при следующем посещении',
        ],
        'otp_generation' => [
            'label' => 'Показывать пароль',
            'help' => 'Установка того, как и когда отображаются <abbr title="One-Time Passwords">OTP</abbr> .<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'С нового устройства',
            'help' => 'Получать email, когда новое устройства подключается к вашей учётной записи 2FAuth'
        ],
        'notify_on_failed_login' => [
            'label' => 'При неудачном входе',
            'help' => 'Получать email при каждой неудачной попытке войти в вашу учётную запись 2FAuth'
        ],
        'show_email_in_footer' => [
            'label' => 'Показывать email в нижнем колонтитуле',
            'help' => 'Отображать email пользователя в нижнем колонтитуле вместо прямых ссылок. Ссылки будут доступны в меню при нажатии на адрес email адрес.'
        ],
        'otp_generation_on_request' => 'После щелчка/касания',
        'otp_generation_on_request_legend' => 'По одиночке, в отдельном окне',
        'otp_generation_on_request_title' => 'Щёлкните на учётную запись, чтобы получить код в отдельном окне',
        'otp_generation_on_home' => 'Постоянно',
        'otp_generation_on_home_legend' => 'Все на домашней странице',
        'otp_generation_on_home_title' => 'Показать все пароли в главной странице, без дополнительных действий',
        'never' => 'Никогда',
        'on_otp_copy' => 'При копировании кода',
        '1_minutes' => 'Через 1 минуту',
        '2_minutes' => 'Через 2 минуты',
        '5_minutes' => 'Через 5 минут',
        '10_minutes' => 'Через 10 минут',
        '15_minutes' => 'Через 15 минут',
        '30_minutes' => 'Через 30 минут',
        '1_hour' => 'Через 1 час',
        '1_day' => 'Через 1 день',
        'livescan' => '📷 Сфотографировать QR-код',
        'upload' => '📤 Загрузить файл с QR-кодом',
        'advanced_form' => '📋 Расширенная форма',
    ],

];