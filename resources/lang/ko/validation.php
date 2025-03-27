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

    'accepted' => ':attribute을(를) 동의해주세요.',
    'accepted_if' => '":other"가 :value일땐 ":attribute"에 반드시 동의해야 합니다.',
    'active_url' => ':attribute은(는) 유효한 URL이 아닙니다.',
    'after' => ':attribute은(는) 반드시 :date 이후 날짜여야 합니다.',
    'after_or_equal' => ':attribute은(는) :date 이후 날짜이거나 같은 날짜여야 합니다.',
    'alpha' => ':attribute은(는) 문자만 포함할 수 있습니다.',
    'alpha_dash' => ':attribute은(는) 영어나 숫자, 하이픈으로만 입력하실 수 있습니다.',
    'alpha_num' => ':attribute은(는) 문자와 숫자만 포함할 수 있습니다.',
    'array' => ':attribute은(는) 배열이어야 합니다.',
    'ascii' => ':attribute(은)는 1바이트 영어 및 숫자와 특수문자만 포함해야 합니다.',
    'before' => ':attribute은(는) :date 이전의 날짜여야 합니다.',
    'before_or_equal' => ':Attribute은(는) :date 이전 날짜이거나 같은 날짜여야 합니다.',
    'between' => [
        'array' => ':attribute은(는) 반드시 :min 과 :max 사이여야 합니다.',
        'file' => ':attribute의 용량은 :min에서 :max KB 사이여야 합니다.',
        'numeric' => ':attribute은(는) :min과 :max 사이의 값이어야 합니다.',
        'string' => ':attribute은(는) 반드시 :min 자에서 :max 자 사이여야 합니다.',
    ],
    'boolean' => ':attribute은(는) true 또는 false 이어야 합니다.',
    'can' => ':attribute 영역에 허용되지 않은 값이 포함되어 있습니다.',
    'confirmed' => ':attribute 확인이 일치하지 않습니다.',
    'contains' => ':attribute 영역에 필요한 값이 없습니다.',
    'current_password' => '비밀번호가 일치하지 않습니다.',
    'date' => ':attribute는 올바른 날짜가 아닙니다.',
    'date_equals' => ':attribute은(는) :date와 같은 날짜여야 합니다.',
    'date_format' => ':attribute이(가) :format 형식과 일치하지 않습니다.',
    'decimal' => ':attribute은(는) 소수점 :decimal 자리여야 합니다.',
    'declined' => ':attribute은(는) 거부되어야 합니다.',
    'declined_if' => ':other이(가) :value일때 :attribute은(는) 거부되어야 합니다.',
    'different' => ':attribute와(과) :other은(는) 서로 달라야 합니다.',
    'digits' => ':attribute은(는) 반드시 :digits 자릿수여야 합니다.',
    'digits_between' => ':attribute은(는) :min에서 :max 자리여야 합니다.',
    'dimensions' => ':attribute의 이미지 크기가 올바르지 않습니다.',
    'distinct' => ':attribute 필드에 중복된 값이 있습니다.',
    'doesnt_end_with' => ':attribute은(는) 다음 중 하나로 끝날 수 없습니다: :values.',
    'doesnt_start_with' => ':attribute은(는) 다음 중 하나로 시작할 수 없습니다: :values.',
    'email' => ':attribute은(는) 유효한 이메일 주소이여야 합니다.',
    'ends_with' => ':attribute은(는) 다음 중 하나로 끝나야 합니다: :values.',
    'enum' => '선택된 :attribute은(는) 올바르지 않습니다.',
    'exists' => '선택된 :attribute은(는) 올바르지 않습니다.',
    'extensions' => ':attribute은(는) 다음 확장 중 하나를 포함해야 합니다: :values.',
    'file' => ':attribute은(는) 파일이어야 합니다.',
    'filled' => ':attribute은(는) 필수 사항입니다.',
    'gt' => [
        'array' => ':attribute은(는) :value개 이상이어야 합니다.',
        'file' => ':attribute의 용량은 :value KB 이상이어야 합니다.',
        'numeric' => ':attribute의 값은 :value보다 커야 합니다.',
        'string' => ':attribute는 :value자 이상이어야합니다.',
    ],
    'gte' => [
        'array' => ':attribute은(는) :value개 이상이어야합니다.',
        'file' => ':attribute의 크기는 :value 킬로바이트 이상이어야 합니다.',
        'numeric' => ':attribute은(는) :value 이상이어야 합니다.',
        'string' => ':attribute은(는) :value 자 이상이어야 합니다.',
    ],
    'hex_color' => ':attribute 영역은 유효한 16진수 색상이어야 합니다.',
    'image' => ':attribute은(는) 이미지여야 합니다.',
    'in' => '선택된 :attribute은(는) 올바르지 않습니다.',
    'in_array' => ':other에 :attribute이(가) 존재하지 않습니다.',
    'integer' => ':Attribute은(는) 정수여야 합니다.',
    'ip' => ':attribute은(는) 유효한 IP 주소여야 합니다.',
    'ipv4' => ':attribute은(는) 유효한 IPv4 주소여야 합니다.',
    'ipv6' => ':attribute은(는) 유효한 IPv6 주소여야 합니다.',
    'json' => ':attribute은(는) 유효한 JSON 문자여야 합니다.',
    'list' => ':attribute 영역은 목록이어야 합니다.',
    'lowercase' => ':attribute 영역은 소문자여야 합니다.',
    'lt' => [
        'array' => ':attribute은(는) :value개 미만이어야 합니다.',
        'file' => ':attribute은(는) :value KB 미만이어야 합니다.',
        'numeric' => ':attribute은(는) :value 미만이어야 합니다.',
        'string' => ':attribute은(는) :value 자 미만이어야 합니다.',
    ],
    'lte' => [
        'array' => ':attribute은(는) :value개 이하여야 합니다.',
        'file' => ':attribute은(는) :value KB 이하이어야 합니다.',
        'numeric' => ':attribute은(는) :value 이하이어야 합니다.',
        'string' => ':attribute은(는) :value 자 이하이어야 합니다.',
    ],
    'mac_address' => ':attribute은(는) 올바른 MAC 주소여야 합니다.',
    'max' => [
        'array' => ':attribute은(는) :max개보다 많을 수 없습니다.',
        'file' => ':attribute은(는) :max KB보다 클 수 없습니다.',
        'numeric' => ':attribute은(는) :max보다 클 수 없습니다.',
        'string' => ':attribute은(는) :max 자보다 많을 수 없습니다.',
    ],
    'max_digits' => ':attribute은 :max 자를 넘지 않아야 합니다.',
    'mimes' => ':attribute은 :values 형식의 파일이어야 합니다.',
    'mimetypes' => ':attribute은 :values 형식의 파일이어야 합니다.',
    'min' => [
        'array' => ':attribute은(는) :min 개 이상이어야 합니다.',
        'file' => ':attribute은(는) :min KB 이상이어야 합니다.',
        'numeric' => ':attribute은(는) :min 이상이어야 합니다.',
        'string' => ':attribute은(는) :min 자 이상이어야 합니다.',
    ],
    'min_digits' => ':attribute은(는) :min 자릿수 이상이어야 합니다.',
    'missing' => ':attribute 영역은 빈 값이어야 합니다.',
    'missing_if' => ':attribute 영역은 :other이(가) :value일 경우 빈 값이어야 합니다.',
    'missing_unless' => ':attribute 영역은 :other이(가) :value이(가) 아닐 경우 빈 값이어야 합니다.',
    'missing_with' => ':attribute 영역은 :value이(가) 존재하는 경우 빈 값이어야 합니다.',
    'missing_with_all' => ':attribute 영역은 :value이(가) 존재하는 경우 빈 값이어야 합니다.',
    'multiple_of' => ':attribute은(는) :value 의 배수여야 합니다.',
    'not_in' => '선택된 :attribute은(는) 유효하지 않습니다.',
    'not_regex' => ':attribute의 형식이 올바르지 않습니다.',
    'numeric' => ':Attribute은(는) 숫자여야 합니다.',
    'password' => [
        'letters' => ':attribute은(는) 반드시 1개 이상의 문자가 포함되야 합니다.',
        'mixed' => ':attribute에는 하나 이상의 대문자와 하나의 소문자가 포함되어야 합니다.',
        'numbers' => ':attribute 은(는) 적어도 하나의 숫자를 포함해야 합니다.',
        'symbols' => ':attribute은(는) 1개 이상의 특수문자가 포함되어야 합니다.',
        'uncompromised' => '주어진 :attribute 가 데이터 유출로 보입니다. 다른 :attribute 를 선택해주세요.',
    ],
    'present' => ':attribute 항목은 필수입니다.',
    'present_if' => ':attribute 영역은 :other이(가) :value일 경우 필수값입니다.',
    'present_unless' => ':attribute 영역은 :other이(가) :value일 경우 필수값입니다.',
    'present_with' => ':attribute 영역은 :value이(가) 존재하는 경우 필수값입니다.',
    'present_with_all' => ':attribute 영역은 :value이(가) 존재하는 경우 필수값입니다.',
    'prohibited' => ':attribute (은)는 금지되어 있습니다.',
    'prohibited_if' => ':attribute 필드는 :other 가 :value 일때 금지됩니다.',
    'prohibited_unless' => ':attribute (은)는 :other 이(가) :value 이(가) 아닌 경우 금지되어 있습니다.',
    'prohibits' => ':attribute 필드는 :other 이(가) 금지되어 있습니다.',
    'regex' => ':attribute의 형식이 올바르지 않습니다.',
    'required' => ':attribute 항목은 필수입니다.',
    'required_array_keys' => ':attribute 필드는 :values에 대한 항목을 포함해야 합니다.',
    'required_if' => ':other이(가) :value 일때 :attribute 필드는 필수입니다.',
    'required_if_accepted' => ':other이(가) 승인되면 :attribute 필드가 필요합니다.',
    'required_if_declined' => ':attribute 영역은 :other이(가) 거부된 경우 필수값입니다.',
    'required_unless' => ':values에 :other이 아닌 이상 attribute 항목은 필수입니다.',
    'required_with' => ':values이(가) 있을 경우 :attribute 항목은 필수입니다.',
    'required_with_all' => ':values이(가) 있는 경우 :attribute 필드는 필수입니다.',
    'required_without' => ':values가 없는 경우 :attribute 필드는 필수입니다.',
    'required_without_all' => ':values(이)가 모두 없을 때 :attribute 항목은 필수입니다.',
    'same' => ':attribute와(과) :other이(가) 일치하지 않습니다.',
    'size' => [
        'array' => ':attribute은(는) :size 개의 항목을 포함해야 합니다.',
        'file' => ':attribute 는 :size kilobytes 여야 합니다.',
        'numeric' => ':attribute은(는) :size여야 합니다.',
        'string' => ':attribute은(는) :size자여야 합니다.',
    ],
    'starts_with' => ':attribute 는 반드시 다음으로 시작해야 합니다: :values.',
    'string' => ':attribute은(는) 반드시 문자열이어야 합니다.',
    'timezone' => ':attribute 는 올바른 시간대여야 합니다.',
    'unique' => ':attribute은(는) 이미 사용중 입니다.',
    'uploaded' => ':attribute을(를) 업로드하지 못했습니다.',
    'uppercase' => ':attribute은(는) 대문자여야 합니다.',
    'url' => ':attribute 는 반드시 올바른 URL이어야 합니다.',
    'ulid' => ':attribute 영역에 유효한 ULID가 필요합니다.',
    'uuid' => ':attribute은(는) 반드시 올바른 UUID여야 합니다.',

    'single' => ':attribute 사용 시 이 요청 본문에서 유일한 매개변수여야 합니다.',
    'onlyCustomOtpWithUri' => 'Uri 매개변수는 단독으로 제공하거나 \'custom_otp\' 매개변수와 함께 제공해야만 합니다.',
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
            'image' => '지원되는 형식은 jpeg, png, bmp, gif, svg, 또는 webp입니다.',
        ],
        'qrcode' => [
            'image' => '지원되는 형식은 jpeg, png, bmp, gif, svg, 또는 webp입니다.',
        ],
        'uri' => [
            'regex' => ':attribute은(는) 올바른 otpauth uri가 아닙니다.',
        ],
        'otp_type' => [
            'in' => ':attribute이(가) 지원되지 않습니다.',
        ],
        'email' => [
            'exists' => '이 이메일을 사용하는 사용자를 찾지 못함',
            'ComplyWithEmailRestrictionPolicy' => '이 이메일 주소는 가입 정책을 준수하지 않습니다.',
            'IsValidEmailList' => '모든 이메일은 유효해야 하며 세로 막대(|)로 구분해야 합니다.'
        ],
        'secret' => [
            'isBase32Encoded' => ':attribute은(는) base32로 인코딩된 문자여야 합니다.',
        ],
        'account' => [
            'regex' => ':attribute은(는) 콜론을 포함할 수 없습니다.',
        ],
        'service' => [
            'regex' => ':attribute은(는) 콜론을 포함할 수 없습니다.',
        ],
        'label' => [
            'required' => 'Uri에는 라벨이 필요합니다.',
        ],
        'ids' => [
            'regex' => 'ID는 쉼표로 구분해야 하며 마지막에는 쉼표가 없어야 합니다.',
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
