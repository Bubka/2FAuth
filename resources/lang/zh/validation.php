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
    'accepted_if' => ':attribute 只有在 :other 为 :value 时才可用',
    'active_url' => ':attribute 不是一个有效的网址。',
    'after' => ':attribute 必须要晚于 :date。',
    'after_or_equal' => ':attribute 必须要等于 :date 或更晚。',
    'alpha' => ':attribute 只能由字母组成。',
    'alpha_dash' => ':attribute 只能由字母、数字、短划线(-)和下划线(_)组成。',
    'alpha_num' => ':attribute 只能由字母和数字组成。',
    'array' => ':attribute 必须是一个数组。',
    'before' => ':attribute 必须要早于 :date。',
    'before_or_equal' => ':attribute 必须要等于 :date 或更早。',
    'between' => [
        'numeric' => ':attribute 必须介于 :min - :max 之间。',
        'file' => ':attribute 必须介于 :min - :max KB 之间。',
        'string' => ':attribute 必须介于 :min - :max 个字符之间。',
        'array' => ':attribute 必须只有 :min - :max 个单元。',
    ],
    'boolean' => ':attribute 必须为布尔值。',
    'confirmed' => ':attribute 两次输入不一致。',
    'current_password' => '密码错误',
    'date' => ':attribute 不是一个有效的日期。',
    'date_equals' => ':attribute 必须要等于 :date。',
    'date_format' => ':attribute 的格式必须为 :format。',
    'declined' => '您必须同意 :attribute.',
    'declined_if' => ':attribute 在 :other 是 :value 时不可用.',
    'different' => ':attribute 和 :other 必须不同。',
    'digits' => ':attribute 必须是 :digits 位数字。',
    'digits_between' => ':attribute 必须是介于 :min 和 :max 位的数字。',
    'dimensions' => ':attribute 图片尺寸不正确。',
    'distinct' => ':attribute 已经存在。',
    'email' => ':attribute 不是一个合法的邮箱。',
    'ends_with' => ':attribute 必须以 :values 为结尾。',
    'exists' => ':attribute 不存在。',
    'file' => ':attribute 必须是文件。',
    'filled' => ':attribute 不能为空。',
    'gt' => [
        'numeric' => ':attribute 必须大于 :value。',
        'file' => ':attribute 必须大于 :value KB。',
        'string' => ':attribute 必须多于 :value 个字符。',
        'array' => ':attribute 必须多于 :value 个元素。',
    ],
    'gte' => [
        'numeric' => ':attribute 必须大于或等于 :value。',
        'file' => ':attribute 必须大于或等于 :value KB。',
        'string' => ':attribute 必须多于或等于 :value 个字符。',
        'array' => ':attribute 必须多于或等于 :value 个元素。',
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
        'numeric' => ':attribute 必须小于 :value。',
        'file' => ':attribute 必须小于 :value KB。',
        'string' => ':attribute 必须少于 :value 个字符。',
        'array' => ':attribute 必须少于 :value 个元素。',
    ],
    'lte' => [
        'numeric' => ':attribute 必须小于或等于 :value。',
        'file' => ':attribute 必须小于或等于 :value KB。',
        'string' => ':attribute 必须少于或等于 :value 个字符。',
        'array' => ':attribute 必须少于或等于 :value 个元素。',
    ],
    'max' => [
        'numeric' => ':attribute 不能大于 :max。',
        'file' => ':attribute 不能大于 :max KB。',
        'string' => ':attribute 不能大于 :max 个字符。',
        'array' => ':attribute 最多只有 :max 个单元。',
    ],
    'mimes' => ':attribute 必须是一个 :values 类型的文件。',
    'mimetypes' => ':attribute 必须是一个 :values 类型的文件。',
    'min' => [
        'numeric' => ':attribute 必须大于等于 :min。',
        'file' => ':attribute 大小不能小于 :min KB。',
        'string' => ':attribute 至少为 :min 个字符。',
        'array' => ':attribute 至少有 :min 个单元。',
    ],
    'multiple_of' => ':attribute 必须是 :value 的倍数',
    'not_in' => '已选的属性 :attribute 非法。',
    'not_regex' => ':attribute 的格式错误。',
    'numeric' => ':attribute 必须是一个数字。',
    'password' => '密码错误',
    'present' => ':attribute 必须存在。',
    'prohibited' => ':attribute 字段是禁止的.',
    'prohibited_if' => '当 :other 为 :value 时, :attribute 字段被禁止',
    'prohibited_unless' => '除非 :other 为 :values，否则 :attribute 字段是禁止的',
    'prohibits' => ':attribute 字段禁止出现 ":other"',
    'regex' => ':attribute 格式不正确。',
    'required' => ':attribute 不能为空。',
    'required_if' => '当 :other 为 :value 时 :attribute 不能为空。',
    'required_unless' => '当 :other 不为 :values 时 :attribute 不能为空。',
    'required_with' => '当 :values 存在时 :attribute 不能为空。',
    'required_with_all' => '当 :values 存在时 :attribute 不能为空。',
    'required_without' => '当 :values 不存在时 :attribute 不能为空。',
    'required_without_all' => '当 :values 都不存在时 :attribute 不能为空。',
    'same' => ':attribute 和 :other 必须相同。',
    'size' => [
        'numeric' => ':attribute 大小必须为 :size。',
        'file' => ':attribute 大小必须为 :size KB。',
        'string' => ':attribute 必须是 :size 个字符。',
        'array' => ':attribute 必须为 :size 个单元。',
    ],
    'starts_with' => ':attribute 必须以 :values 为开头。',
    'string' => ':attribute 必须是一个字符串。',
    'timezone' => ':attribute 必须是一个合法的时区值。',
    'unique' => ':attribute 已经存在。',
    'uploaded' => ':attribute 上传失败。',
    'url' => ':attribute 必须是有效的 url。',
    'uuid' => ':attribute 必须是有效的 UUID。',

    'single' => '当使用 :attribute 时，它必须是此请求主体中的唯一参数',
    'onlyCustomOtpWithUri' => 'The uri parameter must be provided alone or only in combination with the \'custom_otp\' parameter',

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
        ],
        'name' => [
            'firstUser' => '已有注册用户',
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
