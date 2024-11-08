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

    'accepted' => ':Attribute skal accepteres.',
    'accepted_if' => 'De :attribute skal accepteres, når :other er :value.',
    'active_url' => ':Attribute er ikke en gyldig URL.',
    'after' => ':Attribute skal være en dato efter :date.',
    'after_or_equal' => ':Attribute skal være en dato efter eller lig med :date.',
    'alpha' => ':Attribute må kun bestå af bogstaver.',
    'alpha_dash' => ':Attribute må kun bestå af bogstaver, tal og bindestreger.',
    'alpha_num' => ':Attribute må kun bestå af bogstaver og tal.',
    'array' => ':Attribute skal være et array.',
    'ascii' => ':Attribute må kun indeholde single-byte alfanumeriske tegn og symboler.',
    'before' => ':Attribute skal være en dato før :date.',
    'before_or_equal' => ':Attribute skal være en dato før eller lig med :date.',
    'between' => [
        'array' => ':Attribute skal indeholde mellem :min og :max elementer.',
        'file' => ':Attribute skal være mellem :min og :max kilobytes.',
        'numeric' => ':Attribute skal være mellem :min og :max.',
        'string' => ':Attribute skal være mellem :min og :max tegn.',
    ],
    'boolean' => ':Attribute skal være sand eller falsk.',
    'can' => 'Feltet :attribute indeholder en uautoriseret værdi.',
    'confirmed' => ':Attribute er ikke det samme som bekræftelsesfeltet.',
    'contains' => ':Attribute mangler en påkrævet værdi.',
    'current_password' => 'Adgangskoden er forkert.',
    'date' => ':Attribute er ikke en gyldig dato.',
    'date_equals' => ':Attribute skal være en dato lig med :date.',
    'date_format' => ':Attribute matcher ikke formatet :format.',
    'decimal' => 'De :attribute skal have :decimal decimaler.',
    'declined' => 'De :attribute skal afvises.',
    'declined_if' => 'De :attribute skal afvises, når :other er :value.',
    'different' => ':Attribute og :other skal være forskellige.',
    'digits' => ':Attribute skal have :digits cifre.',
    'digits_between' => ':Attribute skal have mellem :min og :max cifre.',
    'dimensions' => ':Attribute har forkerte billeddimensioner.',
    'distinct' => ':Attribute har en duplikatværdi.',
    'doesnt_end_with' => 'De :attribute slutter muligvis ikke med en af ​​følgende: :values.',
    'doesnt_start_with' => 'De :attribute starter muligvis ikke med en af ​​følgende: :values.',
    'email' => ':Attribute skal være en gyldig e-mailadresse.',
    'ends_with' => ':Attribute skal ende med et af følgende: :values.',
    'enum' => 'De valgte :attribute er ugyldige.',
    'exists' => 'Valgte :attribute er ugyldig.',
    'extensions' => 'Feltet :attribute skal have en af ​​følgende udvidelser: :values.',
    'file' => ':attribute skal være en fil.',
    'filled' => ':Attribute skal udfyldes.',
    'gt' => [
        'array' => ':Attribute skal være mere end :value elementer.',
        'file' => ':Attribute skal være større end :value kilobytes.',
        'numeric' => ':Attribute skal være større end :value.',
        'string' => ':Attribute skal være mere end :value tegn.',
    ],
    'gte' => [
        'array' => ':Attribute skal have :value elementer eller mere.',
        'file' => ':Attribute skal være større end eller lig med :value kilobytes.',
        'numeric' => ':Attribute skal være større end eller lig med :value.',
        'string' => ':Attribute skal være mere end eller lig med :value tegn.',
    ],
    'hex_color' => 'Feltet :attribute skal være en gyldig hexadecimal farve.',
    'image' => ':Attribute skal være et billede.',
    'in' => 'Valgte :attribute er ugyldig.',
    'in_array' => ':Attribute eksisterer ikke i :other.',
    'integer' => ':Attribute skal være et heltal.',
    'ip' => ':Attribute skal være en gyldig IP adresse.',
    'ipv4' => ':Attribute skal være en gyldig IPv4 adresse.',
    'ipv6' => ':Attribute skal være en gyldig IPv6 adresse.',
    'json' => ':Attribute skal være en gyldig JSON streng.',
    'list' => 'Feltet :attribute skal være en liste.',
    'lowercase' => ':Attribute skal være små bogstaver.',
    'lt' => [
        'array' => ':Attribute skal have mindre end :value items.',
        'file' => ':Attribute skal være mindre end :value kilobytes.',
        'numeric' => ':Attribute skal være mindre end :value.',
        'string' => ':Attribute skal være mindre end :value tegn.',
    ],
    'lte' => [
        'array' => ':Attribute må ikke have mere end :value elementer.',
        'file' => ':Attribute skal være mindre eller lig med :value kilobytes.',
        'numeric' => ':Attribute skal være mindre eller lig med :value.',
        'string' => ':Attribute skal være mindre eller lig med :value tegn.',
    ],
    'mac_address' => ':Attribute skal være en gyldig MAC-adresse.',
    'max' => [
        'array' => ':Attribute må ikke indeholde mere end :max elementer.',
        'file' => ':Attribute må ikke være større end :max kilobytes.',
        'numeric' => ':Attribute må ikke være større end :max.',
        'string' => ':Attribute må ikke være mere end :max tegn.',
    ],
    'max_digits' => 'De :attribute må ikke have mere end :max cifre.',
    'mimes' => ':Attribute skal være en fil af typen: :values.',
    'mimetypes' => ':Attribute skal være en fil af typen: :values.',
    'min' => [
        'array' => ':Attribute skal indeholde mindst :min elementer.',
        'file' => ':Attribute skal være mindst :min kilobytes.',
        'numeric' => ':Attribute skal være mindst :min.',
        'string' => ':Attribute skal være mindst :min tegn.',
    ],
    'min_digits' => ':Attribute skal have mindst :min cifre.',
    'missing' => ':Attribute-feltet skal mangle.',
    'missing_if' => ':Attribute-feltet skal mangle, når :other er :value.',
    'missing_unless' => ':Attribute-feltet skal mangle, medmindre :other er :value.',
    'missing_with' => ':Attribute-feltet skal mangle, når :values er til stede.',
    'missing_with_all' => ':Attribute-feltet skal mangle, når :values er til stede.',
    'multiple_of' => ':Attribute skal være et multiplum af :value',
    'not_in' => 'Valgte :attribute er ugyldig.',
    'not_regex' => 'Formatet for :attribute er ugyldigt.',
    'numeric' => ':Attribute skal være et tal.',
    'password' => [
        'letters' => 'De :attribute skal indeholde mindst ét ​​bogstav.',
        'mixed' => 'De :attribute skal indeholde mindst et stort og et lille bogstav.',
        'numbers' => 'De :attribute skal indeholde mindst ét ​​tal.',
        'symbols' => 'De :attribute skal indeholde mindst ét ​​symbol.',
        'uncompromised' => 'De givne :attribute er dukket op i et datalæk. Vælg venligst en anden :attribute.',
    ],
    'present' => ':Attribute skal være tilstede.',
    'present_if' => 'Feltet :attribute skal være til stede, når :other er :value.',
    'present_unless' => 'Feltet :attribute skal være til stede, medmindre :other er :value.',
    'present_with' => ':Attribute-feltet skal være til stede, når :values er til stede.',
    'present_with_all' => ':Attribute-feltet skal være til stede, når :values er til stede.',
    'prohibited' => ':Attribute-feltet er forbudt.',
    'prohibited_if' => 'Feltet :attribute er forbudt, når :other er :value.',
    'prohibited_unless' => ':Attribute-feltet er forbudt, medmindre :other er i :values.',
    'prohibits' => ':Attribute-feltet forbyder :other at være til stede.',
    'regex' => ':Attribute formatet er ugyldigt.',
    'required' => ':Attribute skal udfyldes.',
    'required_array_keys' => 'Feltet :attribute skal indeholde poster for: :values.',
    'required_if' => ':Attribute skal udfyldes når :other er :value.',
    'required_if_accepted' => ':Attribute-feltet er påkrævet, når :other accepteres.',
    'required_if_declined' => 'Feltet :attribute er påkrævet, når :other afvises.',
    'required_unless' => ':Attribute er påkrævet med mindre :other findes i :values.',
    'required_with' => ':Attribute skal udfyldes når :values er udfyldt.',
    'required_with_all' => ':Attribute skal udfyldes når :values er udfyldt.',
    'required_without' => ':Attribute skal udfyldes når :values ikke er udfyldt.',
    'required_without_all' => ':Attribute skal udfyldes når ingen af :values er udfyldt.',
    'same' => ':Attribute og :other skal være ens.',
    'size' => [
        'array' => ':Attribute skal indeholde :size elementer.',
        'file' => ':Attribute skal være :size kilobytes.',
        'numeric' => ':Attribute skal være :size.',
        'string' => ':Attribute skal være :size tegn lang.',
    ],
    'starts_with' => ':Attribute skal starte med én af følgende: :values.',
    'string' => ':Attribute skal være en streng.',
    'timezone' => ':Attribute skal være en gyldig tidszone.',
    'unique' => ':Attribute er allerede taget.',
    'uploaded' => ':Attribute fejlede i upload.',
    'uppercase' => 'De :attribute skal være store bogstaver.',
    'url' => ':Attribute formatet er ugyldigt.',
    'ulid' => ':attribute skal være et gyldigt ULID.',
    'uuid' => ':Attribute skal være en gyldig UUID.',

    'single' => 'Når du bruger :attribut skal det være den eneste parameter',
    'onlyCustomOtpWithUri' => 'uri-parameteren skal leveres alene eller kun i kombination med \'custom_otp\'-parameteren',
    'IsValidRegex' => 'Attributfeltet skal være et gyldigt regex.',

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
            'image' => 'Understøttet format er jpeg, png, bmp, gif, svg eller webp.',
        ],
        'qrcode' => [
            'image' => 'Understøttet format er jpeg, png, bmp, gif, svg eller webp.',
        ],
        'uri' => [
            'regex' => 'Attributfeltet er ikke en gyldig otpauth uri.',
        ],
        'otp_type' => [
            'in' => 'Attributfeltet understøttes ikke.',
        ],
        'email' => [
            'exists' => 'Ingen konto fundet med denne e-mail.',
            'ComplyWithEmailRestrictionPolicy' => 'Denne e-mailadresse overholder ikke registreringspolitikken',
            'IsValidEmailList' => 'Alle e-mails skal være gyldige og adskilt med et pipesymbol'
        ],
        'secret' => [
            'isBase32Encoded' => 'Attributfeltet skal være en base32 kodet streng.',
        ],
        'account' => [
            'regex' => 'Attributfeltet må ikke indeholde kolon.',
        ],
        'service' => [
            'regex' => 'Attributfeltet må ikke indeholde kolon.',
        ],
        'label' => [
            'required' => 'uri skal have en etiket.',
        ],
        'ids' => [
            'regex' => 'ID\'er skal være kommaseparerede, uden efterfølgende komma.',
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
