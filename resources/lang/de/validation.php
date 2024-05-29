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

    'accepted' => ':attribute muss akzeptiert werden.',
    'accepted_if' => 'Das :attribute muss akzeptiert werden, wenn :other :value ist.',
    'active_url' => ':attribute ist keine gültige Internet-Adresse.',
    'after' => ':attribute muss ein Datum nach dem :date sein.',
    'after_or_equal' => ':attribute muss ein Datum nach dem :date oder gleich dem :date sein.',
    'alpha' => 'Das :attribute Feld darf nur Buchstaben enthalten.',
    'alpha_dash' => 'Das :attribute Feld darf nur Buchstaben, Ziffern, Bindestriche und Unterstriche enthalten.',
    'alpha_num' => 'Das :attribute Feld darf nur aus Zeichen und Nummern bestehen.',
    'array' => ':attribute muss ein Array sein.',
    'before' => ':attribute muss ein Datum vor dem :date sein.',
    'before_or_equal' => ':attribute muss ein Datum vor dem :date oder gleich dem :date sein.',
    'between' => [
        'array' => ':attribute muss zwischen :min & :max Elemente haben.',
        'file' => ':attribute muss zwischen :min & :max Kilobytes groß sein.',
        'numeric' => ':attribute muss zwischen :min & :max liegen.',
        'string' => ':attribute muss zwischen :min & :max Zeichen lang sein.',
    ],
    'boolean' => ':attribute muss entweder \'true\' oder \'false\' sein.',
    'confirmed' => ':attribute stimmt nicht mit der Bestätigung überein.',
    'current_password' => 'Das Passwort ist falsch.',
    'date' => ':attribute muss ein gültiges Datum sein.',
    'date_equals' => ':attribute muss ein Datum gleich :date sein.',
    'date_format' => ':attribute entspricht nicht dem gültigen Format für :format.',
    'declined' => 'Das :attribute muss abgelehnt werden.',
    'declined_if' => 'Das :attribute muss abgelehnt werden, wenn :other :value ist.',
    'different' => ':attribute und :other müssen sich unterscheiden.',
    'digits' => ':attribute muss :digits Stellen haben.',
    'digits_between' => ':attribute muss zwischen :min und :max Stellen haben.',
    'dimensions' => ':attribute hat ungültige Bildabmessungen.',
    'distinct' => ':attribute beinhaltet einen bereits vorhandenen Wert.',
    'doesnt_end_with' => 'Das :attribute darf nicht mit einem der folgenden Werte enden: :values.',
    'doesnt_start_with' => 'Das :attribute Feld darf nicht mit einem der folgenden Werte beginnen: :values.',
    'email' => ':attribute muss eine gültige E-Mail-Adresse sein.',
    'ends_with' => ':attribute muss eine der folgenden Endungen aufweisen: :values',
    'enum' => 'Der gewählte Wert für :attribute ist ungültig.',
    'exists' => 'Der gewählte Wert für :attribute ist ungültig.',
    'file' => ':attribute muss eine Datei sein.',
    'filled' => ':attribute muss ausgefüllt sein.',
    'gt' => [
        'array' => ':attribute muss mehr als :value Elemente haben.',
        'file' => ':attribute muss größer als :value Kilobytes sein.',
        'numeric' => ':attribute muss größer als :value sein.',
        'string' => ':attribute muss länger als :value Zeichen sein.',
    ],
    'gte' => [
        'array' => ':attribute muss mindestens :value Elemente haben.',
        'file' => 'Das :attribute muss größer oder gleich :value Kilobytes groß sein.',
        'numeric' => 'Das :attribute Feld muss größer oder gleich :value sein.',
        'string' => 'Das :attribute Feld muss mindestens :value Zeichen enthalten.',
    ],
    'image' => ':attribute muss ein Bild sein.',
    'in' => 'Der gewählte Wert für :attribute ist ungültig.',
    'in_array' => 'Der gewählte Wert für :attribute kommt nicht in :other vor.',
    'integer' => ':attribute muss eine ganze Zahl sein.',
    'ip' => ':attribute muss eine gültige IP-Adresse sein.',
    'ipv4' => ':attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6' => ':attribute muss eine gültige IPv6-Adresse sein.',
    'json' => ':attribute muss ein gültiger JSON-String sein.',
    'lt' => [
        'array' => ':attribute muss weniger als :value Elemente haben.',
        'file' => ':attribute muss kleiner als :value Kilobytes sein.',
        'numeric' => ':attribute muss kleiner als :value sein.',
        'string' => ':attribute muss kürzer als :value Zeichen sein.',
    ],
    'lte' => [
        'array' => ':attribute darf maximal :value Elemente haben.',
        'file' => 'Das :attribute muss kleiner oder gleich :value Kilobytes groß sein.',
        'numeric' => 'Das :attribute Feld muss kleiner oder gleich :value sein.',
        'string' => 'Das :attribute Feld darf maximal :value Zeichen enthalten.',
    ],
    'mac_address' => 'Das :attribute Feld muss eine gültige MAC-Adresse enthalten.',
    'max' => [
        'array' => 'Das :attribute Feld darf nicht mehr als :max Elemente enthalten.',
        'file' => 'Das :attribute darf nicht größer als :max Kilobytes groß sein.',
        'numeric' => 'Das :attribute Feld darf nicht größer als :max sein.',
        'string' => 'Das :attribute Feld darf nicht mehr als :value Zeichen enthalten.',
    ],
    'max_digits' => 'Das :attribute Feld darf nicht mehr als :max Ziffern enthalten.',
    'mimes' => ':attribute muss den Dateityp :values haben.',
    'mimetypes' => ':attribute muss den Dateityp :values haben.',
    'min' => [
        'array' => ':attribute muss mindestens :min Elemente haben.',
        'file' => ':attribute muss mindestens :min Kilobytes groß sein.',
        'numeric' => ':attribute muss mindestens :min sein.',
        'string' => ':attribute muss mindestens :min Zeichen lang sein.',
    ],
    'min_digits' => 'Das :attribute Feld muss mindestens :min Ziffern enthalten.',
    'multiple_of' => 'Das :attribute Feld muss ein Vielfaches von :value sein.',
    'not_in' => 'Der gewählte Wert für :attribute ist ungültig.',
    'not_regex' => ':attribute hat ein ungültiges Format.',
    'numeric' => ':attribute muss eine Zahl sein.',
    'password' => [
        'letters' => 'Das :attribute Feld muss mindestens einen Buchstaben enthalten.',
        'mixed' => 'Das :attribute Feld muss mindestens einen Groß- und einen Kleinbuchstaben enthalten.',
        'numbers' => 'Das :attribute Feld muss mindestens eine Zahl enthalten.',
        'symbols' => 'Das :attribute Feld muss mindestens ein Symbol enthalten.',
        'uncompromised' => 'Der im :attribute Feld angegebene Wert wurde in einem Datenleck gefunden. Bitte geben Sie für :attribute einen anderen Wert ein.',
    ],
    'present' => ':attribute muss vorhanden sein.',
    'prohibited' => 'Das :attribute Feld ist unzulässig.',
    'prohibited_if' => 'Das :attribute Feld ist unzulässig, wenn :other den Wert :value entspricht.',
    'prohibited_unless' => 'Das :attribute Feld ist unzulässig, wenn :other nicht den Wert :value annimmt.',
    'prohibits' => 'Bei gegebenem :attribute Feld ist :other nicht zulässig.',
    'regex' => ':attribute Format ist ungültig.',
    'required' => ':attribute muss ausgefüllt werden.',
    'required_array_keys' => 'Das Feld :attribute muss Einträge enthalten für: :values.',
    'required_if' => ':attribute muss ausgefüllt werden, wenn :other den Wert :value hat.',
    'required_if_accepted' => 'Das Feld :attribute muss ausgefüllt werden, wenn :other ausgefüllt wurde.',
    'required_unless' => ':attribute muss ausgefüllt werden, wenn :other nicht den Wert :values hat.',
    'required_with' => ':attribute muss ausgefüllt werden, wenn :values ausgefüllt wurde.',
    'required_with_all' => ':attribute muss ausgefüllt werden, wenn :values ausgefüllt wurde.',
    'required_without' => ':attribute muss ausgefüllt werden, wenn :values nicht ausgefüllt wurde.',
    'required_without_all' => ':attribute muss ausgefüllt werden, wenn keines der Felder :values ausgefüllt wurde.',
    'same' => ':attribute und :other müssen übereinstimmen.',
    'size' => [
        'array' => ':attribute muss genau :size Elemente haben.',
        'file' => ':attribute muss :size Kilobyte groß sein.',
        'numeric' => ':attribute muss gleich :size sein.',
        'string' => ':attribute muss :size Zeichen lang sein.',
    ],
    'starts_with' => ':attribute muss mit einem der folgenden Anfänge aufweisen: :values',
    'string' => ':attribute muss ein String sein.',
    'timezone' => 'Das :attribute Feld muss eine gültige Zeitzone sein.',
    'unique' => ':attribute ist bereits vergeben.',
    'uploaded' => ':attribute konnte nicht hochgeladen werden.',
    'url' => 'Das :attribute Feld muss eine gültige URL aufweisen.',
    'uuid' => ':attribute muss ein UUID sein.',

    'single' => 'Bei Verwendung von :attribute muss es der einzige Parameter in diesem Anfragetext sein',
    'onlyCustomOtpWithUri' => 'Der uri Parameter muss allein oder nur in Kombination mit dem \'custom_otp\' Parameter angegeben werden',

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
            'image' => 'Unterstützte Formate sind jpeg, png, bmp, gif, svg oder webp.',
        ],
        'qrcode' => [
            'image' => 'Unterstützte Formate sind jpeg, png, bmp, gif, svg oder webp.',
        ],
        'uri' => [
            'regex' => 'Das :attribute Feld enthält kein gültiges otpauth uri.',
        ],
        'otp_type' => [
            'in' => 'Das :attribute Feld wird nicht unterstützt.',
        ],
        'email' => [
            'exists' => 'Kein Konto mit dieser E-Mail gefunden.',
            'ComplyWithEmailRestrictionPolicy' => 'Diese E-Mail-Adresse entspricht nicht den Registrierungsrichtlinien',
            'IsValidEmailList' => 'Alle E-Mails müssen gültig und durch eine Pipe getrennt sein'
        ],
        'secret' => [
            'isBase32Encoded' => 'Das :attribute Feld muss einen Base32 kodierten String enthalten.',
        ],
        'account' => [
            'regex' => 'Das :attribute Feld darf keinen Doppelpunkt enthalten.',
        ],
        'service' => [
            'regex' => 'Das :attribute Feld darf keinen Doppelpunkt enthalten.',
        ],
        'label' => [
            'required' => 'Der URI muss ein Label haben.',
        ],
        'ids' => [
            'regex' => 'IDs müssen durch Komma getrennt werden, ohne am Ende Komma.',
        ]
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
