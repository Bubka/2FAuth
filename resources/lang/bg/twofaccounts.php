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

    'service' => 'Услуга',
    'account' => 'Профил',
    'icon' => 'Икона',
    'icon_to_illustrate_the_account' => 'Икона, илюстрираща акаунта',
    'remove_icon' => 'Премахни икона',
    'no_account_here' => 'Тук няма 2FA!',
    'add_first_account' => 'Изберете метод и добавете първия си акаунт',
    'use_full_form' => 'Или използвайте пълния формуляр',
    'add_one' => 'Добавяне на един',
    'show_qrcode' => 'Покажи QR код',
    'no_service' => '- няма услуга -',
    'account_created' => 'Профилът беше създаден успешно',
    'account_updated' => 'Профилът е актуализиран успешно',
    'accounts_deleted' => 'Профил(ите) са изтрити успешно',
    'accounts_moved' => 'Профил(ите) са преместени успешно',
    'export_selected_accounts' => 'Export selected accounts',
    'twofauth_export_format' => '2FAuth format',
    'twofauth_export_format_sub' => 'Export data using the 2FAuth json schema',
    'twofauth_export_format_desc' => 'You should prefer this option if you need to create a backup that can be restored. This format takes care of the icons.',
    'twofauth_export_format_url' => 'The schema definition is described here:',
    'twofauth_export_schema' => '2FAuth export schema',
    'otpauth_export_format' => 'otpauth URIs',
    'otpauth_export_format_sub' => 'Export data as a list of otpauth URIs',
    'otpauth_export_format_desc' => 'otpauth URI is the most common format used to exchange 2FA data, for example in the form of a QR code when you enable 2FA on a web site. Select this if you want to switch from 2FAuth.',
    'reveal' => 'reveal',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'Джон Доу',
        ],
        'new_account' => 'Нов профил',
        'edit_account' => 'Редактиране на профил',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'Сканирайте QR код',
        'upload_qrcode' => 'Качете QR код',
        'use_advanced_form' => 'Използвайте разширената форма',
        'prefill_using_qrcode' => 'Попълнете използвайки QR код',
        'use_qrcode' => [
            'val' => 'Използвайте QR код',
            'title' => 'Използвайте QR код, за магическо попълване',
        ],
        'unlock' => [
            'val' => 'Отключи',
            'title' => 'Отключи го (на ваш собствен риск)',
        ],
        'lock' => [
            'val' => 'Заключи',
            'title' => 'Заключи го',
        ],
        'choose_image' => 'Качи',
        'i_m_lucky' => 'На късмет',
        'i_m_lucky_legend' => 'Бутонът "На късмет" ще опита да изтегли официалната икона на дадената услуга. Въведете действителното име на услугата без разширение ".xyz" и се опитайте да избегнете правописна грешка. (бета функция)',
        'test' => 'Тест',
        'group' => [
            'label' => 'Group',
            'help' => 'The group to which the account is to be assigned'
        ],
        'secret' => [
            'label' => 'Тайна',
            'help' => 'Ключът, използван за генериране на вашите кодове за сигурност'
        ],
        'plain_text' => 'Чист текст',
        'otp_type' => [
            'label' => 'Изберете типа на <abbr title="One-Time Password">OTP</abbr> за създаване',
            'help' => 'Временен OTP или HMAC базиран OTP или Steam OTP'
        ],
        'digits' => [
            'label' => 'Цифри',
            'help' => 'Броят цифри на генерираните кодове за сигурност'
        ],
        'algorithm' => [
            'label' => 'Алгоритъм',
            'help' => 'Алгоритъмът, използван за защита на вашите кодове за сигурност'
        ],
        'period' => [
            'label' => 'Период',
            'placeholder' => '30 по подразбиране',
            'help' => 'Периодът на валидност на генерираните кодове за сигурност в секунди'
        ],
        'counter' => [
            'label' => 'Брояч',
            'placeholder' => '0 по подразбиране',
            'help' => 'Първоначалната стойност на брояча',
            'help_lock' => 'Рисковано е да редактирате брояча, тъй като можете да десинхронизирате акаунта със сървъра за проверка на услугата. Използвайте иконата за заключване, за да разрешите промяната, но само ако знаете, че правите'
        ],
        'image' => [
            'label' => 'Изображение',
            'placeholder' => 'http://...',
            'help' => 'URL адресът на външно изображение, което да се използва като икона на акаунта'
        ],
        'options_help' => 'Можете да оставите следните опции празни, ако не знаете как да ги зададете. Ще бъдат приложени най-често използваните стойности.',
        'alternative_methods' => 'Алтернативни методи',
        'spaces_are_ignored' => 'Unwanted spaces will be automatically removed'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Сканирането не може да стартира :(',
        'need_grant_permission' => [
            'reason' => '2FAuth няма разрешение за достъп до камерата ви',
            'solution' => 'Трябва да дадете разрешение за използване на камерата на вашето устройство. Ако вече сте отказали и вашият браузър не ви подкани отново, моля, вижте документацията на браузъра, за да разберете как да дадете разрешение.',
            'click_camera_icon' => 'It is usually done by clicking on a slashed camera icon in or next to the browser\'s address bar',
        ],
        'not_readable' => [
            'reason' => 'Неуспешно зареждане на скенера',
            'solution' => 'Камерата използва ли се вече? Уверете се, че никое друго приложение не използва камерата и опитайте отново'
        ],
        'no_cam_on_device' => [
            'reason' => 'Няма камера на това устройство',
            'solution' => 'Може би сте забравили да включите уеб камерата си'
        ],
        'secured_context_required' => [
            'reason' => 'Изисква се защитен контекст',
            'solution' => 'За сканиране се изисква HTTPS. Ако стартирате 2FAuth от вашия компютър, не използвайте виртуален хост, различен от localhost'
        ],
        'https_required' => 'За стрийминг от камерата се изисква HTTPS',
        'camera_not_suitable' => [
            'reason' => 'Инсталираните камери не са подходящи',
            'solution' => 'Моля, използвайте друго устройство/камера'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Бразучера не поддържа поточно API',
            'solution' => 'Трябва да използвате модерен браузър'
        ],
    ],
    'confirm' => [
        'delete' => 'Сигурни ли сте, че искате да изтриете този профил?',
        'cancel' => 'Профилът ще бъде загубен. Сигурен ли си?',
        'discard' => 'Сигурни ли сте, че искате да отхвърлите профила?',
        'discard_all' => 'Сигурни ли сте, че искате да отхвърлите всички профили?',
        'discard_duplicates' => 'Сигурни ли сте, че искате да отхвърлите всички дубликати?',
    ],
    'import' => [
        'import' => 'Импорт',
        'to_import' => 'Импорт',
        'import_legend' => '2FAuth може да импортира данни от различни 2FA приложения.<br />Използвайте функцията за експортиране на тези приложения, за да получите ресурс за мигриране (QR код или файл) и да го заредите, като използвате предпочитания от вас метод по-долу.',
        'import_legend_afterpart' => 'Use the Export feature of these apps to get a migration resource like a QR code or a JSON file then load it here.',
        'upload' => 'Качи',
        'scan' => 'Сканирай',
        'supported_formats_for_qrcode_upload' => 'Приемат се: jpg, jpeg, png, bmp, gif, svg или webp',
        'supported_formats_for_file_upload' => 'Приема се: чист текст, json, 2fas',
        'expected_format_for_direct_input' => 'Expected: A list of otpauth URI, one by line',
        'supported_migration_formats' => 'Поддържани формати за миграция',
        'qr_code' => 'QR код',
        'text_file' => 'Text file',
        'direct_input' => 'Direct input',
        'plain_text' => 'Чист текст',
        'parsing_data' => 'Parsing data...',
        'issuer' => 'Издател',
        'imported' => 'Импортиран',
        'failure' => 'Грешка',
        'x_valid_accounts_found' => 'Намерени са {count} валидни профила',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'The following 2FA accounts were found in the migration resource. So far none of them have been added to 2FAuth.',
        'use_buttons_to_save_or_discard' => 'Use the available buttons to permanently save them to your 2FA collection or discard them.',
        'import_all' => 'Импорт. на всички',
        'import_this_account' => 'Импориране на профила',
        'discard_all' => 'Отхвърли всички',
        'discard_duplicates' => 'Отхвърли дубликатите',
        'discard_this_account' => 'Отхвърли профила',
        'generate_a_test_password' => 'Генериране на тестов код',
        'possible_duplicate' => 'Вече съществува профил със същите данни',
        'invalid_account' => '- невалиден профил -',
        'invalid_service' => '- невалидна услуга -',
        'do_not_set_password_or_encryption' => 'НЕ активирайте защита с парола или криптиране, когато експортирате данни (от 2FA приложение), които искате да импортирате в 2FAuth.',
    ],

];