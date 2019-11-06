<?php

return [
    'alipay' => [
        'app_id'         => env('ALIPAY_APP_ID'),
        'ali_public_key' => env('ALIPAY_PUBLIC_KEY'),
        'private_key'    => env('ALIPAY_PRIVATE_KEY'),
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => env('WX_APP_ID'),
        'mch_id'      => env('WX_MCH_ID'),
        'key'         => env('WX_KEY'),
        'cert_client' => env('WX_CERT_CLIENT'),
        'cert_key'    => env('WX_CERT_KEY'),
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];