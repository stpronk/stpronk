<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Services;

use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Models\QueryParameter;
use Stpronk\UrlDissector\Jobs\ParseUrlJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UrlDissectorService
{
    public function __construct(
        protected HostParserService $hostParser,
        protected PathParserService $pathParser
    ) {}

    public function validate(string $url): bool
    {
        return Validator::make(['url' => $url], ['url' => 'required|url'])->passes();
    }

    public function normalize(string $url): string
    {
        $parts = parse_url($url);

        if (!$parts) {
            return $url;
        }

        $scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : config('url-dissector.parsing.default_scheme', 'https');
        $host = isset($parts['host']) ? strtolower($parts['host']) : '';

        if (config('url-dissector.parsing.extract_www', true)) {
            if (str_starts_with($host, 'www.')) {
                $host = substr($host, 4);
            }
        }

        $path = isset($parts['path']) ? $parts['path'] : '';
        if (config('url-dissector.parsing.remove_trailing_slash', true)) {
            $path = rtrim($path, '/');
        }
        if ($path === '') {
            $path = '/';
        }

        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        return "{$scheme}://{$host}{$port}{$path}{$query}{$fragment}";
    }

    public function parse(string $url): array
    {
        $normalized = $this->normalize($url);
        $parts = parse_url($normalized);

        parse_str($parts['query'] ?? '', $queryParams);

        return [
            'normalized_url' => $normalized,
            'scheme' => $parts['scheme'] ?? null,
            'host' => $parts['host'] ?? null,
            'port' => $parts['port'] ?? null,
            'path' => $parts['path'] ?? '/',
            'query' => $parts['query'] ?? null,
            'query_parameters' => $queryParams,
            'fragment' => $parts['fragment'] ?? null,
        ];
    }

    public function store(string $url, ?Model $urlable = null): Url
    {
        $parsed = $this->parse($url);

        return DB::transaction(function () use ($parsed, $urlable) {
            $host = $this->hostParser->findOrCreate($parsed['host']);
            $path = $this->pathParser->findOrCreate($parsed['path']);

            $urlModel = config('url-dissector.models.url')::updateOrCreate(
                ['normalized_url' => $parsed['normalized_url']],
                [
                    'host_id' => $host->id,
                    'path_id' => $path->id,
                    'scheme' => $parsed['scheme'],
                    'port' => $parsed['port'],
                    'query_string' => $parsed['query'],
                    'fragment' => $parsed['fragment'],
                    'parsed_at' => now(),
                ]
            );

            $urlModel->queryParameters()->delete();

            $order = 0;
            foreach ($parsed['query_parameters'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        config('url-dissector.models.query_parameter')::create([
                            'url_id' => $urlModel->id,
                            'key' => (string) $key,
                            'value' => (string) $v,
                            'order' => $order++,
                        ]);
                    }
                } else {
                    config('url-dissector.models.query_parameter')::create([
                        'url_id' => $urlModel->id,
                        'key' => (string) $key,
                        'value' => (string) $value,
                        'order' => $order++,
                    ]);
                }
            }

            // Re-load relationships for verification
            $urlModel->load(['host', 'path', 'queryParameters']);
            $urlModel->is_valid = app(\Stpronk\UrlDissector\Services\UrlReconstructorService::class)->verify($urlModel);
            $urlModel->save();

            if ($urlable) {
                $urlable->urls()->syncWithoutDetaching([$urlModel->id]);
            }

            app(\Stpronk\UrlDissector\Services\UrlStatusCheckerService::class)->check($urlModel);

            return $urlModel;
        });
    }

    public function dispatch(string $url, ?Model $urlable = null): Url
    {
        $normalized = $this->normalize($url);

        $urlModel = config('url-dissector.models.url')::firstOrCreate(
            ['normalized_url' => $normalized]
        );

        if ($urlable) {
            $urlable->urls()->syncWithoutDetaching([$urlModel->id]);
        }

        ParseUrlJob::dispatch($normalized, $urlable)
            ->onConnection(config('url-dissector.queue.connection'))
            ->onQueue(config('url-dissector.queue.queue'));

        return $urlModel;
    }

    public function bulkStore(array $urls, bool $queued = false): Collection
    {
        $results = collect();
        foreach ($urls as $url) {
            try {
                if ($queued) {
                    $this->dispatch($url);
                } else {
                    $results->push($this->store($url));
                }
            } catch (\Exception $e) {
                // Skip or handle error
            }
        }
        return $results;
    }
}
