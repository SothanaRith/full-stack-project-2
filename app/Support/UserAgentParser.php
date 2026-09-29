<?php

namespace App\Support;

final class UserAgentParser
{
    /**
     * @return array{browser_name: ?string, browser_version: ?string, os_name: ?string, os_version: ?string, device_category: ?string}
     */
    public function parse(?string $userAgent): array
    {
        if ($userAgent === null || trim($userAgent) === '') {
            return $this->emptyResult();
        }

        [$browserName, $browserVersion] = $this->browser($userAgent);
        [$osName, $osVersion] = $this->operatingSystem($userAgent);

        return [
            'browser_name' => $browserName,
            'browser_version' => $browserVersion,
            'os_name' => $osName,
            'os_version' => $osVersion,
            'device_category' => $this->deviceCategory($userAgent),
        ];
    }

    /** @return array{0: ?string, 1: ?string} */
    private function browser(string $userAgent): array
    {
        $patterns = [
            'Microsoft Edge' => '/(?:Edg|EdgiOS|EdgA)\/([\d.]+)/i',
            'Opera' => '/(?:OPR|Opera)\/([\d.]+)/i',
            'Chrome' => '/(?:Chrome|CriOS)\/([\d.]+)/i',
            'Firefox' => '/(?:Firefox|FxiOS)\/([\d.]+)/i',
            'Internet Explorer' => '/(?:MSIE\s|Trident\/.*rv:)([\d.]+)/i',
            'Safari' => '/Version\/([\d.]+).*Safari\//i',
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent, $matches) === 1) {
                return [$name, $matches[1]];
            }
        }

        return [null, null];
    }

    /** @return array{0: ?string, 1: ?string} */
    private function operatingSystem(string $userAgent): array
    {
        if (preg_match('/Windows NT ([\d.]+)/i', $userAgent, $matches) === 1) {
            $versions = ['10.0' => '10 or later', '6.3' => '8.1', '6.2' => '8', '6.1' => '7'];

            return ['Windows', $versions[$matches[1]] ?? $matches[1]];
        }

        if (preg_match('/Android\s+([\d.]+)/i', $userAgent, $matches) === 1) {
            return ['Android', $matches[1]];
        }

        if (preg_match('/(?:iPhone|CPU(?: iPhone)? OS)\s+([\d_]+)/i', $userAgent, $matches) === 1) {
            return ['iOS', str_replace('_', '.', $matches[1])];
        }

        if (preg_match('/Mac OS X\s+([\d_]+)/i', $userAgent, $matches) === 1) {
            return ['macOS', str_replace('_', '.', $matches[1])];
        }

        if (stripos($userAgent, 'Linux') !== false) {
            return ['Linux', null];
        }

        return [null, null];
    }

    private function deviceCategory(string $userAgent): string
    {
        if (preg_match('/bot|crawler|spider|slurp|bingpreview|headlesschrome|facebookexternalhit/i', $userAgent) === 1) {
            return 'bot';
        }

        if (preg_match('/ipad|tablet|kindle|silk|playbook|android(?!.*mobile)/i', $userAgent) === 1) {
            return 'tablet';
        }

        if (preg_match('/mobile|iphone|ipod|android|blackberry|opera mini|iemobile/i', $userAgent) === 1) {
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * @return array{browser_name: null, browser_version: null, os_name: null, os_version: null, device_category: null}
     */
    private function emptyResult(): array
    {
        return [
            'browser_name' => null,
            'browser_version' => null,
            'os_name' => null,
            'os_version' => null,
            'device_category' => null,
        ];
    }
}
