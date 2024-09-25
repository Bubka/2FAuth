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

    'accepted' => ':attribute को स्वीकार करना होगा।',
    'accepted_if' => 'जब :other :value हो तो :attribute को स्वीकार करना होगा।',
    'active_url' => ':attribute एक वैध URL नहीं है',
    'after' => ':attribute :date के बाद की तारीख होनी चाहिए।',
    'after_or_equal' => ':attribute :date के बाद की या उस के बराबर की तारीख होनी चाहिए।',
    'alpha' => ':attribute में केवल अक्षर होने चाहिए',
    'alpha_dash' => ':attribute में केवल अक्षर, संख्याएँ, डैश और अंडरस्कोर होने चाहिए।',
    'alpha_num' => ':attribute में केवल अक्षर और संख्याएँ होने चाहिए।',
    'array' => ':attribute एक सरणी होनी चाहिए।',
    'before' => ':attribute :date के पहले की तारीख होनी चाहिए।',
    'before_or_equal' => ':attribute :date के पहले की या बराबर की तारीख होनी चाहिए।',
    'between' => [
        'array' => ':attibute :min और :max आइटम के बीच होनी चाहिए।',
        'file' => ':attibute :min और :max किलोबाइट्स के बीच होनी चाहिए।',
        'numeric' => ':attibute :min और :max के बीच होनी चाहिए।',
        'string' => ':attibute :min और :max अक्षरों के बीच होनी चाहिए।',
    ],
    'boolean' => ':attribute फ़ील्ड सही या गलत होनी चाहिए।',
    'confirmed' => ':attribute पुष्टिकरण मेल नहीं खाता।',
    'current_password' => 'पासवर्ड गलत है।',
    'date' => ':attribute एक वैध तारीख नहीं है',
    'date_equals' => ':attribute :date के बराबर तारीख होनी चाहिए',
    'date_format' => ':attribute :format प्रारूप से मेल नहीं खाती।',
    'declined' => ':attribute को अस्वीकार किया जाना चाहिए।',
    'declined_if' => 'जब :other :value हो तो :attribute को अस्वीकार किया जाना चाहिए।',
    'different' => ':attribute और :other भिन्न होने चाहिए।',
    'digits' => ':attribute :digits अंकों का होना चाहिए।',
    'digits_between' => ':attibute :min और :max अंकों के बीच होना चाहिए।',
    'dimensions' => ':attribute के छवि आयाम मान्य नहीं हैं।',
    'distinct' => ':attribute फील्ड में डुप्लिकेट मान है।',
    'doesnt_end_with' => ':attribute निम्नलिखित में से किसी एक के साथ समाप्त नहीं हो सकता: :मान।',
    'doesnt_start_with' => ':attribute निम्नलिखित में से किसी एक के साथ शुरू नहीं हो सकता: :values।',
    'email' => ':attribute एक वैध ईमेल अड्रेस होना चाहिए।',
    'ends_with' => ':attribute निम्नलिखित में से किसी एक के साथ समाप्त होना चाहिए: :values।',
    'enum' => 'चुना गया :attribute वैध नहीं है।',
    'exists' => 'चुना गया :attribute वैध नहीं है।',
    'file' => ':attribute एक फ़ाइल होनी चाहिए।',
    'filled' => ':attribute के स्थान में एक मान होना चाहिए।',
    'gt' => [
        'array' => ':attribute में :value से अधिक चीजें होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से बड़ा होना चाहिए।',
        'numeric' => ':attribute :value से बड़ा होना चाहिए।',
        'string' => ':attribute :value अक्षरों से बड़ा होना चाहिए।',
    ],
    'gte' => [
        'array' => ':attribute में :value या उससे अधिक चीजें होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से बड़ा या बराबर होना चाहिए।',
        'numeric' => ':attribute :value से बड़ा या बराबर होना चाहिए।',
        'string' => ':attribute :value अक्षरों से बड़ा या बराबर होना चाहिए।',
    ],
    'image' => ':attribute एक छवि होनी चाहिए।',
    'in' => 'चुना गया :attribute वैध नहीं है।',
    'in_array' => ':attribute फील्ड, :other में मौजूद नहीं है।',
    'integer' => ':attribute एक पूर्णांक(integer) होना चाहिए।',
    'ip' => ':attribute एक मान्य IP पता होना चाहिए।',
    'ipv4' => ':attribute एक मान्य IPv4 पता होना चाहिए।',
    'ipv6' => ':attribute एक मान्य IPv6 पता होना चाहिए।',
    'json' => ':attribute एक मान्य JSON स्ट्रिंग होना चाहिए।',
    'lt' => [
        'array' => ':attribute में :value से कम चीजें होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से कम होना चाहिए।',
        'numeric' => ':attribute :value से कम होना चाहिए।',
        'string' => ':attribute :value अक्षरों से कम होना चाहिए।',
    ],
    'lte' => [
        'array' => ':attribute में :value से अधिक चीजें नहीं होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से कम या बराबर होना चाहिए।',
        'numeric' => ':attribute :value से कम या बराबर होना चाहिए।',
        'string' => ':attribute :value अक्षरों से कम या बराबर होना चाहिए।',
    ],
    'mac_address' => ':attribute एक मान्य MAC पता होना चाहिए।',
    'max' => [
        'array' => ':attribute में :max से ज्यादा चीजें नहीं हो सकती हैं।',
        'file' => ':attribute :max किलोबाइट से बड़ा नहीं हो सकता है।',
        'numeric' => ':attribute :max से बड़ा नहीं होना चाहिए।',
        'string' => ':attribute :max अक्षरों से बड़ा नहीं हो सकता है।',
    ],
    'max_digits' => ':attribute में :max से ज्यादा अंक नहीं हो सकते हैं।',
    'mimes' => ':attribute :values टाइप की फाइल होनी चाहिए।',
    'mimetypes' => ':attribute :values टाइप की फाइल होनी चाहिए।',
    'min' => [
        'array' => ':attribute में कम से कम :min चीजें होनी चाहिए।',
        'file' => ':attribute कम से कम :min किलोबाइट का होना चाहिए।',
        'numeric' => ':attribute कम से कम :min होना चाहिए।',
        'string' => ':attribute में कम से कम :min अक्षर होने चाहिए।',
    ],
    'min_digits' => ':attribute में कम से कम :min अंक होने चाहिए।',
    'multiple_of' => ':attribute :values का मल्टिपल होना चाहिए।',
    'not_in' => 'चुना गया :attribute वैध नहीं है।',
    'not_regex' => ':attribute फॉर्मेट अमान्य है।',
    'numeric' => ':attribute एक संख्या होनी चाहिए।',
    'password' => [
        'letters' => ':attribute में कम से कम एक अक्षर होना चाहिए।',
        'mixed' => ':attribute में कम से कम एक uppercase और एक lowecase अक्षर होना चाहिए।',
        'numbers' => ':attribute में कम से कम एक अंक होना चाहिए।',
        'symbols' => ':attribute में कम से कम एक सिम्बल होना चाहिए।',
        'uncompromised' => ':attribute एक डेटा लीक में पाया गया है। कृपया एक दूसरा :attribute चुनें।',
    ],
    'present' => ':attribute फील्ड मौजूद होना चाहिए।',
    'prohibited' => ':attribute फील्ड निषिद्ध है।',
    'prohibited_if' => 'जब :other :value हो तो :attribute फील्ड निषिद्ध है।',
    'prohibited_unless' => 'यदि :other :values में न हो तो :attribute फील्ड निषिद्ध है।',
    'prohibits' => ':attribute फील्ड :other के प्रस्तुत होने को निषेध करता है।',
    'regex' => ':attribute का फॉर्मैट अवैध है।',
    'required' => ':attribute फील्ड की आवश्यकता है।',
    'required_array_keys' => ':attribute फील्ड में :values की प्रविष्टियाँ होनी चाहिए।',
    'required_if' => 'जब :other :value हो तो :attribute फील्ड आवश्यक है।',
    'required_if_accepted' => 'जब :other मान्य हो तो :attribute फील्ड आवश्यक है।',
    'required_unless' => 'यदि :other :values में न हो तो :attribute फील्ड आवश्यक है।',
    'required_with' => 'जब :values प्रस्तुत हो तो :attribute फील्ड आवश्यक है।',
    'required_with_all' => 'जब :values प्रस्तुत हो तो :attribute फील्ड आवश्यक है।',
    'required_without' => 'जब :values प्रस्तुत न हो तो :attribute फील्ड आवश्यक है।',
    'required_without_all' => 'जब :values में से कोई भी प्रस्तुत न हो तो :attribute फील्ड आवश्यक है।',
    'same' => ':attribute और :other मैच करना चाहिए।',
    'size' => [
        'array' => ':attribute में :size आइटम होने चाहिए।',
        'file' => ':attribute :size किलोबाइट्स का होना चाहिए।',
        'numeric' => ':attribute :size का होना चाहिए।',
        'string' => ':attribute :size अक्षर का होना चाहिए।',
    ],
    'starts_with' => ':attribute निम्नलिखित में से किसी एक के साथ शुरू नहीं हो सकता: :values।',
    'string' => ':attribute एक स्ट्रिंग होनी चाहिए।',
    'timezone' => ':attribute एक मान्य टाइम जोन होना चाहिए।',
    'unique' => ':attribute को पहले ही उपयोग में लिया जा चुका है।',
    'uploaded' => ':attribute अपलोड होने में असफल रहा।',
    'url' => ':attribute एक मान्य URL होना चाहिए।',
    'uuid' => ':attribute एक मान्य UUID होना चाहिए।',

    'single' => ':attribute का उपयोग करते समय वह इस request के body में एकमात्र पैरामीटर होना चाहिए',
    'onlyCustomOtpWithUri' => 'URI पैरामीटर अकेले या केवल \'custom_otp\' पैरामीटर के संयोजन में प्रदान किया जाना चाहिए',
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
            'image' => 'jpeg, png, bmp, gif, svg, या webp ही समर्थित प्रारूप हैं',
        ],
        'qrcode' => [
            'image' => 'jpeg, png, bmp, gif, svg, या webp ही समर्थित प्रारूप हैं',
        ],
        'uri' => [
            'regex' => ':attribute एक वैध OTPAuth URI नहीं है।',
        ],
        'otp_type' => [
            'in' => ':attribute समर्थित नहीं है।',
        ],
        'email' => [
            'exists' => 'इस ईमेल का कोई अकाउंट नहीं पाया गया।',
            'ComplyWithEmailRestrictionPolicy' => 'यह ईमेल पता रेजिस्ट्रेशन के नियम का पालन नहीं करता है',
            'IsValidEmailList' => 'सभी ईमेल वैध होने चाहिए और पाइप सिम्बल (|) से अलग किए जाने चाहिए'
        ],
        'secret' => [
            'isBase32Encoded' => ':attribute base32 कोडिंग में बनाया हुआ स्ट्रिंग होना चाहिए।',
        ],
        'account' => [
            'regex' => ':attribute में कोलन नहीं होना चाहिए।',
        ],
        'service' => [
            'regex' => ':attribute में कोलन नहीं होना चाहिए।',
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
