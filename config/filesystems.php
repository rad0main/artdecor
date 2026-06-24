<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        'yandex-cloud' => [
            'driver' => 's3',
            'key' => env('YC_KEY_ID'),
            'secret' => env('YC_KEY_SECRET'),
            'region' => env('YC_REGION', 'ru-central1'),
            'bucket' => env('YC_BUCKET'),
            'url' => env('YC_CDN_URL'),
            'endpoint' => 'https://storage.yandexcloud.net',
            'use_path_style_endpoint' => true,
            'throw' => false,
        ],
        'yandex' => [
            'driver' => 'local',
            'root' => storage_path('app/yandex'),
        ],
    ],
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
