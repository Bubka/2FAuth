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

    'accepted' => '您必须接受 :attribute。',
    'accepted_if' => ':attribute 只有在 :other 为 :value 时才有效',
    'active_url' => ':attribute 不是一个有效的网址。',
    'after' => ':attribute 必须要晚于 :date。',
    'after_or_equal' => ':attribute 必须要等于 :date 或更晚。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、 数字、 破折号和下划线',
    'alpha_num' => ':attribute 只能包含字母和数字',
    'array' => ':attribute 必须是一个数组。',
    'before' => ':attribute 必须要早于 :date。',
    'before_or_equal' => ':attribute 必须要等于 :date 或更早。',
    'between' => [
        'array' => ':attribute 必须只有 :min - :max 个单元。',
        'file' => ':attribute 必须介于 :min - :max KB 之间。',
        'numeric' => ':attribute 必须介于 :min - :max 之间。',
        'string' => ':attribute 必须介于 :min - :max 个字符之间。',
    ],
    'boolean' => ':attribute 必须为布尔值。',
    'confirmed' => ':attribute 两次输入不一致。',
    'current_password' => '密码错误',
    'date' => ':attribute 不是一个有效的日期。',
    'date_equals' => ':attribute 必须要等于 :date。',
    'date_format' => ':attribute 的格式必须为 :format。',
    'declined' => '您必须同意 :attribute.',
    'declined_if' => ':attribute 在 :other 是 :value 时无效.',
    'different' => ':attribute 和 :other 必须不同。',
    'digits' => ':attribute 必须是 :digits 位数字。',
    'digits_between' => ':attribute 必须是介于 :min 和 :max 位的数字。',
    'dimensions' => ':attribute 图片尺寸不正确。',
    'distinct' => ':attribute 已经存在。',
    'doesnt_end_with' => 'The :attribute may not end with one of the following: :values.',
    'doesnt_start_with' => 'The :attribute may not start with one of the following: :values.',
    'email' => ':attribute 不是一个合法的邮箱。',
    'ends_with' => ':attribute 必须以 :values 为结尾。',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => ':attribute 不存在。',
    'file' => ':attribute 必须是文件。',
    'filled' => ':attribute 不能为空。',
    'gt' => [
        'array' => ':attribute 必须多于 :value 个元素。',
        'file' => ':attribute 必须大于 :value KB。',
        'numeric' => ':attribute 必须大于 :value。',
        'string' => ':attribute 必须多于 :value 个字符。',
    ],
    'gte' => [
        'array' => ':attribute 必须多于或等于 :value 个元素。',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
    ],
    'image' => ':attribute 必须是图片。',
    'in' => '已选的属性 :attribute 无效。',
    'in_array' => ':attribute 必须在 :other 中。',
    'integer' => ':attribute 必须是整数。',
    'ip' => ':attribute 必须是有效的 IP 地址。',
    'ipv4' => ':attribute 必须是有效的 IPv4 地址。',
    'ipv6' => ':attribute 必须是有效的 IPv6 地址。',
    'json' => ':attribute 必须是正确的 JSON 格式。',
    'lt' => [
        'array' => ':attribute 必须少于 :value 个元素。',
        'file' => ':attribute 必须小于 :value KB。',
        'numeric' => ':attribute 必须小于 :value。',
        'string' => ':attribute 必须少于 :value 个字符。',
    ],
    'lte' => [
        'array' => ':attribute 必须少于或等于 :value 个元素。',
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
    'mimes' => ':attribute 必须是一个 :values 类型的文件。',
    'mimetypes' => ':attribute 必须是一个 :values 类型的文件。',
    'min' => [
        'array' => ':attribute 至少有 :min 个单元。',
        'file' => ':attribute 大小不能小于 :min KB。',
        'numeric' => ':attribute 必须大于等于 :min。',
        'string' => ':attribute 至少为 :min 个字符。',
    ],
    'min_digits' => 'The :attribute must have at least :min digits.',
    'multiple_of' => ':attribute 必须是 :value 的倍数',
    'not_in' => '已选的属性 :attribute 非法。',
    'not_regex' => ':attribute 的格式错误。',
    'numeric' => ':attribute 必须是一个数字。',
    'password' => [
        'letters' => 'The :attribute must contain at least one letter.',
        'mixed' => 'The :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'The :attribute must contain at least one number.',
        'symbols' => 'The :attribute must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => ':attribute 必须存在。',
    'prohibited' => ':attribute 字段是禁止的.',
    'prohibited_if' => '当 :other 为 :value 时, :attribute 字段被禁止',
    'prohibited_unless' => '除非 :other 为 :values，否则 :attribute 字段是禁止的',
    'prohibits' => ':attribute 字段禁止出现 ":other"',
    'regex' => ':attribute 格式不正确。',
    'required' => ':attribute 不能为空。',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => '当 :other 为 :value 时 :attribute 不能为空。',
    'required_if_accepted' => 'The :attribute field is required when :other is accepted.',
    'required_unless' => '当 :other 不为 :values 时 :attribute 不能为空。',
    'required_with' => '当 :values 存在时 :attribute 不能为空。',
    'required_with_all' => '当 :values 存在时 :attribute 不能为空。',
    'required_without' => '当 :values 不存在时 :attribute 不能为空。',
    'required_without_all' => '当 :values 都不存在时 :attribute 不能为空。',
    'same' => ':attribute 和 :other 必须相同。',
    'size' => [
        'array' => ':attribute 必须为 :size 个单元。',
        'file' => ':attribute 大小必须为 :size KB。',
        'numeric' => ':attribute 大小必须为 :size。',
        'string' => ':attribute 必须是 :size 个字符。',
    ],
    'starts_with' => ':attribute 必须以 :values 为开头。',
    'string' => ':attribute 必须是一个字符串。',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => ':attribute 已经存在。',
    'uploaded' => ':attribute 上传失败。',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => ':attribute 必须是有效的 UUID。',

    'single' => '当使用 :attribute 时，它必须是此请求主体中的唯一参数',
    'onlyCustomOtpWithUri' => '"uri"参数仅应单独提供，或与"custom_otp"参数结合提供',

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
            'image' => '支持的格式是 jpeg、png、bmp、gif、svg或web。',
        ],
        'qrcode' => [
            'image' => '支持的格式是 jpeg、png、bmp、gif、svg或web。',
        ],
        'uri' => [
            'regex' => ':attribute 不是有效的 otpauth uri',
        ],
        'otp_type' => [
            'in' => ':attribute 不受支持',
        ],
        'email' => [
            'exists' => '未找到使用此电子邮件的账户。',
        ],
        'secret' => [
            'isBase32Encoded' => ':attribute 必须是 base32 编码的字符串',
        ],
        'account' => [
            'regex' => ':attribute 不能包含冒号。',
        ],
        'service' => [
            'regex' => ':attribute 不能包含冒号。',
        ],
        'label' => [
            'required' => 'uri 必须有一个标签。',
        ],
        'ids' => [
            'regex' => 'ID必须以逗号分隔，无需尾随逗号。',
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
