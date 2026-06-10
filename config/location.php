<?php

return [
    'driver' => App\Services\Visitors\LocationDrivers\FastIpApi::class,

    'fallbacks' => [],

    'position' => Stevebauman\Location\Position::class,

    'maxmind' => [
        'web' => [
            'enabled' => false,
            'user_id' => '',
            'license_key' => '',
            'options' => [
                'host' => 'geoip.maxmind.com',
            ],
        ],
        'local' => [
            'type' => 'city',
            'path' => database_path('maxmind/GeoLite2-City.mmdb'),
        ],
    ],

    'ip_api' => [
        'token' => env('IP_API_TOKEN'),
    ],

    'ipinfo' => [
        'token' => env('IPINFO_TOKEN'),
    ],

    'ipdata' => [
        'token' => env('IPDATA_TOKEN'),
    ],

    'kloudend' => [
        'token' => env('KLOUDEND_TOKEN'),
    ],

    'testing' => [
        'enabled' => env('LOCATION_TESTING', false),
        'ip' => '66.102.0.0',
    ],
];
