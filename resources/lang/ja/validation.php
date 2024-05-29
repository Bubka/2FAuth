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

    'accepted' => ':attributeを承認してください。',
    'accepted_if' => ':otherが:valueの場合、:attributeを有効にする必要があります。',
    'active_url' => ':attributeは、有効なURLではありません。',
    'after' => ':attributeには、:dateより後の日付を指定してください。',
    'after_or_equal' => ':attributeには、:date以降の日付を指定してください。',
    'alpha' => ':attributeには、半角英字（\'A-Z\', \'a-z\'）のみ使用できます。',
    'alpha_dash' => ':attributeには、半角の英数字（\'A-Z\', \'a-z\', \'0-9\'）、ハイフン（-）、下線（_）のみ使用できます。',
    'alpha_num' => ':attributeには、半角英数字（\'A-Z\', \'a-z\', \'0-9\'）のみ使用できます。',
    'array' => ':attributeには、配列を指定してください。',
    'before' => ':attributeには、:dateより前の日付を指定してください。',
    'before_or_equal' => ':attributeには、:date以前の日付を指定してください。',
    'between' => [
        'array' => ':attributeの項目は、:min個から:max個にしてください。',
        'file' => ':attributeには、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'numeric' => ':attributeには、:minから、:maxまでの数字を指定してください。',
        'string' => ':attributeは、:min文字から:max文字にしてください。',
    ],
    'boolean' => ':attributeには、\'true\'か\'false\'を指定してください。',
    'confirmed' => ':attributeと:attribute確認が一致しません。',
    'current_password' => 'パスワードが違います。',
    'date' => ':attributeは、正しい日付ではありません。',
    'date_equals' => ':attributeは:dateに等しい日付でなければなりません。',
    'date_format' => ':attributeの形式は、\':format\'と合いません。',
    'declined' => ':attributeを拒否する必要があります。',
    'declined_if' => ':otherが:valueの場合、:attributeを無効にする必要があります。',
    'different' => ':attributeと:otherには、異なるものを指定してください。',
    'digits' => ':attributeは、:digits桁にしてください。',
    'digits_between' => ':attributeは、:min桁から:max桁にしてください。',
    'dimensions' => ':attributeの画像サイズが無効です',
    'distinct' => ':attributeの値が重複しています。',
    'doesnt_end_with' => ':attributeは「:values」以外で終わる必要があります。',
    'doesnt_start_with' => ':attributeは「:values」以外で始まる必要があります。',
    'email' => ':attributeは、有効なメールアドレス形式で指定してください。',
    'ends_with' => ':attributeは、次のうちのいずれかで終わらなければなりません。: :values',
    'enum' => '選択した:attributeは無効です。',
    'exists' => '選択された:attributeは、有効ではありません。',
    'file' => ':attributeはファイルでなければいけません。',
    'filled' => ':attributeは必須です。',
    'gt' => [
        'array' => ':attributeの項目数は、:value個より大きくなければなりません。',
        'file' => ':attributeは、:value KBより大きくなければなりません。',
        'numeric' => ':attributeは、:valueより大きくなければなりません。',
        'string' => ':attributeは、:value文字より大きくなければなりません。',
    ],
    'gte' => [
        'array' => ':attributeの項目数は、:value個以上でなければなりません。',
        'file' => ':attributeは :value キロバイト以上でなければなりません。',
        'numeric' => ':attributeは :value 以上でなければなりません。',
        'string' => ':attributeは :value 文字以上でなければなりません。',
    ],
    'image' => ':attributeには、画像を指定してください。',
    'in' => '選択された:attributeは、有効ではありません。',
    'in_array' => ':attributeが:otherに存在しません。',
    'integer' => ':attributeには、整数を指定してください。',
    'ip' => ':attributeには、有効なIPアドレスを指定してください。',
    'ipv4' => ':attributeはIPv4アドレスを指定してください。',
    'ipv6' => ':attributeはIPv6アドレスを指定してください。',
    'json' => ':attributeには、有効なJSON文字列を指定してください。',
    'lt' => [
        'array' => ':attributeの項目数は、:value個より小さくなければなりません。',
        'file' => ':attributeは、:value KBより小さくなければなりません。',
        'numeric' => ':attributeは、:valueより小さくなければなりません。',
        'string' => ':attributeは、:value文字より小さくなければなりません。',
    ],
    'lte' => [
        'array' => ':attributeの項目数は、:value個以下でなければなりません。',
        'file' => ':attributeは :value キロバイト以下でなければなりません。',
        'numeric' => ':attributeは :value 以下でなければなりません。',
        'string' => ':attributeは :value 文字以下でなければなりません。',
    ],
    'mac_address' => ':attributeはMACアドレスを指定してください。',
    'max' => [
        'array' => ':attributeの項目は、:max 個以下にしてください。',
        'file' => ':attributeは :max キロバイト以下でなければなりません。',
        'numeric' => ':attributeは :max 以下でなければなりません。',
        'string' => ':attributeは :max 文字以下でなければなりません。',
    ],
    'max_digits' => ':attributeは :max 桁以下の数字でなければなりません。',
    'mimes' => ':attributeには、:valuesタイプのファイルを指定してください。',
    'mimetypes' => ':attributeには、:valuesタイプのファイルを指定してください。',
    'min' => [
        'array' => ':attributeの項目は、:min個以上にしてください。',
        'file' => ':attributeには、:min KB以上のファイルを指定してください。',
        'numeric' => ':attributeには、:min以上の数字を指定してください。',
        'string' => ':attributeは、:min文字以上にしてください。',
    ],
    'min_digits' => ':attributeは :min 桁以上の数字でなければなりません。',
    'multiple_of' => ':attributeは :value の倍数でなければなりません。',
    'not_in' => '選択された:attributeは、有効ではありません。',
    'not_regex' => ':attributeの形式が無効です。',
    'numeric' => ':attributeには、数字を指定してください。',
    'password' => [
        'letters' => ':attributeには、英字を 1 文字以上含める必要があります。',
        'mixed' => ':attributeには、大文字と小文字をそれぞれ1文字以上含める必要があります。',
        'numbers' => ':attributeには、数字を 1 文字以上含める必要があります。',
        'symbols' => ':attributeには、記号を 1 文字以上含める必要があります。',
        'uncompromised' => 'この:attributeはデータ漏洩の対象となった可能性があります。別の:attributeを選んでください。',
    ],
    'present' => ':attributeが存在している必要があります。',
    'prohibited' => ':attribute項目は入力しないでください。',
    'prohibited_if' => ':otherが:valueの場合、:attributeは入力しないでください。',
    'prohibited_unless' => ':otherが:values以外の場合、:attributeは入力しないでください。',
    'prohibits' => ':attributeがある場合、:otherは存在できません。',
    'regex' => ':attributeには、有効な正規表現を指定してください。',
    'required' => ':attributeは、必ず指定してください。',
    'required_array_keys' => ':attributeには、「:values 」を含める必要があります。',
    'required_if' => ':otherが:valueの場合、:attributeを指定してください。',
    'required_if_accepted' => ':otherが有効である場合、:attributeを指定してください。',
    'required_unless' => ':otherが:values以外の場合、:attributeを指定してください。',
    'required_with' => ':valuesが指定されている場合、:attributeも指定してください。',
    'required_with_all' => ':valuesが全て指定されている場合、:attributeも指定してください。',
    'required_without' => ':valuesが指定されていない場合、:attributeを指定してください。',
    'required_without_all' => ':valuesが全て指定されていない場合、:attributeを指定してください。',
    'same' => ':attributeと:otherが一致しません。',
    'size' => [
        'array' => ':attributeの項目は、:size個にしてください。',
        'file' => ':attributeには、:size KBのファイルを指定してください。',
        'numeric' => ':attributeには、:sizeを指定してください。',
        'string' => ':attributeは、:size文字にしてください。',
    ],
    'starts_with' => ':attributeは、次のいずれかで始まる必要があります。:values',
    'string' => ':attributeには、文字を指定してください。',
    'timezone' => ':attributeには、有効なタイムゾーンを指定してください。',
    'unique' => '指定の:attributeは既に使用されています。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'url' => ':attributeには、正しいURLを指定してください。',
    'uuid' => ':attributeは、有効なUUIDでなければなりません。',

    'single' => ':attributeを使用する場合、このリクエスト本文の唯一のパラメータにする必要があります。',
    'onlyCustomOtpWithUri' => 'uri パラメータは単独または「custom_otp」パラメータと組み合わせて指定する必要があります。',

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
            'image' => 'サポートされている形式は jpeg, png, bmp, gif, svg, webp です。',
        ],
        'qrcode' => [
            'image' => 'サポートされている形式は jpeg, png, bmp, gif, svg, webp です。',
        ],
        'uri' => [
            'regex' => ':attributeは、有効な otpauth uri ではありません。',
        ],
        'otp_type' => [
            'in' => ':attributeはサポートされていません。',
        ],
        'email' => [
            'exists' => 'このメールアドレスを使用するアカウントは見つかりませんでした。',
            'ComplyWithEmailRestrictionPolicy' => 'このメールアドレスは登録ルールに適合していません',
            'IsValidEmailList' => 'すべてのアドレスは有効かつ、パイプ (|) で区切られている必要があります'
        ],
        'secret' => [
            'isBase32Encoded' => ':attributeは base32 エンコード文字列を指定してください。',
        ],
        'account' => [
            'regex' => ':attributeにコロンを含めることはできません。',
        ],
        'service' => [
            'regex' => ':attributeにコロンを含めることはできません。',
        ],
        'label' => [
            'required' => 'uri にはラベルが必要です。',
        ],
        'ids' => [
            'regex' => 'IDはコンマで区切り、最後にはコンマを入れないでください。',
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
