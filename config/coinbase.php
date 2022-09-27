<?php

return [
    'base_url' => env('COINBASE_BASE_URL', 'https://api.commerce.coinbase.com/'),
    'api_version' => env('COINBASE_API_VERSION', '2018-03-22'),
    'api_key' => env('COINBASE_API_KEY'),
    'hmac_secret' => env('COINBASE_HMAC_SECRET'),
    'log' => [
        'level' => env('COINBASE_LOG_LEVEL', 'debug'),
        'prefix' => env('COINBASE_LOG_PREFIX', '[COINBASE LOG] ' . PHP_EOL),
        'template' => env('COINBASE_LOG_TEMPLATE', \GuzzleHttp\MessageFormatter::DEBUG),
    ],
];
