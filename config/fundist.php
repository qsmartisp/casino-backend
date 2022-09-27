<?php

return [
    'ip' => env('CASINO_BACKEND_IP', '193.108.113.21'),
    'base_url' => env('FUNDIST_BASE_URL', 'https://apitest.fundist.org/'),
    'pwd' => env('FUNDIST_PWD', '4271556689179983'),
    'key' => env('FUNDIST_KEY', '3dbb072e6a49b69450c71e4f58129929'),
    'hmac_secret' => env('FUNDIST_HMAC_SECRET', 'dpl6iyuplgnthr5t19wejpnm0gpth34fiynffdr5ycv1ume0xll2aysylv8u8149'),
    'log' => [
        'level' => env('FUNDIST_LOG_LEVEL', 'debug'),
        'prefix' => env('FUNDIST_LOG_PREFIX', '[FUNDIST LOG] ' . PHP_EOL),
        'template' => env('FUNDIST_LOG_TEMPLATE', \GuzzleHttp\MessageFormatter::DEBUG),
    ],
    'login' => [
        'prefix' => env('FUNDIST_LOGIN_PREFIX', 'GG'),
        'delim' => env('FUNDIST_LOGIN_DELIM', '_'),
    ],
    'demo' => [
        'login' => '$DemoUser$',
        'password' => 'Demo',
    ],
];
