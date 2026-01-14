<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Services;

use Pdp\Rules;
use Pdp\Domain;
use Stpronk\UrlDissector\Models\Host;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HostParserService
{
    protected ?Rules $rules = null;

    public function parse(string $host): array
    {
        $host = strtolower($host);

        try {
            $rules = $this->getRules();
            $result = $rules->resolve($host);

            return [
                'full_host' => $host,
                'subdomain' => $result->subdomain()->value(),
                'domain' => $result->secondLevelDomain()->value() ?? $host,
                'tld' => $result->suffix()->value() ?? '',
            ];
        } catch (\Exception $e) {
            // Fallback for when rules are not available or parsing fails
            $parts = explode('.', $host);
            $count = count($parts);

            if ($count >= 2) {
                return [
                    'full_host' => $host,
                    'subdomain' => $count > 2 ? implode('.', array_slice($parts, 0, $count - 2)) : null,
                    'domain' => $parts[$count - 2],
                    'tld' => $parts[$count - 1],
                ];
            }

            return [
                'full_host' => $host,
                'subdomain' => null,
                'domain' => $host,
                'tld' => '',
            ];
        }
    }

    public function findOrCreate(string $host): Host
    {
        $parsed = $this->parse($host);

        return config('url-dissector.models.host')::firstOrCreate(
            ['full_host' => $parsed['full_host']],
            [
                'domain' => $parsed['domain'],
                'tld' => $parsed['tld'],
                'subdomain' => $parsed['subdomain'],
            ]
        );
    }

    protected function getRules(): Rules
    {
        if ($this->rules !== null) {
            return $this->rules;
        }

        $cacheKey = 'url-dissector-pdp-rules';
        $rulesContent = Cache::get($cacheKey);

        if (!$rulesContent) {
            // In a real scenario, we might want to ship this with the package or download it
            // For now, we'll try to download it once or use a local copy if we had one
            try {
                $response = Http::get('https://publicsuffix.org/list/public_suffix_list.dat');
                if ($response->successful()) {
                    $rulesContent = $response->body();
                    Cache::put($cacheKey, $rulesContent, 86400 * 7); // 1 week
                }
            } catch (\Exception $e) {
                // If download fails, we might have to throw or use fallback
            }
        }

        if ($rulesContent) {
            $this->rules = Rules::fromString($rulesContent);
            return $this->rules;
        }

        throw new \RuntimeException('Unable to load Public Suffix List rules');
    }
}
