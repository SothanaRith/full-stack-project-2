<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /** @return array<int, string> */
    protected function proxies(): array
    {
        $proxies = config('trusted-proxies.proxies', []);

        // These impossible source addresses prevent Laravel's host-based proxy
        // auto-detection from turning into implicit wildcard trust.
        return $proxies ?: ['0.0.0.0/32', '::/128'];
    }

    protected function headers(): int
    {
        return match (config('trusted-proxies.headers')) {
            'aws-elb' => Request::HEADER_X_FORWARDED_AWS_ELB,
            'forwarded' => Request::HEADER_FORWARDED,
            default => Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_PREFIX,
        };
    }
}
