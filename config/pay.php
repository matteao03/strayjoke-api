<?php

return [
    'alipay' => [
        'app_id'         => '2016092300575725',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxq/299oPdlgBZKawOL274+tbpxJpaLQowL377AbZMdbLKy7VHNL30TmzYHq3XIj6TMfHorOUeQxzl8iHzfbbWP5i6sJrd7PZrf5cq5d0PrUsiq1apZhBA4/2NaJJ8kKH1UNXPLRZTDtMfr3/vlB2G1yuB2wI3j/pre+rMimZEFNWvBoEmXsG84PpYZgh43wjAZo2+tkfOmwQoF3LKQMwJgE7CNbHJ8SYXvKA75e3pZsdymQLNpn2yhbaJKk5hFddG1U81NQCDvzJ9Bt5nFHpPH+jOrdiBj4boJ5vGRd8Yt1B5oHyiFifGubx8KgMvYFdD71QuargXtp6+V2V8guD+QIDAQAB',
        'private_key'    => 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCQUUyWsrzvNuaNB3nXlakSs/rJ2ZWodqK6RTr4rXa1ZSb24Fsy/3R5oXEQW5VAtmqud8Ni4RLli0H14RQsDmiOFvrlCYNLgOKq38wyTB/POs54jLxzHKwZvdsGEyvHayhJIx9FYL9NaNSaT2+OP/qYSIH3nwQrrTMVmHT88YZltFsjBUzVFAzvfEdKRW4UXPl/c13f1lEqRhsu4NAxEoVjGdc8iH2pooRGg4z4AY16kC8oIanFXYO9I4X2kwF9P5eAO1dT73p7RvvT4G0On6U635RMlwiDWElkgx0iXWmBJxVsbHu5PNFT13PB2HcA+/XC7A90O1fBOOa6AazEM3JzAgMBAAECggEAT9u8/tLvO4vsrfOKF6KoSUIwr4tohi0HxP1UMZGDU8MieZASxziWkkukjgGvqHyIsfxxsQCM/Vo+6Llg+TQL5TyQHzdRYPFz/EwU6Ww4WerXn9t42FXwYdouHcF0A0inpTj7L9/mXAZ7RtxYBaYfnC7bRFPI8gZIc1XGvsVDsnjw/CY0PrF0o9kQgosmkLtXmowGslpSzQOTJhu299XsJvS9W700JemWeDenGfHMnCGpRPYsy/Kb7VtFeQr9OB+bnTQO0DoUqp3r61LuQ00TKrXA9h0dDIQvj/LmWQeph/jsVW+yminfXTSYHm9LI1pJmwGbrEZaLbw4Va3O/B5XwQKBgQDdBPzrO8hqFqj3HO8a0VA3PQwz/w6ClsN4l2clyrkYA9VA+xce7mJ5+8vfGGDmGY2vqJY0VBOVn+fWzBlgjFRXS8KgNKCPLaZpDm0thQXnKIvkJt+6XZDmAMCF4kzKBpKYjC55FW0Ut/p5jk8TDYgncfetVmyW51FktBeM7ZW14QKBgQCnKJeGHATUnKCKkOp7W9P+bN+ZFc8FMpB6GkhARMiqVfb4KaKsA6EkpSFUyjmUTG4wh7rwty+EA/PzTHDCsnfHc9gFUIEVC824SCiO6n32H2wUaplCqGu9H+eCEPWME2hJzrpvQL9ByULINxzduUn+sMRVezw6/rjW4b/gs6vK0wKBgQC+G5M89wAtGG6fl3inNZFs4grEEsg1VV6vNHOZkyTgXjOpIBDEH0H/MLEspTh701EG3djNC+CIm7F8FbRiUnIdYGH5EStl3Fs+FBWeyMPKEBs71KAuGlsPK2huALgSMdMYecNjjSV1Y8aDlf+4ILSTUTk8FJF0v2VIXIvBQX67oQKBgQCcWq6oxcqK6NqN4hFCQ9mekU3wzmJvFSXo4G6WlwAvu+sfoypCxb5UHZV4zxNesMMc58inYmGylVP5TpXmt7KsQKJeDjg/bGQHrI8rZxFdR3T7/93NaOYl6BLPKXfUh060QwdGCwUaztuFlW+NwcQB1GxBHN1wvVQ9wrmMv+K/+wKBgAz066QwgoJSu0GAmtlzUiPzUau1fHFY2BxBkSA0V6WKzhw4S0WCnw3K1vhYhTO3I7BMDS9ADsBqcEW8QIel9zy7jOiBZKJbS5FvnB67zN1+c23Lt7XGrEq3ssTRZB9fqKK9a64gf36AZJLRg2FXd95wv0vTaP5lwFoIU+Y5ma+d',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];