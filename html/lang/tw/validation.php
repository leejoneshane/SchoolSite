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

    'accepted'             => '您必須同意 :attribute 。',
    'accepted_if'          => '當 :other 為 :value 時，您必須同意 :attribute 。',
    'active_url'           => ':attribute 網址不存在或無法連結。',
    'after'                => ':attribute 必須是一個晚於 :date 的日期。',
    'after_or_equal'       => ':attribute 必須是一個晚於或等於 :date 的日期。',
    'alpha'                => ':attribute 只能由英文字母組成。',
    'alpha_dash'           => ':attribute 只能由英文字母、數字、破折號（ - ）或底線（ _ ）組成。',
    'alpha_num'            => ':attribute 只能由英文字母或數字組成。',
    'array'                => ':attribute 必須是一個陣列。',
    'before'               => ':attribute 必須是一個早於 :date 的日期。',
    'before_or_equal'      => ':attribute 必須是一個早於或等於 :date 的日期。',
    'between'              => [
        'array'            => ':attribute 陣列必須介於 :min 到 :max 個元素之間。',
        'file'             => ':attribute 檔案大小必須介於 :min 到 :max KB。',
        'numeric'          => ':attribute 必須介於 :min 到 :max 之間。',
        'string'           => ':attribute 字串長度必須介於 :min 到 :max 個字元之間。',
    ],
    'boolean'              => ':attribute 欄位必須能夠轉型為布林值。',
    'confirmed'            => '兩次輸入的 :attribute 必須相同。',
    'current_password'     => '密碼不正確。',
    'date'                 => ':attribute 日期不存在。',
    'date_equals'          => ':attribute 必須是日期，且與 :date 相同。',
    'date_format'          => ':attribute 日期格式必須為 :format 。',
    'declined'             => '您必須拒絕 :attribute。',
    'declined_if'          => '當 :other 為 :value 時，您必須拒絕 :attribute 。',
    'different'            => ':attribute 必須和 :other 不相同。',
    'digits'               => ':attribute 必須是 :digits 位數。',
    'digits_between'       => ':attribute 必須介於 :min 和 :max 位數之間。',
    'dimensions'           => ':attribute 圖片的長或寬超出允許範圍。',
    'distinct'             => ':attribute 欄位不可以與別的欄位重複。',
    'doesnt_start_with'    => ':attribute 的開頭不可以是這些字元： :values。',
    'email'                => ':attribute 必須符合電子郵件格式。',
    'ends_with'            => ':attribute 的結尾不可以是這些字元： :values。',
    'enum'                 => '您選擇的 :attribute 不正確。',
    'exists'               => '您選擇的 :attribute 不存在。',
    'file'                 => ':attribute 必須是一個檔案。',
    'filled'               => ':attribute 欄位不可留白。',
    'gt'                   => [
        'array'            => ':attribute 陣列應多於 :value 個元素。',
        'file'             => ':attribute 檔案應該大於 :value KB。',
        'numeric'          => ':attribute 應大於 :value。',
        'string'           => ':attribute 字串長度應大於 :value 個字元。',
    ],
    'gte' => [
        'array'            => ':attribute 陣列不可少於 :value 個元素。',
        'file'             => ':attribute 檔案不可小於 :value KB。',
        'numeric'          => ':attribute 不可小於 :value。',
        'string'           => ':attribute 字串長度不可少於 :value 個字元。',
    ],
    'image'                => ':attribute 必須是圖片檔案。',
    'in'                   => '您選擇的 :attribute 不在允許範圍。',
    'in_array'             => ':attribute 必須在 :other 陣列中。',
    'integer'              => ':attribute 必須是整數。',
    'ip'                   => ':attribute 必須符合 IP 位址格式。',
    'ipv4'                 => ':attribute 必須符合 IPv4 位址格式。',
    'ipv6'                 => ':attribute 必須符合 IPv6 位址格式。',
    'json'                 => ':attribute 必須是一個 JSON 字串。',
    'lt'                   => [
        'array'            => ':attribute 陣列必需少於 :value 個元素。',
        'file'             => ':attribute 檔案必須小於 :value KB。',
        'numeric'          => ':attribute 必須小於 :value。',
        'string'           => ':attribute 字串長度必須小於 :value 個字元。',
    ],
    'lte'                  => [
        'array'            => ':attribute 陣列不可多於 :value 個元素。',
        'file'             => ':attribute 檔案不可大於 :value KB。',
        'numeric'          => ':attribute 不可大於 :value。',
        'string'           => ':attribute 字串長度不可多於 :value 個字元。',
    ],
    'mac_address'          => ':attribute 必須是正確的網卡地址格式。',
    'max'                  => [
        'numeric'          => ':attribute 不可以大於 :max。',
        'file'             => ':attribute 檔案大小不可以超過 :max KB。',
        'string'           => ':attribute 字串長度不可以超過 :max 個字元。',
        'array'            => ':attribute 陣列不可以超過 :max 個元素。',
    ],
    'mimes'                => ':attribute 檔案格式必須為 :values。',
    'mimetypes'            => ':attribute 檔案格式必須為 :values。',
    'min'                  => [
        'numeric'          => ':attribute 必須大於或等於 :min。',
        'file'             => ':attribute 檔案大小至少要 :min KB。',
        'string'           => ':attribute 字串長度至少要 :min 個字元。',
        'array'            => ':attribute 陣列至少要 :min 個元素。',
    ],
    'multiple_of'          => ':attribute 必須為 :value 的倍數。',
    'not_in'               => '您選擇的 :attribute 不在允許範圍。',
    'not_regex'            => ':attribute 格式不正確。',
    'numeric'              => ':attribute 必須是數字。',
    'password' => [
        'letters'          => ':attribute 應包含英文字母。',
        'mixed'            => ':attribute 應包含大寫及小寫英文字母。',
        'numbers'          => ':attribute 應包含數字。',
        'symbols'          => ':attribute 應包含特殊符號。',
        'uncompromised'    => '您輸入的 :attribute 已經資料外洩，請選擇不同的 :attribute。',
    ],
    'present'              => ':attribute 可以留白。',
    'prohibited'           => ':attribute 欄位應留白。',
    'prohibited_if'        => '當 :other 為 :value 時，:attribute 欄位應留白。',
    'prohibited_unless'    => '除非 :other 為 :value，否則 :attribute 欄位應留白。',
    'prohibits'            => '若 :attribute 欄位已被填寫， :other 欄位應留白。',
    'regex'                => ':attribute 格式不正確。',
    'required'             => ':attribute 是必填欄位。',
    'required_array_keys'  => ':attribute 欄位必須包含： :values。',
    'required_if'          => '如果 :other 是 :value，那麼請填寫 :attribute 欄位。',
    'required_unless'      => '除非 :other 已經在 :values 中，否則請填寫 :attribute 欄位。',
    'required_with'        => '如果您已填寫 :values ，那麼也請一併填寫 :attribute 欄位。',
    'required_with_all'    => '如果您已填寫 :values 其中任何一個欄位，那麼也請一併填寫 :attribute 欄位。',
    'required_without'     => '如果您未填寫 :values ，那麼請填寫 :attribute 欄位。',
    'required_without_all' => '如果您未填寫 :values 其中任何一個欄位，那麼就請填寫 :attribute 欄位。',
    'same'                 => ':attribute 和 :other 必須相同。',
    'size'                 => [
        'array'            => ':attribute 陣列必須包含 :size 個元素。',
        'file'             => ':attribute 檔案必須為 :size KB。',
        'numeric'          => ':attribute 必須等於 :size。',
        'string'           => ':attribute 字串長度必須等於 :size 個字元。',
    ],
    'starts_with'          => ':attribute 的開頭必須包含這些字元： :values。',
    'string'               => ':attribute 必須是一個字串。',
    'timezone'             => ':attribute 時區不正確。',
    'unique'               => ':attribute 已經被別人使用。',
    'uploaded'             => ':attribute 上傳失敗。',
    'url'                  => ':attribute 網址格式不正確。',
    'uuid'                 => ':attribute 必須符合 UUID 字串格式。',

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
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
