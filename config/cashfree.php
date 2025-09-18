<?php

return [
    'enabled' => env('CASHFREE_ENABLED', true),
    'app_id' => env('CASHFREE_APP_ID'),
    'secret_key' => env('CASHFREE_SECRET_KEY'),
    'environment' => env('CASHFREE_ENV', 'sandbox'),
    'api_version' => env('CASHFREE_API_VERSION', '2022-09-01'),
    'base_urls' => [
        'sandbox' => 'https://sandbox.cashfree.com/pg',
        'production' => 'https://api.cashfree.com/pg',
    ],
    'order_prefix' => env('CASHFREE_ORDER_PREFIX', 'ONELINK'),
    'return_url' => env('CASHFREE_RETURN_URL'),
];
