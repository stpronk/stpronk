<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Services;

use Stpronk\UrlDissector\Models\Url;
use Illuminate\Support\Facades\Cache;

class UrlReconstructorService
{
    public function getComponents(int | Url $url): array
    {
        if (is_int($url)) {
            $url = config('url-dissector.models.url')::with(['host', 'path', 'queryParameters'])->findOrFail($url);
        }

        if (!$url->relationLoaded('host') || !$url->relationLoaded('path') || !$url->relationLoaded('queryParameters')) {
            $url->load(['host', 'path', 'queryParameters']);
        }

        return [
            'scheme' => $url->scheme,
            'host' => $url->host?->full_host,
            'port' => $url->port,
            'path' => $url->path?->full_path,
            'query_parameters' => $url->queryParameters->pluck('value', 'key')->toArray(),
            'fragment' => $url->fragment,
        ];
    }

    public function rebuild(int | Url $url): string
    {
        $urlId = is_int($url) ? $url : $url->id;
        $cacheEnabled = config('url-dissector.cache.enabled', true);
        $cacheKey = config('url-dissector.cache.prefix', 'url_dissector_') . 'rebuild_' . $urlId;

        if ($cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $components = $this->getComponents($url);
        $reconstructedUrl = $this->rebuildFromComponents($components);

        if ($cacheEnabled) {
            Cache::put($cacheKey, $reconstructedUrl, config('url-dissector.cache.ttl', 3600));
        }

        return $reconstructedUrl;
    }

    public function rebuildFromComponents(array $components): string
    {
        $scheme = $components['scheme'] ?? 'https';
        $host = $components['host'] ?? '';
        $port = isset($components['port']) && $components['port'] !== null ? ':' . $components['port'] : '';
        $path = $components['path'] ?? '/';

        $query = '';
        if (!empty($components['query_parameters'])) {
            $query = '?' . http_build_query($components['query_parameters']);
        } elseif (!empty($components['query'])) {
            $query = '?' . ltrim($components['query'], '?');
        }

        $fragment = isset($components['fragment']) && $components['fragment'] !== null ? '#' . ltrim($components['fragment'], '#') : '';

        return "{$scheme}://{$host}{$port}{$path}{$query}{$fragment}";
    }

    public function verify(int | Url $url): bool
    {
        if (is_int($url)) {
            $url = Url::findOrFail($url);
        }

        $reconstructed = $this->rebuild($url);

        // We compare with the normalized URL, but normalization might have changed it
        // So we should probably compare with a normalized version of the original
        $dissector = app(UrlDissectorService::class);
        return $dissector->normalize($url->normalized_url) === $dissector->normalize($reconstructed);
    }
}
