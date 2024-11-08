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

    'accepted' => 'The :attribute field must be accepted.',
    'accepted_if' => 'The :attribute field must be accepted when :other is :value.',
    'active_url' => 'The :attribute field must be a valid URL.',
    'after' => 'The :attribute field must be a date after :date.',
    'after_or_equal' => 'The :attribute field must be a date after or equal to :date.',
    'alpha' => 'The :attribute field must only contain letters.',
    'alpha_dash' => 'The :attribute field must only contain letters, numbers, dashes, and underscores.',
    'alpha_num' => 'The :attribute field must only contain letters and numbers.',
    'array' => 'The :attribute field must be an array.',
    'ascii' => 'The :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'before' => 'The :attribute field must be a date before :date.',
    'before_or_equal' => 'The :attribute field must be a date before or equal to :date.',
    'between' => [
        'array' => 'The :attribute field must have between :min and :max items.',
        'file' => 'The :attribute field must be between :min and :max kilobytes.',
        'numeric' => 'The :attribute field must be between :min and :max.',
        'string' => 'The :attribute field must be between :min and :max characters.',
    ],
    'boolean' => ':attribute फ़ील्ड सही या गलत होनी चाहिए।',
    'can' => 'The :attribute field contains an unauthorized value.',
    'confirmed' => 'The :attribute field confirmation does not match.',
    'contains' => 'The :attribute field is missing a required value.',
    'current_password' => 'पासवर्ड गलत है।',
    'date' => 'The :attribute field must be a valid date.',
    'date_equals' => 'The :attribute field must be a date equal to :date.',
    'date_format' => 'The :attribute field must match the format :format.',
    'decimal' => 'The :attribute field must have :decimal decimal places.',
    'declined' => 'The :attribute field must be declined.',
    'declined_if' => 'The :attribute field must be declined when :other is :value.',
    'different' => 'The :attribute field and :other must be different.',
    'digits' => 'The :attribute field must be :digits digits.',
    'digits_between' => 'The :attribute field must be between :min and :max digits.',
    'dimensions' => 'The :attribute field has invalid image dimensions.',
    'distinct' => ':attribute फील्ड में डुप्लिकेट मान है।',
    'doesnt_end_with' => 'The :attribute field must not end with one of the following: :values.',
    'doesnt_start_with' => 'The :attribute field must not start with one of the following: :values.',
    'email' => 'The :attribute field must be a valid email address.',
    'ends_with' => 'The :attribute field must end with one of the following: :values.',
    'enum' => 'चुना गया :attribute वैध नहीं है।',
    'exists' => 'चुना गया :attribute वैध नहीं है।',
    'extensions' => 'The :attribute field must have one of the following extensions: :values.',
    'file' => 'The :attribute field must be a file.',
    'filled' => ':attribute के स्थान में एक मान होना चाहिए।',
    'gt' => [
        'array' => 'The :attribute field must have more than :value items.',
        'file' => 'The :attribute field must be greater than :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than :value.',
        'string' => 'The :attribute field must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'The :attribute field must have :value items or more.',
        'file' => 'The :attribute field must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than or equal to :value.',
        'string' => 'The :attribute field must be greater than or equal to :value characters.',
    ],
    'hex_color' => 'The :attribute field must be a valid hexadecimal color.',
    'image' => 'The :attribute field must be an image.',
    'in' => 'चुना गया :attribute वैध नहीं है।',
    'in_array' => 'The :attribute field must exist in :other.',
    'integer' => 'The :attribute field must be an integer.',
    'ip' => 'The :attribute field must be a valid IP address.',
    'ipv4' => 'The :attribute field must be a valid IPv4 address.',
    'ipv6' => 'The :attribute field must be a valid IPv6 address.',
    'json' => 'The :attribute field must be a valid JSON string.',
    'list' => 'The :attribute field must be a list.',
    'lowercase' => 'The :attribute field must be lowercase.',
    'lt' => [
        'array' => 'The :attribute field must have less than :value items.',
        'file' => 'The :attribute field must be less than :value kilobytes.',
        'numeric' => 'The :attribute field must be less than :value.',
        'string' => 'The :attribute field must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'The :attribute field must not have more than :value items.',
        'file' => 'The :attribute field must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be less than or equal to :value.',
        'string' => 'The :attribute field must be less than or equal to :value characters.',
    ],
    'mac_address' => 'The :attribute field must be a valid MAC address.',
    'max' => [
        'array' => 'The :attribute field must not have more than :max items.',
        'file' => 'The :attribute field must not be greater than :max kilobytes.',
        'numeric' => 'The :attribute field must not be greater than :max.',
        'string' => 'The :attribute field must not be greater than :max characters.',
    ],
    'max_digits' => 'The :attribute field must not have more than :max digits.',
    'mimes' => 'The :attribute field must be a file of type: :values.',
    'mimetypes' => 'The :attribute field must be a file of type: :values.',
    'min' => [
        'array' => 'The :attribute field must have at least :min items.',
        'file' => 'The :attribute field must be at least :min kilobytes.',
        'numeric' => 'The :attribute field must be at least :min.',
        'string' => 'The :attribute field must be at least :min characters.',
    ],
    'min_digits' => 'The :attribute field must have at least :min digits.',
    'missing' => 'The :attribute field must be missing.',
    'missing_if' => 'The :attribute field must be missing when :other is :value.',
    'missing_unless' => 'The :attribute field must be missing unless :other is :value.',
    'missing_with' => 'The :attribute field must be missing when :values is present.',
    'missing_with_all' => 'The :attribute field must be missing when :values are present.',
    'multiple_of' => 'The :attribute field must be a multiple of :value.',
    'not_in' => 'चुना गया :attribute वैध नहीं है।',
    'not_regex' => 'The :attribute field format is invalid.',
    'numeric' => 'The :attribute field must be a number.',
    'password' => [
        'letters' => 'The :attribute field must contain at least one letter.',
        'mixed' => 'The :attribute field must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute field must contain at least one number.',
        'symbols' => 'The :attribute field must contain at least one symbol.',
        'uncompromised' => ':attribute एक डेटा लीक में पाया गया है। कृपया एक दूसरा :attribute चुनें।',
    ],
    'present' => ':attribute फील्ड मौजूद होना चाहिए।',
    'present_if' => 'The :attribute field must be present when :other is :value.',
    'present_unless' => 'The :attribute field must be present unless :other is :value.',
    'present_with' => 'The :attribute field must be present when :values is present.',
    'present_with_all' => 'The :attribute field must be present when :values are present.',
    'prohibited' => ':attribute फील्ड निषिद्ध है।',
    'prohibited_if' => 'जब :other :value हो तो :attribute फील्ड निषिद्ध है।',
    'prohibited_unless' => 'यदि :other :values में न हो तो :attribute फील्ड निषिद्ध है।',
    'prohibits' => ':attribute फील्ड :other के प्रस्तुत होने को निषेध करता है।',
    'regex' => 'The :attribute field format is invalid.',
    'required' => ':attribute फील्ड की आवश्यकता है।',
    'required_array_keys' => ':attribute फील्ड में :values की प्रविष्टियाँ होनी चाहिए।',
    'required_if' => 'जब :other :value हो तो :attribute फील्ड आवश्यक है।',
    'required_if_accepted' => 'जब :other मान्य हो तो :attribute फील्ड आवश्यक है।',
    'required_if_declined' => 'The :attribute field is required when :other is declined.',
    'required_unless' => 'यदि :other :values में न हो तो :attribute फील्ड आवश्यक है।',
    'required_with' => 'जब :values प्रस्तुत हो तो :attribute फील्ड आवश्यक है।',
    'required_with_all' => 'जब :values प्रस्तुत हो तो :attribute फील्ड आवश्यक है।',
    'required_without' => 'जब :values प्रस्तुत न हो तो :attribute फील्ड आवश्यक है।',
    'required_without_all' => 'जब :values में से कोई भी प्रस्तुत न हो तो :attribute फील्ड आवश्यक है।',
    'same' => 'The :attribute field must match :other.',
    'size' => [
        'array' => 'The :attribute field must contain :size items.',
        'file' => 'The :attribute field must be :size kilobytes.',
        'numeric' => 'The :attribute field must be :size.',
        'string' => 'The :attribute field must be :size characters.',
    ],
    'starts_with' => 'The :attribute field must start with one of the following: :values.',
    'string' => 'The :attribute field must be a string.',
    'timezone' => 'The :attribute field must be a valid timezone.',
    'unique' => ':attribute को पहले ही उपयोग में लिया जा चुका है।',
    'uploaded' => ':attribute अपलोड होने में असफल रहा।',
    'uppercase' => 'The :attribute field must be uppercase.',
    'url' => 'The :attribute field must be a valid URL.',
    'ulid' => 'The :attribute field must be a valid ULID.',
    'uuid' => 'The :attribute field must be a valid UUID.',

    'single' => ':attribute का उपयोग करते समय वह इस request के body में एकमात्र पैरामीटर होना चाहिए',
    'onlyCustomOtpWithUri' => 'URI पैरामीटर अकेले या केवल \'custom_otp\' पैरामीटर के संयोजन में प्रदान किया जाना चाहिए',
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
            'image' => 'jpeg, png, bmp, gif, svg, या webp ही समर्थित प्रारूप हैं',
        ],
        'qrcode' => [
            'image' => 'jpeg, png, bmp, gif, svg, या webp ही समर्थित प्रारूप हैं',
        ],
        'uri' => [
            'regex' => 'The :attribute field is not a valid otpauth uri.',
        ],
        'otp_type' => [
            'in' => 'The :attribute field is not supported.',
        ],
        'email' => [
            'exists' => 'इस ईमेल का कोई अकाउंट नहीं पाया गया।',
            'ComplyWithEmailRestrictionPolicy' => 'यह ईमेल पता रेजिस्ट्रेशन के नियम का पालन नहीं करता है',
            'IsValidEmailList' => 'सभी ईमेल वैध होने चाहिए और पाइप सिम्बल (|) से अलग किए जाने चाहिए'
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
            'required' => 'URI में लेबल होना चाहिए।',
        ],
        'ids' => [
            'regex' => 'IDs को कॉमा से अलग किया जाना चाहिए, बिना अंतिम कॉमा के।',
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
