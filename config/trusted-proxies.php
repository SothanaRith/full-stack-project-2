<?php

$configuredProxies = array_values(array_filter(array_map(
    'trim',
    explode(',', (string) env('TRUSTED_PROXIES', '')),
)));

$isRailway = (string) env('RAILWAY_ENVIRONMENT_ID', '') !== '';

return [
    // Railway's proxy addresses are dynamic. REMOTE_ADDR tells Laravel to trust
    // only the immediate platform proxy for each request, rather than a wildcard.
    'proxies' => $configuredProxies ?: ($isRailway ? ['REMOTE_ADDR'] : []),

    'headers' => env('TRUSTED_PROXY_HEADERS', 'x-forwarded'),
];
