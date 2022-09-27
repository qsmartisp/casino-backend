<?php

return [
    'urls' => [
        'base' => env('BGAMING_BASE_URL', 'https://int.bgaming-system.com/a8r/letterm-int/'),
        'deposit' => env('BGAMING_DEPOSIT_URL', 'https://cryptobosscasino.com/profile/wallet/deposit'),
        'return' => env('BGAMING_RETURN_URL', 'https://cryptobosscasino.com/'),
    ],
    'auth' => [
        'token' => env('BGAMING_AUTH_TOKEN', ''),
    ],
    'casino' => [
        'id' => env('BGAMING_CASINO_ID', 'letterm-int'),
    ],
    'log' => [
        'level' => env('BGAMING_LOG_LEVEL', 'debug'),
        'prefix' => env('BGAMING_LOG_PREFIX', '[BGAMING LOG] ' . PHP_EOL),
        'template' => env('BGAMING_LOG_TEMPLATE', \GuzzleHttp\MessageFormatter::DEBUG),
    ],
    'login' => [
        'prefix' => env('BGAMING_LOGIN_PREFIX', 'CC'),
        'delim' => env('BGAMING_LOGIN_DELIM', '_'),
    ],
];
