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
    'preferences' => 'Preferences',
    'account' => 'Профил',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Токени',
    'options' => 'Опции',
    'user_preferences' => 'User preferences',
    'admin_settings' => 'Admin settings',
    'confirm' => [

    ],
    'administration' => 'Administration',
    'administration_legend' => 'While previous settings are user settings (every user can set its own preferences), following settings are global and apply to all users. Only an administrator can view and edit those settings.',
    'you_are_administrator' => 'You are an administrator',
    'general' => 'Общи',
    'security' => 'Сигурност',
    'profile' => 'Профил',
    'change_password' => 'Промяна на паролата',
    'personal_access_tokens' => 'Персонални токени за достъп',
    'token_legend' => 'Токените за персонален достъп позволяват на всяко приложение да се удостовери в 2Fauth API. Трябва да посочите токена за достъп като токен на Bearer в хедърите за оторизация на заявките за потребителски приложения.',
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
        'show_otp_as_dot' => [
            'label' => 'Показване на генерираните еднократни пароли като точка',
            'help' => 'Заменете генерираните знаци за парола с ***, за да осигурите поверителност. Не засягайте функцията за копиране/поставяне'
        ],
        'close_otp_on_copy' => [
            'label' => 'Затворете <abbr title="One-Time Password">OTP</abbr> след копиране',
            'help' => 'Кликването върху генерираната парола ще я копира и автоматично ще я скрива от екрана'
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
            'label' => 'Password formatting',
            'help' => 'Change how the passwords are displayed by grouping digits to ease readability and memorization'
        ],
        'pair' => 'by Pair',
        'pair_legend' => 'Group digits two by two',
        'trio_legend' => 'Group digits three by three',
        'half_legend' => 'Split digits into two equals groups',
        'trio' => 'by Trio',
        'half' => 'by Half',
        'grid' => 'Решетка',
        'list' => 'Списък',
        'theme' => [
            'label' => 'Theme',
            'help' => 'Force a specific theme or apply the theme defined in your system/browser preferences'
        ],
        'light' => 'Light',
        'dark' => 'Dark',
        'automatic' => 'Auto',
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
        'use_encryption' => [
            'label' => 'Защита на чувствителните данни',
            'help' => 'Чувствителните данни, 2FA тайните и имейлите се съхраняват криптирани в база данни. Не забравяйте да направите резервно копие на стойността "APP_KEY" във вашия .env файл (или на целия файл), тъй като тя служи като ключ за криптиране. Няма начин да дешифрирате криптирани данни без този ключ.',
        ],
        'default_group' => [
            'label' => 'Група по подразбиране',
            'help' => 'Групата, към която са свързани новосъздадените акаунти',
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
        'never' => 'Никога',
        'on_otp_copy' => 'При копиране на кода',
        '1_minutes' => 'След 1 минута',
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