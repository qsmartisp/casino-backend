<?php

return [
    'urls' => [
        'base' => env('SIMPLEPAY_BASE_URL', 'https://api-sbox.simplepay.asia/merchant/'),
    ],
    'services' => [
        'qrcode' => [
            'service_id' => env('SIMPLEPAY_QRCODE_SERVICE_ID', 168),
        ],
    ],
    'credentials' => [
        'api' => [
            'key' => env('SIMPLEPAY_API_KEY', ''),
            'secret_key' => env('SIMPLEPAY_API_SECRET_KEY', ''),
        ],
        'webhook' => [
            'signing_secret_key' => env('SIMPLEPAY_WEBHOOK_SIGNING_SECRET_KEY', ''),
        ],
    ],
    'log' => [
        'level' => env('SIMPLEPAY_LOG_LEVEL', 'debug'),
        'prefix' => env('SIMPLEPAY_LOG_PREFIX', '[SIMPLEPAY LOG] ' . PHP_EOL),
        'template' => env('SIMPLEPAY_LOG_TEMPLATE', \GuzzleHttp\MessageFormatter::DEBUG),
    ],
    'login' => [
        'prefix' => env('SIMPLEPAY_LOGIN_PREFIX', 'SP'),
        'delim' => env('SIMPLEPAY_LOGIN_DELIM', '_'),
    ],
];
