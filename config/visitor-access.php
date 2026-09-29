<?php

return [
    'enabled' => env('VISITOR_ACCESS_LOG_ENABLED', true),

    'queue' => env('VISITOR_ACCESS_LOG_QUEUE', 'default'),

    'retention_days' => (int) env('VISITOR_ACCESS_LOG_RETENTION_DAYS', 90),

    'redacted_route_parameters' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('VISITOR_ACCESS_LOG_REDACTED_ROUTE_PARAMETERS', 'token,hash,secret,key,password')),
    ))),

    'exclude' => [
        'paths' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env(
                'VISITOR_ACCESS_LOG_EXCLUDED_PATHS',
                'up,build/*,storage/*,favicon.ico,robots.txt',
            )),
        ))),

        'route_names' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env(
                'VISITOR_ACCESS_LOG_EXCLUDED_ROUTES',
                'visitor-access-logs.*',
            )),
        ))),

        'extensions' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env(
                'VISITOR_ACCESS_LOG_EXCLUDED_EXTENSIONS',
                'css,js,map,jpg,jpeg,png,gif,svg,webp,ico,woff,woff2,ttf,eot',
            )),
        ))),
    ],
];
