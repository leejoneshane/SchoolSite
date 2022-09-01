<?php

return [

    'verify' => env('SUBSCRIBERS_VERIFY', false),
    
    /*
     |--------------------------------------------------------------------------
     | Notifications Mail Messages
     |--------------------------------------------------------------------------
     |
     */
    'mail' => [
        'verify' => [
            'expiration' => 60,
            'subject' => '國語實小E化服務電子報訂閱驗證信',
            'greeting' => '親愛的訂閱者，您好：',
            'content' => [
                '電子報可能會因為學校行程安排調整出刊日期，系統並不會特別通知，如有造成不便還請見諒！',
                '請務必點擊下方的按鈕，以便驗證您的電子郵件信箱。若未通過驗證，將不會寄發電子報！',
            ],
            'action' => '驗證我的電子郵件',
            'footer' => [
                '如果您並未訂閱我們的電子報，請直接刪除本信件，不要點擊「驗證我的電子郵件」按鈕！',
                '本信件由系統自動寄發，請勿直接回信，若有任何疑問，歡迎來電查詢，請洽國語實小資訊組。',
            ],
        ]
    ]

];