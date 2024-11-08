<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Вы должны принять :attribute.',
    'accepted_if' => 'Вы должны принять :attribute, когда :other соответствует :value.',
    'active_url' => 'Значение поля :attribute должно быть корректным URL.',
    'after' => 'Значение поля :attribute должно быть датой после :date.',
    'after_or_equal' => 'Значение поля :attribute должно быть датой большей или равной :date.',
    'alpha' => 'Значение поля :attribute должно содержать только буквы.',
    'alpha_dash' => 'Значение поля :attribute должно содержать только буквы, цифры, дефис и нижнее подчёркивание.',
    'alpha_num' => 'Значение поля :attribute должно содержать только буквы и цифры.',
    'array' => 'Значение поля :attribute должно быть массивом.',
    'ascii' => 'Значение поля :attribute должно содержать только однобайтовые цифро-буквенные символы.',
    'before' => 'Значение поля :attribute должно быть датой до :date.',
    'before_or_equal' => 'Значение поля :attribute должно быть датой меньшей или равной :date.',
    'between' => [
        'array' => 'Значение поля :attribute должно содержать от :min до :max элементов.',
        'file' => 'Файл в поле :attribute должен быть от :min до :max килобайт.',
        'numeric' => 'Значение поля :attribute должно быть от :min до :max.',
        'string' => 'Значение поля :attribute должно быть длинной от :min до :max символов.',
    ],
    'boolean' => 'Поле :attribute должно иметь значение логического типа.',
    'can' => 'Значение поля :attribute содержит недопустимое значение.',
    'confirmed' => 'Значение :attribute не совпадает.',
    'contains' => 'Значение поля :attribute не содержит обязательного значения.',
    'current_password' => 'Неверный пароль.',
    'date' => 'Значение поля :attribute должно быть корректной датой.',
    'date_equals' => 'Значение поля :attribute должно быть датой равной :date.',
    'date_format' => 'Значение :attribute должно соответствовать формату :format.',
    'decimal' => 'Значение поля :attribute должно содержать :decimal цифр десятичных разрядов.',
    'declined' => 'Вы должны отклонить :attribute.',
    'declined_if' => 'Значение поля :attribute должно отсутствовать, когда :other равно :value.',
    'different' => 'Значения полей :attribute и :other должны различаться.',
    'digits' => 'Значение поля :attribute должно быть числом.',
    'digits_between' => 'Значение поля :attribute должно содержать от :min до :max цифр.',
    'dimensions' => 'Поле :attribute содержит изображение с недопустимым разрешением.',
    'distinct' => 'Поле :attribute содержит повторяющееся значение.',
    'doesnt_end_with' => 'Значение поля :attribute не должно заканчиваться одним из следующих значений: :values.',
    'doesnt_start_with' => 'Значение поля :attribute не должно начинаться с одного из следующих значений: :values.',
    'email' => 'Значение поля :attribute должно быть корректным адресом электронной почты.',
    'ends_with' => 'Значение поля :attribute должно заканчиваться одним из следующих значений: :values.',
    'enum' => 'Значение поля :attribute некорректно.',
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'extensions' => 'Файл в поле :attribute должен иметь одно из следующих расширений: :values.',
    'file' => 'Значение поля :attribute должно быть файлом.',
    'filled' => 'Поле :attribute обязательно для заполнения.',
    'gt' => [
        'array' => 'Количество элементов в поле :attribute должно быть больше :value.',
        'file' => 'Размер файла в поле :attribute должен быть больше :value килобайт.',
        'numeric' => 'Значение поля :attribute должно быть больше чем :value.',
        'string' => 'Значение поля :attribute должно содержать больше :value символа(ов).',
    ],
    'gte' => [
        'array' => 'Количество элементов в поле :attribute должно быть :value или больше.',
        'file' => 'Размер файла в поле :attribute должен равняться или быть более :value килобайт.',
        'numeric' => 'Значение поля :attribute должно быть :value или больше.',
        'string' => 'Значение поля :attribute должно содержать :value символ(ов) или больше.',
    ],
    'hex_color' => 'Значение поля :attribute должно быть корректным цветом в шестнадцатеричном формате.',
    'image' => 'Значение поля :attribute должно быть изображением.',
    'in' => 'Выбранное значение для :attribute ошибочно.',
    'in_array' => 'Значение поля :attribute должно существовать в :other.',
    'integer' => 'Значение :attribute должно быть целым числом.',
    'ip' => 'Значение поля :attribute должно быть корректным IP адресом.',
    'ipv4' => 'Значение поля :attribute должно быть корректным IPv4 адресом.',
    'ipv6' => 'Значение поля :attribute должно быть корректным IPv6 адресом.',
    'json' => 'Значение поля :attribute должно быть корректной JSON строкой.',
    'list' => 'Значение поля :attribute должно быть списком.',
    'lowercase' => 'Значение поля :attribute должно быть в нижнем регистре.',
    'lt' => [
        'array' => 'Количество элементов в поле :attribute должно быть меньше :value.',
        'file' => 'Размер файла в поле :attribute должен быть меньше :value килобайт.',
        'numeric' => 'Значение поля :attribute должно быть меньше чем :value.',
        'string' => 'Значение поля :attribute должно содержать меньше :value символа(ов).',
    ],
    'lte' => [
        'array' => 'Количество элементов в поле :attribute должно быть меньше :value.',
        'file' => 'Размер файла в поле :attribute должен быть :value килобайт или меньше.',
        'numeric' => 'Значение поля :attribute должно быть :value или меньше .',
        'string' => 'Значение поля :attribute должно содержать :value символ(ов) или меньше.',
    ],
    'mac_address' => 'Значение поля :attribute должно быть корректным MAC адресом.',
    'max' => [
        'array' => 'Количество элементов в поле :attribute должно быть больше :value.',
        'file' => 'Размер файла в поле :attribute не может быть больше :max килобайт.',
        'numeric' => 'Значение поля :attribute должно быть не больше чем :value.',
        'string' => 'Значение поля :attribute должно содержать не больше :value символа(ов).',
    ],
    'max_digits' => 'Значение поля :attribute не должно содержать не более :max цифр(ы).',
    'mimes' => 'Значение поля :attribute должно быть файлом типа :values.',
    'mimetypes' => 'Значение поля :attribute должно быть файлом типа :values.',
    'min' => [
        'array' => 'Значение поля :attribute должно содержать хотя бы :min элемент(ов).',
        'file' => 'Размер файла в поле :attribute должен быть хотя бы :min килобайт.',
        'numeric' => 'Значение поля :attribute должно быть хотя бы :min.',
        'string' => 'Значение поля :attribute должно содержать хотя бы :min символов.',
    ],
    'min_digits' => 'Значение поля :attribute должно содержать хотя бы :min цифр.',
    'missing' => 'Значение поля :attribute должно отсутствовать.',
    'missing_if' => 'Значение поля :attribute должно отсутствовать, когда :other равно :value.',
    'missing_unless' => 'Значение поля :attribute должно отсутствовать, пока :other равно :value.',
    'missing_with' => 'Значение поля :attribute должно отсутствовать, если :values указано.',
    'missing_with_all' => 'Значение поля :attribute должно отсутствовать, если :values указано.',
    'multiple_of' => 'Значение поля :attribute должно быть кратным :value.',
    'not_in' => 'Выбранное значение для :attribute ошибочно.',
    'not_regex' => 'Значение поля :attribute в неверном формате.',
    'numeric' => 'Значение поля :attribute должно быть числом.',
    'password' => [
        'letters' => 'Значение поля :attribute должно содержать хотя бы одну букву.',
        'mixed' => 'Значение поля :attribute должно содержать хотя бы одну прописную и одну строчную буквы.',
        'numbers' => 'Значение поля :attribute должно содержать хотя бы одну цифру.',
        'symbols' => 'Значение поля :attribute должно содержать хотя бы один символ.',
        'uncompromised' => 'Значение поля :attribute обнаружено в утёкших данных. Пожалуйста, выберите другое значение для :attribute.',
    ],
    'present' => 'Поле :attribute должно присутствовать.',
    'present_if' => 'Поле :attribute должно присутствовать, если :other равно :value.',
    'present_unless' => 'Значение поля :attribute должно присутствовать, пока :other равно :value.',
    'present_with' => 'Значение поля :attribute должно присутствовать, если присутствует :values.',
    'present_with_all' => 'Значение поля :attribute должно присутствовать, если присутствует :values.',
    'prohibited' => 'Значение поля :attribute запрещено.',
    'prohibited_if' => 'Значение поля :attribute запрещено, когда :other равно :value.',
    'prohibited_unless' => 'Значение поля :attribute запрещено, если :other не состоит в :values.',
    'prohibits' => 'Значение поля :attribute запрещает присутствие :other.',
    'regex' => 'Значение поля :attribute в неверном формате.',
    'required' => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys' => 'Массив в поле :attribute обязательно должен иметь ключи: :values',
    'required_if' => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
    'required_if_accepted' => 'Поле :attribute обязательно, когда :other принято.',
    'required_if_declined' => 'Значение поля :attribute обязательно, когда :other отклонено.',
    'required_unless' => 'Поле :attribute обязательно для заполнения, когда :other не равно :values.',
    'required_with' => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_with_all' => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_without' => 'Поле :attribute обязательно для заполнения, когда :values не указано.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, когда ни одно из :values не указано.',
    'same' => 'Значение поля :attribute не должно совпадать с :other.',
    'size' => [
        'array' => 'Количество элементов в поле :attribute должно быть равным :size.',
        'file' => 'Размер файла в поле :attribute должен быть равен :size килобайт.',
        'numeric' => 'Значение поля :attribute должно иметь размер :size.',
        'string' => 'Значение поля :attribute должно иметь размер :size символов.',
    ],
    'starts_with' => 'Значение поля :attribute должно начинаться с одного из следующих: :values.',
    'string' => 'Значение поля :attribute должно быть строкой.',
    'timezone' => 'Значение поля :attribute должно быть корректным часовым поясом.',
    'unique' => 'Такое значение поля :attribute уже существует.',
    'uploaded' => 'Загрузка поля :attribute не удалась.',
    'uppercase' => 'Значение поля :attribute должно быть в верхнем регистре.',
    'url' => 'Значение поля :attribute должно быть корректным URL.',
    'ulid' => 'Значение поля :attribute должно быть корректным ULID.',
    'uuid' => 'Значение поля :attribute должно быть корректным UUID.',

    'single' => 'При использовании :attribute - это должен быть единственный параметр в теле этого запроса',
    'onlyCustomOtpWithUri' => 'Параметр uri должен быть указан один или в сочетании с параметром \'custom_otp\'',
    'IsValidRegex' => 'Значение поля :attribute должно быть корректным регулярным выражением.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'icon' => [
            'image' => 'Поддерживаемый формат - jpeg, png, bmp, gif, svg или webp.',
        ],
        'qrcode' => [
            'image' => 'Поддерживаемый формат - jpeg, png, bmp, gif, svg или webp.',
        ],
        'uri' => [
            'regex' => 'Значение поля :attribute не является корректным OTPauth URI.',
        ],
        'otp_type' => [
            'in' => 'Значение поля :attribute не поддерживается.',
        ],
        'email' => [
            'exists' => 'Учётная запись с таким электронным адресом не найдена.',
            'ComplyWithEmailRestrictionPolicy' => 'Этот адрес электронной почты не соответствует условиям регистрации',
            'IsValidEmailList' => 'Все адреса электронной почты должны быть действительными и разделены вертикальной чертой'
        ],
        'secret' => [
            'isBase32Encoded' => 'Значение поля :attribute должно быть строкой в кодировке base32.',
        ],
        'account' => [
            'regex' => 'Значение поля :attribute не должно содержать двоеточий.',
        ],
        'service' => [
            'regex' => 'Значение поля :attribute не должно содержать двоеточий.',
        ],
        'label' => [
            'required' => 'URI должен иметь метку.',
        ],
        'ids' => [
            'regex' => 'Идентификаторы должны быть разделены запятыми. Запятая в конце не ставится.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
