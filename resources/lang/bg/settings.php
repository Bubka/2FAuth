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
    'preferences' => 'Предпочитания',
    'account' => 'Профил',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Токени',
    'options' => 'Опции',
    'user_preferences' => 'Потребителски предпочитания',
    'admin_settings' => 'Настройки на администратора',
    'confirm' => [

    ],
    'you_are_administrator' => 'Вие сте администратор',
    'account_linked_to_sso_x_provider' => 'You signed-in via SSO using your :provider account. Your information cannot be changed here but on :provider.',
    'general' => 'Общи',
    'security' => 'Сигурност',
    'notifications' => 'Notifications',
    'profile' => 'Профил',
    'change_password' => 'Промяна на паролата',
    'personal_access_tokens' => 'Персонални токени за достъп',
    'token_legend' => 'Токените за персонален достъп позволяват на всяко приложение да се удостовери в 2Fauth API. Трябва да посочите токена за достъп като Bearer токен в хедърите за оторизация на заявките за потребителски приложения.',
    'generate_new_token' => 'Генерирайте нов токен',
    'revoke' => 'Анулирaне',
    'token_revoked' => 'Токена е анулиран успешно',
    'revoking_a_token_is_permanent' => 'Анулираното на токена е перманентно',
    'confirm' => [
        'revoke' => 'Наистина ли искате да анулирате този токен?',
    ],
    'make_sure_copy_token' => 'Уверете се, че сте копирали вашия личен токен за достъп. Няма да можете да го видите отново!',
    'data_input' => 'Въвеждане на данни',
    'forms' => [
        'edit_settings' => 'Редактиране на настройките',
        'setting_saved' => 'Настройките са запазени',
        'new_token' => 'Нов токен',
        'some_translation_are_missing' => 'Някои преводи липсват при използване на предпочитания от браузъра език?',
        'help_translate_2fauth' => 'Помогнете за превода на 2FAuth',
        'language' => [
            'label' => 'Език',
            'help' => 'Език, използван за превод на потребителския интерфейс 2FAuth. Наименуваните езици са завършени, задайте този по ваш избор, който да замени предпочитанията на браузъра ви.'
        ],
        'timezone' => [
            'label' => 'Time zone',
            'help' => 'The time zone applied to all dates and times displayed in the application'
        ],
        'show_otp_as_dot' => [
            'label' => 'Показване на генерираните еднократни пароли като точка',
            'help' => 'Заменете генерираните знаци за парола с ***, за да осигурите поверителност. Не засягайте функцията за копиране/поставяне'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Reveal obscured <abbr title="One-Time Password">OTP</abbr>',
            'help' => 'Let the ability to temporarily reveal Dot-Obscured passwords'
        ],
        'close_otp_on_copy' => [
            'label' => 'Затворете <abbr title="One-Time Password">OTP</abbr> след копиране',
            'help' => 'Click on a generated password to copy it automatically hides it from the screen'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close <abbr title="One-Time Password">OTP</abbr>',
            'help' => 'Automatically hide on-screen password after a timeout. This avoids unnecessary requests for fresh passwords if you forget to close the password view.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Clear Search on copy',
            'help' => 'Empty the Search box right after a code has been copied to the clipboard'
        ],
        'sort_case_sensitive' => [
            'label' => 'Sort case sensitive',
            'help' => 'When invoked, force the Sort function to sort accounts on a case-sensitive basis'
        ],
        'copy_otp_on_display' => [
            'label' => 'Копирайте <abbr title="One-Time Password">OTP</abbr> при показване',
            'help' => 'Автоматично копиране на генерирана парола веднага след като се появи на екрана. Поради ограниченията на браузърите само първата <abbr title="Time-based One-Time Password">TOTP</abbr> парола ще бъде копирана, а не ротационните'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Използване на основен четец на QR код',
            'help' => 'Ако имате проблеми при заснемането на QR кодове, тази опция позволява превключване към по-прост, но по-надежден четец на QR кодове'
        ],
        'display_mode' => [
            'label' => 'Режим на показване',
            'help' => 'Изберете дали искате профилите да се показват като списък или като решетка'
        ],
        'password_format' => [
            'label' => 'Форматиране на парола',
            'help' => 'Променете начина, по който се показват паролите, като групирате цифри, за да улесните четливостта и запаметяването'
        ],
        'pair' => 'на Две',
        'pair_legend' => 'Групиране на цифрите две по две',
        'trio_legend' => 'Групиране на цифрите три по три',
        'half_legend' => 'Разделяне на цифрите на две равни групи',
        'trio' => 'на Три',
        'half' => 'на Половина',
        'grid' => 'Решетка',
        'list' => 'Списък',
        'theme' => [
            'label' => 'Облик',
            'help' => 'Прилагане на специфичен облик или прилагане на предпочитания според системните/браузърните предпочитания'
        ],
        'light' => 'Светъл',
        'dark' => 'Тъмен',
        'automatic' => 'Авто',
        'show_accounts_icons' => [
            'label' => 'Показвай иконите',
            'help' => 'Показва иконите на профилите в основният екран'
        ],
        'get_official_icons' => [
            'label' => 'Взимане на официални икони',
            'help' => 'Взима официалната икона на издателя на 2FA при добавяне на акаунт (ако е възможно)'
        ],
        'auto_lock' => [
            'label' => 'Автоматично заключване',
            'help' => 'Отписване на потребителя автоматично в случай на неактивност. Няма ефект, когато удостоверяването се обработва от прокси и не е указан персонализиран URL адрес за излизане.'
        ],
        'default_group' => [
            'label' => 'Група по подразбиране',
            'help' => 'Групата, към която са свързани новосъздадените акаунти',
        ],
        'view_default_group_on_copy' => [
            'label' => 'View default group on copy',
            'help' => 'Always return to the default group when an OTP is copied',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Auto-save accounts',
            'help' => 'New accounts are automatically registered after scanning or uploading a QR code, no need to click a Save button',
        ],
        'useDirectCapture' => [
            'label' => 'Директно въвеждане',
            'help' => 'Изберете дали искате да бъдете подканени да изберете режим на въвеждане сред наличните или искате директно да използвате режима на въвеждане по подразбиране',
        ],
        'defaultCaptureMode' => [
            'label' => 'Режим на въвеждане по подразбиране',
            'help' => 'Режим на въвеждане по подразбиране, използван, когато опцията Директно въвеждане е включена',
        ],
        'remember_active_group' => [
            'label' => 'Запомняне на груповия филтър',
            'help' => 'Запазва последния приложен групов филтър и го възстановява при следващото ви посещение',
        ],
        'otp_generation' => [
            'label' => 'Покажи паролата',
            'help' => 'Задайте как и кога <abbr title="One-Time Passwords">OTPs</abbr> да се показват.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'On new device',
            'help' => 'Get an email when a new device connects to your 2FAuth account for the first time'
        ],
        'notify_on_failed_login' => [
            'label' => 'On failed login',
            'help' => 'Get an email each time an attempt to connect to your 2FAuth account fails'
        ],
        'otp_generation_on_request' => 'След клик/тап',
        'otp_generation_on_request_legend' => 'Самостоятелно, в отделен изглед',
        'otp_generation_on_request_title' => 'Щракнете върху акаунт, за да получите парола в отделен изглед',
        'otp_generation_on_home' => 'Постоянно',
        'otp_generation_on_home_legend' => 'Всички в основният изглед',
        'otp_generation_on_home_title' => 'Показване на всички пароли в основния изглед, без да правите нищо',
        'never' => 'Никога',
        'on_otp_copy' => 'При копиране на кода',
        '1_minutes' => 'След 1 минута',
        '2_minutes' => 'After 2 minutes',
        '5_minutes' => 'След 5 минути',
        '10_minutes' => 'След 10 минути',
        '15_minutes' => 'След 15 минути',
        '30_minutes' => 'След 30 минути',
        '1_hour' => 'След 1 час',
        '1_day' => 'След 1 ден',
        'livescan' => 'Сканиране на QR код',
        'upload' => 'Качване на QR код',
        'advanced_form' => 'Разширена форма',
    ],

];