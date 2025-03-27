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

    'accepted' => 'Ви повинні прийняти :attribute.',
    'accepted_if' => 'Поле :attribute має бути прийнятним, коли :other є :value.',
    'active_url' => 'Поле :attribute не є правильним URL.',
    'after' => 'Поле :attribute має містити дату не раніше :date.',
    'after_or_equal' => 'Поле :attribute має містити дату не раніше, або дорівнюватися :date.',
    'alpha' => 'Поле :attribute має містити лише літери.',
    'alpha_dash' => 'Поле :attribute має містити лише літери, цифри, тире та підкреслення.',
    'alpha_num' => 'Поле :attribute має містити лише літери та цифри.',
    'array' => 'Поле :attribute має бути масивом.',
    'ascii' => 'Поле :attribute має містити лише однобайтові буквено-цифрові знаки та символи.',
    'before' => 'Поле :attribute має містити дату не пізніше :date.',
    'before_or_equal' => 'Поле :attribute має містити дату не пізніше, або дорівнюватися :date.',
    'between' => [
        'array' => 'Поле :attribute має містити від :min до :max елементів.',
        'file' => 'Розмір файлу у полі :attribute має бути не менше :min та не більше :max кілобайт.',
        'numeric' => 'Поле :attribute має бути між :min та :max.',
        'string' => 'Текст у полі :attribute має бути не менше :min та не більше :max символів.',
    ],
    'boolean' => 'Поле :attribute повинне містити логічний тип.',
    'can' => 'Поле :attribute містить неавторизоване значення.',
    'confirmed' => 'Поле :attribute не збігається з підтвердженням.',
    'contains' => 'Поле :attribute має містити обов\'язкове значення.',
    'current_password' => 'Пароль неправильний.',
    'date' => 'Поле :attribute не є датою.',
    'date_equals' => 'Поле :attribute має бути датою рівною :date.',
    'date_format' => 'Поле :attribute не відповідає формату :format.',
    'decimal' => 'Поле :attribute має містити :decimal десяткових знаків.',
    'declined' => 'Поле :attribute має бути відхилено.',
    'declined_if' => 'Поле :attribute має бути відхилено, якщо :other є :value.',
    'different' => 'Поля :attribute та :other повинні бути різними.',
    'digits' => 'Довжина цифрового поля :attribute повинна дорівнювати :digits.',
    'digits_between' => 'Довжина цифрового поля :attribute повинна бути від :min до :max.',
    'dimensions' => 'Поле :attribute містить неприпустимі розміри зображення.',
    'distinct' => 'Поле :attribute містить значення, яке дублюється.',
    'doesnt_end_with' => 'Поле :attribute не може закінчуватися одним із таких: :values.',
    'doesnt_start_with' => 'Поле :attribute не може починатися з одного з наступного: :values.',
    'email' => 'Поле :attribute повинне містити коректну електронну адресу.',
    'ends_with' => 'Поле :attribute має закінчуватися одним з наступних значень: :values',
    'enum' => 'Значення поля :attribute відсутнє у списку допустимих значень.',
    'exists' => 'Значення поля :attribute не існує.',
    'extensions' => 'Файл у полі :attribute повинен мати одне з наступних розширень: :values.',
    'file' => 'Поле :attribute має містити файл.',
    'filled' => 'Поле :attribute є обов\'язковим для заповнення.',
    'gt' => [
        'array' => 'Поле :attribute має містити більше ніж :value елементів.',
        'file' => 'Поле :attribute має бути більше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має бути більше ніж :value.',
        'string' => 'Поле :attribute має бути більше ніж :value символів.',
    ],
    'gte' => [
        'array' => 'Поле :attribute має містити :value чи більше елементів.',
        'file' => 'Поле :attribute має дорівнювати чи бути більше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має дорівнювати чи бути більше ніж :value.',
        'string' => 'Поле :attribute має дорівнювати чи бути більше ніж :value символів.',
    ],
    'hex_color' => 'Поле :attribute має мати дійсний шістнадцятковий колір.',
    'image' => 'Поле :attribute має містити зображення.',
    'in' => 'Значення поля :attribute відсутнє у списку допустимих значень.',
    'in_array' => 'Значення поля :attribute не міститься в :other.',
    'integer' => 'Поле :attribute має містити ціле число.',
    'ip' => 'Поле :attribute має містити IP адресу.',
    'ipv4' => 'Поле :attribute має містити IPv4 адресу.',
    'ipv6' => 'Поле :attribute має містити IPv6 адресу.',
    'json' => 'Дані поля :attribute мають бути у форматі JSON.',
    'list' => 'Значення поля :attribute має бути списком.',
    'lowercase' => 'Поле :attribute має бути рядком у нижньому регістрі',
    'lt' => [
        'array' => 'Поле :attribute має містити менше ніж :value items.',
        'file' => 'Поле :attribute має бути менше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має бути менше ніж :value.',
        'string' => 'Поле :attribute має бути менше ніж :value символів.',
    ],
    'lte' => [
        'array' => 'Поле :attribute має містити не більше ніж :value елементів.',
        'file' => 'Поле :attribute має дорівнювати чи бути менше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute має дорівнювати чи бути менше ніж :value.',
        'string' => 'Поле :attribute має дорівнювати чи бути менше ніж :value символів.',
    ],
    'mac_address' => 'Поле :attribute має містити MAC адресу.',
    'max' => [
        'array' => 'Поле :attribute повинне містити не більше :max елементів.',
        'file' => 'Файл в полі :attribute має бути не більше :max кілобайт.',
        'numeric' => 'Поле :attribute має бути не більше :max.',
        'string' => 'Текст в полі :attribute повинен мати довжину не більшу за :max.',
    ],
    'max_digits' => 'Поле :attribute не може містити більше :max цифр.',
    'mimes' => 'Поле :attribute повинне містити файл одного з типів: :values.',
    'mimetypes' => 'Поле :attribute повинне містити файл одного з типів: :values.',
    'min' => [
        'array' => 'Поле :attribute повинне містити не менше :min елементів.',
        'file' => 'Розмір файлу у полі :attribute має бути не меншим :min кілобайт.',
        'numeric' => 'Поле :attribute повинне бути не менше :min.',
        'string' => 'Текст у полі :attribute повинен містити не менше :min символів.',
    ],
    'min_digits' => 'Поле :attribute має містити принаймні :min цифр.',
    'missing' => 'Поле :attribute має бути відсутнім.',
    'missing_if' => 'Поле :attribute має бути відсутнім, якщо :other дорівнює :value.',
    'missing_unless' => 'Поле :attribute має бути відсутнім, якщо :other не є :value.',
    'missing_with' => 'Поле :attribute повинно бути відсутнім, якщо присутнє :values.',
    'missing_with_all' => 'Поле :attribute повинно бути відсутнім, якщо присутні :values.',
    'multiple_of' => 'Поле :attribute повинно бути кратним :value',
    'not_in' => 'Значення поля :attribute не повинно бути у списку.',
    'not_regex' => 'Формат поля :attribute не вірний.',
    'numeric' => 'Поле :attribute повинно містити число.',
    'password' => [
        'letters' => 'Поле :attribute має містити принаймні одну літеру.',
        'mixed' => 'Поле :attribute має містити принаймні одну велику та одну малу літери.',
        'numbers' => 'Поле :attribute має містити принаймні одне число.',
        'symbols' => 'Поле :attribute має містити принаймні один символ.',
        'uncompromised' => 'Поле :attribute з\'явився під час витоку даних. Виберіть інший :attribute.',
    ],
    'present' => 'Поле :attribute повинне бути присутнє.',
    'present_if' => 'Поле :attribute має бути присутнім, коли :other дорівнює :value.',
    'present_unless' => 'Поле :attribute повинно бути присутнім, якщо :other не є :value.',
    'present_with' => 'Поле :attribute повинно бути присутнім, коли присутній :values.',
    'present_with_all' => 'Поле :attribute повинно бути присутнім, якщо присутні :values.',
    'prohibited' => 'Поле :attribute заборонено.',
    'prohibited_if' => 'Поле :attribute заборонено, коли :other дорівнює :value.',
    'prohibited_unless' => 'Поле :attribute заборонено, якщо тільки :other не знаходиться в :values.',
    'prohibits' => 'Поле :attribute забороняє присутність :other.',
    'regex' => 'Поле :attribute має хибний формат.',
    'required' => 'Поле :attribute є обов\'язковим для заповнення.',
    'required_array_keys' => 'Поле :attribute має містити записи для: :values.',
    'required_if' => 'Поле :attribute є обов\'язковим для заповнення, коли :other є рівним :value.',
    'required_if_accepted' => 'Поле :attribute є обов\'язковим, якщо прийнято :other.',
    'required_if_declined' => 'Поле :attribute є обов\'язковим, якщо відхилено :other.',
    'required_unless' => 'Поле :attribute є обов\'язковим для заповнення, коли :other відрізняється від :values',
    'required_with' => 'Поле :attribute є обов\'язковим для заповнення, коли :values вказано.',
    'required_with_all' => 'Поле :attribute є обов\'язковим для заповнення, коли :values вказано.',
    'required_without' => 'Поле :attribute є обов\'язковим для заповнення, коли :values не вказано.',
    'required_without_all' => 'Поле :attribute є обов\'язковим для заповнення, коли :values не вказано.',
    'same' => 'Поля :attribute та :other мають збігатися.',
    'size' => [
        'array' => 'Поле :attribute повинне містити :size елементів.',
        'file' => 'Файл у полі :attribute має бути розміром :size кілобайт.',
        'numeric' => 'Поле :attribute має бути довжини :size.',
        'string' => 'Текст у полі :attribute повинен містити :size символів.',
    ],
    'starts_with' => 'Поле :attribute повинне починатися з одного з наступних значень: :values',
    'string' => 'Поле :attribute повинне містити текст.',
    'timezone' => 'Поле :attribute повинне містити коректну часову зону.',
    'unique' => 'Вказане значення поля :attribute вже існує.',
    'uploaded' => 'Завантаження :attribute не вдалося.',
    'uppercase' => 'Поле :attribute має бути рядком у верхньому регістрі.',
    'url' => 'Формат поля :attribute хибний.',
    'ulid' => 'Поле :attribute має бути коректним ULID.',
    'uuid' => 'Поле :attribute має бути коректним UUID ідентифікатором.',

    'single' => 'When using :attribute it must be the only parameter in this request body',
    'onlyCustomOtpWithUri' => 'The uri parameter must be provided alone or only in combination with the \'custom_otp\' parameter',
    'IsValidRegex' => 'The :attribute field must be a valid regex pattern.',

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
            'image' => 'Supported format are jpeg, png, bmp, gif, svg, or webp.',
        ],
        'qrcode' => [
            'image' => 'Supported format are jpeg, png, bmp, gif, svg, or webp.',
        ],
        'uri' => [
            'regex' => 'The :attribute field is not a valid otpauth uri.',
        ],
        'otp_type' => [
            'in' => 'The :attribute field is not supported.',
        ],
        'email' => [
            'exists' => 'No account found using this email.',
            'ComplyWithEmailRestrictionPolicy' => 'This email address does not comply with the registration policy',
            'IsValidEmailList' => 'All emails must be valid and separated with a pipe'
        ],
        'secret' => [
            'isBase32Encoded' => 'The :attribute field must be a base32 encoded string.',
        ],
        'account' => [
            'regex' => 'The :attribute field must not contain colon.',
        ],
        'service' => [
            'regex' => 'The :attribute field must not contain colon.',
        ],
        'label' => [
            'required' => 'The uri must have a label.',
        ],
        'ids' => [
            'regex' => 'IDs must be comma separated, without trailing comma.',
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
