<?php

return [
    'current_service' => null,
    'services' => [
        'admin' => [
            '<service_name>' => [
                'name' => env('INTERNAL_<NAME>_SERVICE', '<name>'),
                'url' => 'http://nginx/<service>/',
                'token' => env('INTERNAL_<NAME>_SERVICE_TOKEN', 'e21c89n18yc2b0192eby8'),
            ]
        ],

        '<service_name>' => [
            'name' => env('INTERNAL_<NAME>_SERVICE', '<name>'),
            'url' => 'http://nginx/<service>/',
            'token' => env('INTERNAL_<NAME>_SERVICE_TOKEN', 'e21c89n18yc2b0192eby8'),
        ]
    ]
];