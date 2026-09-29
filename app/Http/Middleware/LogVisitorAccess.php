<?php

namespace App\Http\Middleware;

use App\Jobs\RecordVisitorAccess;
use App\Support\UserAgentParser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogVisitorAccess
{
    private const STARTED_AT = '_visitor_access_started_at';

    private const STARTED_NS = '_visitor_access_started_ns';

    public function handle(Request $request, Closure $next): Response
    {
        if (config('visitor-access.enabled')) {
            $request->attributes->set(self::STARTED_AT, now());
            $request->attributes->set(self::STARTED_NS, hrtime(true));
        }

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (! config('visitor-access.enabled') || $this->excluded($request)) {
            return;
        }

        $startedAt = $request->attributes->get(self::STARTED_AT);
        $startedNs = $request->attributes->get(self::STARTED_NS);

        if ($startedAt === null || ! is_int($startedNs)) {
            return;
        }

        $userAgent = $request->userAgent();
        $parsedAgent = app(UserAgentParser::class)->parse($userAgent);

        RecordVisitorAccess::dispatch([
            'accessed_at' => $startedAt,
            'ip_address' => $request->ip(),
            'http_method' => $request->method(),
            'route_name' => $request->route()?->getName(),
            'url_path' => $this->safePath($request),
            'response_status' => $response->getStatusCode(),
            'referrer_domain' => $this->referrerDomain($request->headers->get('referer')),
            'user_agent' => $userAgent ?: null,
            ...$parsedAgent,
            'user_id' => $request->user()?->getAuthIdentifier(),
            'duration_ms' => max(0, (int) round((hrtime(true) - $startedNs) / 1_000_000)),
        ])->onQueue(config('visitor-access.queue'));
    }

    private function excluded(Request $request): bool
    {
        foreach (config('visitor-access.exclude.paths', []) as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        $routeName = $request->route()?->getName();

        foreach (config('visitor-access.exclude.route_names', []) as $pattern) {
            if ($routeName !== null && Str::is($pattern, $routeName)) {
                return true;
            }
        }

        $extension = strtolower((string) pathinfo($request->path(), PATHINFO_EXTENSION));

        return $extension !== '' && in_array($extension, config('visitor-access.exclude.extensions', []), true);
    }

    private function referrerDomain(?string $referrer): ?string
    {
        if ($referrer === null || $referrer === '') {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        return is_string($host) ? Str::limit(strtolower($host), 255, '') : null;
    }

    private function safePath(Request $request): string
    {
        $path = '/'.ltrim($request->path(), '/');
        $parameters = $request->route()?->parameters() ?? [];

        foreach (config('visitor-access.redacted_route_parameters', []) as $name) {
            $value = $parameters[$name] ?? null;

            if (! is_scalar($value) || (string) $value === '') {
                continue;
            }

            $value = (string) $value;
            $path = str_replace([$value, rawurlencode($value), urlencode($value)], '[redacted]', $path);
        }

        return $path;
    }
}
