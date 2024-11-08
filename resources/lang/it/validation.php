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

    'accepted' => ':attribute deve essere accettato.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => ':attribute non è un URL valido.',
    'after' => ':attribute deve essere una data successiva al :date.',
    'after_or_equal' => ':attribute deve essere una data successiva o uguale al :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => ':attribute deve essere un array.',
    'ascii' => 'The :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'before' => ':attribute deve essere una data precedente al :date.',
    'before_or_equal' => ':attribute deve essere una data precedente o uguale al :date.',
    'between' => [
        'array' => ':attribute deve avere tra :min - :max elementi.',
        'file' => ':attribute deve trovarsi tra :min - :max kilobyte.',
        'numeric' => ':attribute deve trovarsi tra :min - :max.',
        'string' => ':attribute deve trovarsi tra :min - :max caratteri.',
    ],
    'boolean' => 'Il campo :attribute deve essere vero o falso.',
    'can' => 'The :attribute field contains an unauthorized value.',
    'confirmed' => 'Il campo di conferma per :attribute non coincide.',
    'contains' => 'The :attribute field is missing a required value.',
    'current_password' => 'The password is incorrect.',
    'date' => ':attribute non è una data valida.',
    'date_equals' => ':attribute deve essere una data e uguale a :date.',
    'date_format' => ':attribute non coincide con il formato :format.',
    'decimal' => 'The :attribute field must have :decimal decimal places.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => ':attribute e :other devono essere differenti.',
    'digits' => ':attribute deve essere di :digits cifre.',
    'digits_between' => ':attribute deve essere tra :min e :max cifre.',
    'dimensions' => 'Le dimensioni dell\'immagine di :attribute non sono valide.',
    'distinct' => ':attribute contiene un valore duplicato.',
    'doesnt_end_with' => 'The :attribute may not end with one of the following: :values.',
    'doesnt_start_with' => 'The :attribute may not start with one of the following: :values.',
    'email' => ':attribute non è valido.',
    'ends_with' => ':attribute deve finire con uno dei seguenti valori: :values',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => ':attribute selezionato non è valido.',
    'extensions' => 'The :attribute field must have one of the following extensions: :values.',
    'file' => ':attribute deve essere un file.',
    'filled' => 'Il campo :attribute deve contenere un valore.',
    'gt' => [
        'array' => ':attribute deve contenere più di :value elementi.',
        'file' => ':attribute deve essere maggiore di :value kilobyte.',
        'numeric' => ':attribute deve essere maggiore di :value.',
        'string' => ':attribute deve contenere più di :value caratteri.',
    ],
    'gte' => [
        'array' => ':attribute deve contenere un numero di elementi uguale o maggiore di :value.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
    ],
    'hex_color' => 'The :attribute field must be a valid hexadecimal color.',
    'image' => ':attribute deve essere un\'immagine.',
    'in' => ':attribute selezionato non è valido.',
    'in_array' => 'Il valore del campo :attribute non esiste in :other.',
    'integer' => ':attribute deve essere un numero intero.',
    'ip' => ':attribute deve essere un indirizzo IP valido.',
    'ipv4' => ':attribute deve essere un indirizzo IPv4 valido.',
    'ipv6' => ':attribute deve essere un indirizzo IPv6 valido.',
    'json' => ':attribute deve essere una stringa JSON valida.',
    'list' => 'The :attribute field must be a list.',
    'lowercase' => 'The :attribute field must be lowercase.',
    'lt' => [
        'array' => ':attribute deve contenere meno di :value elementi.',
        'file' => ':attribute deve essere minore di :value kilobyte.',
        'numeric' => ':attribute deve essere minore di :value.',
        'string' => ':attribute deve contenere meno di :value caratteri.',
    ],
    'lte' => [
        'array' => ':attribute deve contenere un numero di elementi minore o uguale a :value.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'array' => 'The :attribute must not have more than :max items.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'numeric' => 'The :attribute must not be greater than :max.',
        'string' => 'The :attribute must not be greater than :max characters.',
    ],
    'max_digits' => 'The :attribute must not have more than :max digits.',
    'mimes' => ':attribute deve essere del tipo: :values.',
    'mimetypes' => ':attribute deve essere del tipo: :values.',
    'min' => [
        'array' => ':attribute deve avere almeno :min elementi.',
        'file' => ':attribute deve essere almeno di :min kilobyte.',
        'numeric' => ':attribute deve essere almeno :min.',
        'string' => ':attribute deve contenere almeno :min caratteri.',
    ],
    'min_digits' => 'The :attribute must have at least :min digits.',
    'missing' => 'The :attribute field must be missing.',
    'missing_if' => 'The :attribute field must be missing when :other is :value.',
    'missing_unless' => 'The :attribute field must be missing unless :other is :value.',
    'missing_with' => 'The :attribute field must be missing when :values is present.',
    'missing_with_all' => 'The :attribute field must be missing when :values are present.',
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'Il valore selezionato per :attribute non è valido.',
    'not_regex' => 'Il formato di :attribute non è valido.',
    'numeric' => ':attribute deve essere un numero.',
    'password' => [
        'letters' => 'The :attribute must contain at least one letter.',
        'mixed' => 'The :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute must contain at least one number.',
        'symbols' => 'The :attribute must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'Il campo :attribute deve essere presente.',
    'present_if' => 'The :attribute field must be present when :other is :value.',
    'present_unless' => 'The :attribute field must be present unless :other is :value.',
    'present_with' => 'The :attribute field must be present when :values is present.',
    'present_with_all' => 'The :attribute field must be present when :values are present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'Il formato del campo :attribute non è valido.',
    'required' => 'Il campo :attribute è richiesto.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'Il campo :attribute è richiesto quando :other è :value.',
    'required_if_accepted' => 'The :attribute field is required when :other is accepted.',
    'required_if_declined' => 'The :attribute field is required when :other is declined.',
    'required_unless' => 'Il campo :attribute è richiesto a meno che :other sia in :values.',
    'required_with' => 'Il campo :attribute è richiesto quando :values è presente.',
    'required_with_all' => 'Il campo :attribute è richiesto quando :values sono presenti.',
    'required_without' => 'Il campo :attribute è richiesto quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è richiesto quando nessuno di :values è presente.',
    'same' => ':attribute e :other devono coincidere.',
    'size' => [
        'array' => ':attribute deve contenere :size elementi.',
        'file' => ':attribute deve essere :size kilobyte.',
        'numeric' => ':attribute deve essere :size.',
        'string' => ':attribute deve contenere :size caratteri.',
    ],
    'starts_with' => ':attribute deve iniziare con uno dei seguenti: :values',
    'string' => ':attribute deve essere una stringa.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => ':attribute è stato già utilizzato.',
    'uploaded' => ':attribute non è stato caricato.',
    'uppercase' => 'The :attribute field must be uppercase.',
    'url' => 'The :attribute must be a valid URL.',
    'ulid' => 'The :attribute field must be a valid ULID.',
    'uuid' => ':attribute deve essere un UUID valido.',

    'single' => 'When using :attribute it must be the only parameter in this request body',
    'onlyCustomOtpWithUri' => 'The uri parameter must be provided alone or only in combination with the \'custom_otp\' parameter',
    'IsValidRegex' => 'The :attribute must be a valid regex pattern.',

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
            'regex' => 'The :attribute is not a valid otpauth uri.',
        ],
        'otp_type' => [
            'in' => 'The :attribute is not supported.',
        ],
        'email' => [
            'exists' => 'No account found using this email.',
            'ComplyWithEmailRestrictionPolicy' => 'This email address does not comply with the registration policy',
            'IsValidEmailList' => 'All emails must be valid and separated with a pipe'
        ],
        'secret' => [
            'isBase32Encoded' => 'The :attribute must be a base32 encoded string.',
        ],
        'account' => [
            'regex' => 'The :attribute must not contain colon.',
        ],
        'service' => [
            'regex' => 'The :attribute must not contain colon.',
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
