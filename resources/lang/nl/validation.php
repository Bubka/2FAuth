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

    'accepted' => 'Het :attribute moet geaccepteerd worden.',
    'accepted_if' => 'Het :attribute moet worden geaccepteerd als :other :value is.',
    'active_url' => 'Het :attribute is geen geldige URL.',
    'after' => 'Het :attribute moet een datum na :date zijn.',
    'after_or_equal' => 'Het :attribute moet een datum na of gelijk aan :date zijn.',
    'alpha' => 'Het :attribute mag alleen letters bevatten.',
    'alpha_dash' => 'Het :attribute mag alleen letters, cijfers, streepjes en underscores bevatten.',
    'alpha_num' => 'Het :attribute mag alleen letters en nummers bevatten.',
    'array' => 'Het :attribute moet een reeks zijn.',
    'before' => 'Het :attribute moet een datum vóór :date zijn.',
    'before_or_equal' => 'Het :attribute moet een datum zijn voor of gelijk aan :date.',
    'between' => [
        'array' => 'Het :attribute moet tenminste :min en :max items bevatten.',
        'file' => 'Het :attribute moet tussen de :min en :max kilobytes zijn.',
        'numeric' => 'Het :attribute moet tussen :min en :max zijn.',
        'string' => 'Het :attribute moet tussen :min en :max karakters zijn.',
    ],
    'boolean' => 'Het :attribute moet ja of nee zijn.',
    'confirmed' => ':attribute bevestiging komt niet overeen.',
    'current_password' => 'Het wachtwoord is onjuist.',
    'date' => 'Het :attribute is geen geldige datum.',
    'date_equals' => 'Het :attribute moet een datum gelijk aan :date zijn.',
    'date_format' => 'Het :attribute komt niet overeen met het formaat :format.',
    'declined' => 'Het :attribuut moet worden afgewezen.',
    'declined_if' => 'Het :attribute moet worden afgewezen als :other :value is.',
    'different' => 'Het :attribute en :other moeten verschillend zijn.',
    'digits' => 'Het :attribute moet bestaan uit :digits cijfers.',
    'digits_between' => 'Het :attribute moet bestaan uit minimaal :min en maximaal :max cijfers.',
    'dimensions' => 'Het :attribute heeft ongeldige afbeeldings dimensies.',
    'distinct' => ':attribute veld heeft een reeds bestaande waarde.',
    'doesnt_end_with' => 'Het :attribute mag niet eindigen met een van de volgende: :values.',
    'doesnt_start_with' => 'Het :attribute mag niet beginnen met een van de volgende: :values.',
    'email' => 'Het :attribute moet een geldig e-mailadres zijn.',
    'ends_with' => 'Het :attribute moet eindigen met een van de volgende: :values.',
    'enum' => 'Het geselecteerde :attribute is ongeldig.',
    'exists' => 'Het geselecteerde :attribute is ongeldig.',
    'file' => 'Het :attribute moet een bestand zijn.',
    'filled' => 'Het veld :attribute moet een waarde bevatten.',
    'gt' => [
        'array' => 'Het :attribute moet meer dan :value waardes bevatten.',
        'file' => 'Het :attribute moet groter zijn dan :value kilobytes.',
        'numeric' => 'Het :attribute moet groter zijn dan :value.',
        'string' => 'Het :attribute moet groter zijn dan de :value tekens.',
    ],
    'gte' => [
        'array' => 'Het :attribute moet :value items of meer hebben.',
        'file' => 'De :attribute moet groter of gelijk zijn aan :value kilobytes.',
        'numeric' => 'De :attribute moet groter of gelijk zijn aan :value.',
        'string' => 'De :attribute moet minimaal :value tekens bevatten.',
    ],
    'image' => 'De :attribute moet een afbeelding zijn.',
    'in' => 'De geselecteerde :attribute is ongeldig.',
    'in_array' => 'Het :attribute veld bestaat niet in :other.',
    'integer' => 'Het :attribute moet van het type integer zijn.',
    'ip' => 'Het :attribute moet een geldig IP-adres zijn.',
    'ipv4' => 'Het :attribute moet een geldig IPv4 adres zijn.',
    'ipv6' => 'Het :attribute moet een geldig IPv6 adres zijn.',
    'json' => 'Het :attribute moet een geldige JSON string zijn.',
    'lt' => [
        'array' => 'Het :attribute moet minder dan :value items hebben.',
        'file' => 'Het :attribute moet kleiner zijn dan :value kilobytes.',
        'numeric' => 'Het :attribute moet kleiner zijn dan :value.',
        'string' => 'Het :attribute moet groter zijn dan de :value tekens.',
    ],
    'lte' => [
        'array' => 'Het :attribute mag niet meer dan :value items hebben.',
        'file' => 'Het :attribute moet kleiner zijn dan of gelijk zijn aan :value kilobytes.',
        'numeric' => 'Het :attribute moet kleiner zijn dan of gelijk zijn aan :value.',
        'string' => 'Het :attribute moet kleiner zijn dan of gelijk aan :value tekens.',
    ],
    'mac_address' => 'Het :attribute moet een geldig MAC-adres zijn.',
    'max' => [
        'array' => 'Het :attribute mag niet meer dan :max items hebben.',
        'file' => 'Het :attribute mag niet groter zijn dan :max kilobytes.',
        'numeric' => 'Het :attribute mag niet groter zijn dan :max.',
        'string' => 'Het :attribute mag niet groter zijn dan de :max tekens.',
    ],
    'max_digits' => 'Het :attribute mag niet meer dan :max cijfers hebben.',
    'mimes' => 'Het :attribute moet een bestand zijn van het type: :values.',
    'mimetypes' => 'Het :attribute moet een bestand zijn van het type: :values.',
    'min' => [
        'array' => 'Het :attribute moet ten minste :min items hebben.',
        'file' => 'Het :attribute moet ten minste :min kilobytes zijn.',
        'numeric' => 'Het :attribute moet ten minste :min items hebben.',
        'string' => 'Het :attribute moet ten minste :min karakters zijn.',
    ],
    'min_digits' => 'Het :attribute moet ten minste :min cijfers hebben.',
    'multiple_of' => 'Het :attribute moet een meervoud zijn van :value.',
    'not_in' => 'Het geselecteerde :attribute is ongeldig.',
    'not_regex' => 'Het formaat van :attribute is ongeldig.',
    'numeric' => 'Het :attribute moet een cijfer zijn.',
    'password' => [
        'letters' => 'Het :attribute moet minimaal één letter bevatten.',
        'mixed' => 'Het :attribute moet ten minste één hoofdletter en één kleine letter bevatten.',
        'numbers' => 'Het :attribute moet ten minste één getal bevatten.',
        'symbols' => 'Het :attribute moet minimaal één symbool bevatten.',
        'uncompromised' => 'Het opgegeven :attribute komt voor in een datalek. Kies een ander :attribute.',
    ],
    'present' => 'Het :attribute veld moet aanwezig zijn.',
    'prohibited' => 'Het :attribute veld is verboden.',
    'prohibited_if' => 'Het :attribute veld is verboden als :other is :value.',
    'prohibited_unless' => 'Het :attribute veld is verboden tenzij :other is in :values.',
    'prohibits' => 'Het :attribute veld verbiedt de aanwezigheid van :other.',
    'regex' => 'Het formaat van :attribute is ongeldig.',
    'required' => 'Het :attribute veld is verplicht.',
    'required_array_keys' => 'Het :attribute veld moet items bevatten voor: :values.',
    'required_if' => 'Het veld :attribute is verplicht als :other gelijk is aan :value.',
    'required_if_accepted' => 'Het :attribute veld is verplicht wanneer :other is geaccepteerd.',
    'required_unless' => 'Het :attribute veld is verplicht tenzij :other is in :values.',
    'required_with' => 'Het veld :attribute is verplicht als :values ingesteld staan.',
    'required_with_all' => 'Het :attribute veld is verplicht als :values aanwezig zijn.',
    'required_without' => 'Het veld :attribute is verplicht als :values niet ingesteld staan.',
    'required_without_all' => 'Het :attribute veld is verplicht wanneer geen van de :values aanwezig zijn.',
    'same' => 'Het :attribute en :other moeten gelijk zijn.',
    'size' => [
        'array' => 'Het :attribute moet :size items bevatten.',
        'file' => 'Het :attribute moet ten minste :min kilobytes zijn.',
        'numeric' => 'Het :attribute moet :size zijn.',
        'string' => 'Het :attribute moet zijn :size karakters zijn.',
    ],
    'starts_with' => 'Het :attribute moet beginnen met een van de volgende: :values.',
    'string' => 'Het :attribute moet een tekenreeks zijn.',
    'timezone' => 'Het :attribute moet een geldige tijdzone zijn.',
    'unique' => 'Het :attribute is al in gebruik.',
    'uploaded' => 'Het uploaden van :attribute is mislukt.',
    'url' => 'Het :attribute moet een geldig URL zijn.',
    'uuid' => 'Het :attribute moet een geldig UUID zijn.',

    'single' => 'Bij gebruik van :attribute moet het de enige parameter zijn in de body van de aanvraag',
    'onlyCustomOtpWithUri' => 'De uri parameter moet alleen of alleen worden verstrekt in combinatie met de \'custom_otp\' parameter',
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
            'image' => 'Ondersteunde formaat zijn jpeg, png, bmp, gif, svg of webp.',
        ],
        'qrcode' => [
            'image' => 'Ondersteunde formaat zijn jpeg, png, bmp, gif, svg of webp.',
        ],
        'uri' => [
            'regex' => 'Het :attribute is geen geldige otpauth uri.',
        ],
        'otp_type' => [
            'in' => 'Het :attribute wordt niet ondersteund.',
        ],
        'email' => [
            'exists' => 'Geen account gevonden met dit e-mailadres.',
            'ComplyWithEmailRestrictionPolicy' => 'Dit e-mailadres voldoet niet aan het registratie beleid',
            'IsValidEmailList' => 'Alle e-mailadressen moeten geldig zijn en gescheiden worden door een buis'
        ],
        'secret' => [
            'isBase32Encoded' => 'Het :attribute moet een base32 gecodeerde tekenreeks zijn.',
        ],
        'account' => [
            'regex' => 'Het :attribute mag leestekens bevatten.',
        ],
        'service' => [
            'regex' => 'Het :attribute mag geen leestekens bevatten.',
        ],
        'label' => [
            'required' => 'De uri moet een label hebben.',
        ],
        'ids' => [
            'regex' => 'ID\'s moeten met een komma gescheiden, zonder een komma.',
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
