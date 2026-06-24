<?php

return [
    'default' => env('CACHE_DRIVER', 'redis'),
    'prefix' => env('CACHE_PREFIX', 'artdecor_cache'),
    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
    ],
];
