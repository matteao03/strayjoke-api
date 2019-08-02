<?php
return [
    // HTTP ����ĳ�ʱʱ�䣨�룩
    'timeout' => 5.0,

    // Ĭ�Ϸ�������
    'default' => [
        // ���ص��ò��ԣ�Ĭ�ϣ�˳�����
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // Ĭ�Ͽ��õķ�������
        'gateways' => [
            'aliyun',
        ],
    ],
    // ���õ���������
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'aliyun' => [
            'access_key_id' => env('ALIBABASMS_KEY_ID'),
            'access_key_secret' => env('ALIBABASMS_KEY_SECRET'),
            'sign_name' => env('ALIBABASMS_SIGNNAME'),
        ],
    ],
];