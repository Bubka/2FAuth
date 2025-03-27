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

    'accepted' => ':attribute kabul edilmelidir.',
    'accepted_if' => ':other, :value olduğunda :attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL değil.',
    'after' => ':attribute şu tarihten :date sonra olmalı.',
    'after_or_equal' => ':attribute, :date tarihi ile aynı veya bundan sonraki bir tarih olmalıdır.',
    'alpha' => ':attribute yalnızca harf içermelidir.',
    'alpha_dash' => ':attribute yalnızca harfler, rakamlar, tireler ve alt çizgiler içermelidir.',
    'alpha_num' => ':attribute yalnızca harfler ve rakamlar içermelidir.',
    'array' => ':attribute bir dizi olmalıdır.',
    'ascii' => ':attribute yalnızca tek baytlık alfanümerik karakterler ve semboller içermelidir.',
    'before' => ':attribute, :date tarihinden önce bir tarih olmalıdır.',
    'before_or_equal' => ':attribute, :date tarihi ile aynı veya önceki bir tarih olmalıdır.',
    'between' => [
        'array' => ':attribute, :min ve :max aralığında olmalıdır.',
        'file' => ':attribute, :min ve :max kilobyte aralığında olmalıdır.',
        'numeric' => ':attribute, :min ve :max aralığında olmalıdır.',
        'string' => ':attribute, :min ve :max karakter aralığında olmalıdır.',
    ],
    'boolean' => ':attribute, doğru veya yanlış olmalıdır.',
    'can' => ':attribute alanı yetkisiz bir değer içeriyor.',
    'confirmed' => ':attribute doğrulaması eşleşmedi.',
    'contains' => ':attribute alanında gerekli bir değer eksik.',
    'current_password' => 'Parola hatalı.',
    'date' => ':attribute geçerli bir tarih değil.',
    'date_equals' => ':attribute tarihi, :date tarihine eşit olmalıdır.',
    'date_format' => ':attribute :format biçimi ile eşleşmiyor.',
    'decimal' => ':attribute alanı :decimal kadar ondalık basamağa sahip olmalıdır.',
    'declined' => ':attribute reddedilmelidir.',
    'declined_if' => ':other, :value olduğunda :attribute reddedilmelidir.',
    'different' => ':attribute ve :other birbirinden farklı olmalıdır.',
    'digits' => ':attribute, :digits rakam olmalıdır.',
    'digits_between' => ':attribute, :min ve :max basamak aralığında olmalıdır.',
    'dimensions' => ':attribute geçersiz görüntü boyutlarına sahip.',
    'distinct' => ':attribute alanı yinelenen bir değere sahip.',
    'doesnt_end_with' => ':attribute şunlardan biriyle bitmemelidir: :values.',
    'doesnt_start_with' => ':attribute şunlardan biriyle başlamamalıdır: :values.',
    'email' => ':attribute geçerli bir ePosta adresi olmalı.',
    'ends_with' => ':attribute şunlardan biriyle bitmelidir: :values.',
    'enum' => ':attribute seçimi geçersiz.',
    'exists' => ':attribute seçimi geçersiz.',
    'extensions' => ':attribute alanı, şu uzantılardan birine sahip olmalıdır: :values.',
    'file' => ':attribute bir dosya olmalıdır.',
    'filled' => ':attribute alanının doldurulması zorunludur.',
    'gt' => [
        'array' => ':attribute :value öğelerinden daha fazla öğeye sahip olmalıdır.',
        'file' => ':attribute, :value kilobayttan fazla olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'string' => ':attribute :value karakterden büyük olmalıdır.',
    ],
    'gte' => [
        'array' => ':attribute, :value veya daha fazla öğe içermelidir.',
        'file' => ':attribute :value kilobayttan büyük veya eşit olmalıdır.',
        'numeric' => ':attribute, :value değerinden büyük veya eşit olmalıdır.',
        'string' => ':attribute, en az :value karakter içermelidir.',
    ],
    'hex_color' => ':attribute alanı geçerli bir onaltılık renk olmalıdır.',
    'image' => ':attribute bir görsel olmalı.',
    'in' => ':attribute seçimi geçersiz.',
    'in_array' => ':attribute değeri :other içinde mevcut değil.',
    'integer' => ':attribute tamsayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON olmalı.',
    'list' => ':attribute alanı bir liste olmalı.',
    'lowercase' => ':attribute alanı yalnızca küçük harfler içermelidir.',
    'lt' => [
        'array' => ':attribute, :value ögeden az olmalıdır.',
        'file' => ':attribute, :value kilobayttan az olmalıdır.',
        'numeric' => ':attribute, :value değerinden küçük olmalıdır.',
        'string' => ':attribute, :value karakterden az olmalıdır.',
    ],
    'lte' => [
        'array' => ':attribute, en fazla :value öge içermelidir.',
        'file' => ':attribute, :value kilobayttan küçük ya da eşit olmalıdır.',
        'numeric' => ':attribute, :value değerinden küçük veya eşit olmalıdır.',
        'string' => ':attribute :value karakterden az ya da eşit olmalıdır.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => ':attribute, :max öğeden fazla öğeye sahip olmamalıdır.',
        'file' => ':attribute değeri :max kilobayttan büyük olmamalıdır.',
        'numeric' => ':attribute değeri :max değerinden büyük olmamalıdır.',
        'string' => ':attribute, :max karakterden fazla olmamalıdır.',
    ],
    'max_digits' => ':attribute, :max rakamdan fazla rakama sahip olmamalıdır.',
    'mimes' => ':attribute şu dosya biçimlerinden biri olmalıdır: :values.',
    'mimetypes' => ':attribute şu dosya biçimlerinden biri olmalıdır: :values.',
    'min' => [
        'array' => ':attribute en az :min nesneye sahip olmalıdır.',
        'file' => ':attribute en az :min kilobayt olmalıdır.',
        'numeric' => ':attribute en az :min olmalıdır.',
        'string' => ':attribute en az :min karakter içermelidir.',
    ],
    'min_digits' => ':attribute en az :min rakam içermelidir.',
    'missing' => ':attribute alanı eksik olmalı.',
    'missing_if' => ':attribute alanı, :other :value olduğunda, boş olmalıdır.',
    'missing_unless' => ':other, :value olmadığı sürece :attribute alanı eksik olmalıdır.',
    'missing_with' => ':values mevcut olduğunda :attribute alanı boş olmalıdır.',
    'missing_with_all' => ':values mevcut olduğunda :attribute alanı boş olmalıdır.',
    'multiple_of' => ':attribute, :values değerinin katı olmalıdır.',
    'not_in' => ':attribute seçimi geçersiz.',
    'not_regex' => ':attribute formatı geçersiz.',
    'numeric' => ':attribute bir sayı olmalıdır.',
    'password' => [
        'letters' => ':attribute en az bir harf içermelidir.',
        'mixed' => ':attribute en az bir büyük harf ve bir küçük harf içermelidir.',
        'numbers' => ':attribute en az bir sayı içermelidir.',
        'symbols' => ':attribute en az bir sembol içermelidir.',
        'uncompromised' => 'Girilen :attribute bir veri sızıntısında ortaya çıktı. Lütfen farklı bir :attribute seçin.',
    ],
    'present' => ':attribute alanı dolu olmalı.',
    'present_if' => ':other :valiue olduğunda, :attribute mevcut olmalıdır.',
    'present_unless' => ':other, :value olmadığı sürece :attribute alanı mevcut olmalıdır.',
    'present_with' => ':values mevcut olduğunda :attribute alanı da mevcut olmalıdır.',
    'present_with_all' => ':values mevcut olduğunda :attribute alanı da mevcut olmalıdır.',
    'prohibited' => ':attribute alanı engellenmiştir.',
    'prohibited_if' => ':other :value iken :attribute alanı engellenmiştir.',
    'prohibited_unless' => ':attribute alanı, :other alanı :value değerlerinden birine sahip olmadığı sürece engellenmiştir.',
    'prohibits' => ':attribute alanı :other değerinin mevcut olmasını engeller.',
    'regex' => ':attribute formatı geçersiz.',
    'required' => ':attribute alanı zorunludur.',
    'required_array_keys' => ':attribute alanı aşağıdakiler için girişler içermelidir: :values.',
    'required_if' => ':other :value iken :attribute alanı gereklidir.',
    'required_if_accepted' => ':other kabul edildiğinde :attribute alanı gereklidir.',
    'required_if_declined' => ':other reddedildiğinde :attribute alanı gereklidir.',
    'required_unless' => ':attribute alanı, :other alanı :value değerlerinden birine sahip olmadığında zorunludur.',
    'required_with' => ':values varsa :attribute alanı zorunludur.',
    'required_with_all' => ':values mevcut ise :attribute alanları zorunludur.',
    'required_without' => ':attribute alanı :values yokken zorunludur.',
    'required_without_all' => 'Herhangi bir :values değeri mevcut olmadığında :attribute alanına değer girilmesi zorunludur.',
    'same' => ':attribute ve :other aynı olmalı.',
    'size' => [
        'array' => ':attribute :size nesneye sahip olmalıdır.',
        'file' => ':attribute :size kilobayt olmalıdır.',
        'numeric' => ':attribute, :size olmalıdır.',
        'string' => ': attribute en az :size karakter olmalıdır.',
    ],
    'starts_with' => ':attribute şunlardan biriyle başlamalıdır: :values.',
    'string' => ':attribute bir dize olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute daha önceden kayıt edilmiş.',
    'uploaded' => ':attribute yüklemesi başarısız.',
    'uppercase' => ':attribute alanı yalnızca BÜYÜK HARFLER içermelidir.',
    'url' => ':attribute geçerli bir URL olmalıdır.',
    'ulid' => ':attribute alanı geçerli bir ULID olmalıdır.',
    'uuid' => ':attribute geçerli bir UUID olmalıdır.',

    'single' => ':attribute kullanıldığında, istek gövdesindeki tek parametre bu olmalıdır',
    'onlyCustomOtpWithUri' => 'Uri parametresi tek başına veya yalnızca \'custom_otp\' parametresiyle birlikte sağlanmalıdır',
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
            'image' => 'Desteklenen formatlar jpeg, png, bmp, gif, svg veya webp\'dir.',
        ],
        'qrcode' => [
            'image' => 'Desteklenen formatlar jpeg, png, bmp, gif, svg veya webp\'dir.',
        ],
        'uri' => [
            'regex' => ':attribute geçerli bir otpauth uri\'si değil.',
        ],
        'otp_type' => [
            'in' => ':attribute desteklenmiyor.',
        ],
        'email' => [
            'exists' => 'Bu ePostayı kullanan bir hesap bulunamadı.',
            'ComplyWithEmailRestrictionPolicy' => 'Bu ePosta adresi kayıt politikasına uymuyor',
            'IsValidEmailList' => 'Tüm ePostaların geçerli ve dikey çubuk ile ayrılmış olması gerekiyor'
        ],
        'secret' => [
            'isBase32Encoded' => ':attribute base32 kodlu bir dize olmalıdır.',
        ],
        'account' => [
            'regex' => ':attribute iki nokta üst üste içermemelidir.',
        ],
        'service' => [
            'regex' => ':attribute iki nokta üst üste içermemelidir.',
        ],
        'label' => [
            'required' => 'Uri\'nin bir etiketi olmalıdır.',
        ],
        'ids' => [
            'regex' => 'IDler virgül ile ayrılmalı, en sonda ise virgül olmamalıdır.',
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
