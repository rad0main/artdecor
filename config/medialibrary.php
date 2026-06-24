<?php

return [
    'disk_name' => env('MEDIA_DISK', 'public'),
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,
    'image_generators' => [
        Spatie\MediaLibrary\Conversions\ImageGenerators\Image::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Webp::class,
    ],
    'queue_conversions_by_default' => true,
    'default_filesystem' => env('MEDIA_DISK', 'public'),
    'remote' => [
        'extra_headers' => [
            'CacheControl' => 'public, max_age=31536000',
        ],
    ],
];
