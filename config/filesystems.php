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
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        'attachments' => [
            'driver' => 'local',
            'root' => storage_path('app/attachments'),
            'url' => env('APP_URL').'/storage/attachments',
            'visibility' => 'public',
            'throw' => false,
        ],
    ],
];

