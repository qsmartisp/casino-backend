<?php

return [
    'base_url' => env('ESTCHANGE_BASE_URL', 'https://assets.estchange.io/'),
    'api_key' => env('ESTCHANGE_API_KEY'),
    'log' => [
        'level' => env('ESTCHANGE_LOG_LEVEL', 'debug'),
        'prefix' => env('ESTCHANGE_LOG_PREFIX', '[ESTCHANGE LOG] ' . PHP_EOL),
        'template' => env('ESTCHANGE_LOG_TEMPLATE', \GuzzleHttp\MessageFormatter::DEBUG),
    ],
];
