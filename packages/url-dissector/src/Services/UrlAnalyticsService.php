<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Services;

use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Models\Host;
use Stpronk\UrlDissector\Models\Path;
use Stpronk\UrlDissector\Models\QueryParameter;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UrlAnalyticsService
{
    protected function getCacheKey(string $key, array $params = []): string
    {
        return config('url-dissector.cache.prefix', 'url_dissector_') . 'analytics_' . $key . '_' . md5(serialize($params));
    }

    protected function remember(string $key, array $params, \Closure $callback)
    {
        if (!config('url-dissector.analytics.cache_results', true)) {
            return $callback();
        }

        return Cache::remember(
            $this->getCacheKey($key, $params),
            config('url-dissector.analytics.cache_ttl', 1800),
            $callback
        );
    }

    public function topHosts(int $limit = 10, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        return $this->remember('top_hosts', [$limit, $startDate, $endDate], function () use ($limit, $startDate, $endDate) {
            $query = config('url-dissector.models.url')::query()
                ->select('host_id', DB::raw('count(*) as count'))
                ->groupBy('host_id')
                ->orderByDesc('count')
                ->limit($limit)
                ->with('host');

            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }

            return $query->get();
        });
    }

    public function topPaths(int $limit = 10, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        return $this->remember('top_paths', [$limit, $startDate, $endDate], function () use ($limit, $startDate, $endDate) {
            $query = config('url-dissector.models.url')::query()
                ->select('path_id', DB::raw('count(*) as count'))
                ->groupBy('path_id')
                ->orderByDesc('count')
                ->limit($limit)
                ->with('path');

            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }

            return $query->get();
        });
    }

    public function pathDepthDistribution(): array
    {
        return $this->remember('path_depth_distribution', [], function () {
            return config('url-dissector.models.path')::query()
                ->select('depth', DB::raw('count(*) as count'))
                ->groupBy('depth')
                ->orderBy('depth')
                ->pluck('count', 'depth')
                ->toArray();
        });
    }

    public function queryParameterFrequency(): Collection
    {
        return $this->remember('query_parameter_frequency', [], function () {
            return config('url-dissector.models.query_parameter')::query()
                ->select('key', DB::raw('count(*) as count'))
                ->groupBy('key')
                ->orderByDesc('count')
                ->limit(20)
                ->get();
        });
    }

    public function hostsByTld(): Collection
    {
        return $this->remember('hosts_by_tld', [], function () {
            return config('url-dissector.models.host')::query()
                ->select('tld', DB::raw('count(*) as count'))
                ->groupBy('tld')
                ->orderByDesc('count')
                ->get();
        });
    }

    public function schemeDistribution(): array
    {
        return $this->remember('scheme_distribution', [], function () {
            return config('url-dissector.models.url')::query()
                ->select('scheme', DB::raw('count(*) as count'))
                ->groupBy('scheme')
                ->pluck('count', 'scheme')
                ->toArray();
        });
    }

    public function urlsOverTime(string $interval = 'day'): Collection
    {
        return $this->remember('urls_over_time', [$interval], function () use ($interval) {
            $format = match ($interval) {
                'hour' => '%Y-%m-%d %H:00',
                'day' => '%Y-%m-%d',
                'week' => '%x-%v',
                'month' => '%Y-%m',
                default => '%Y-%m-%d',
            };

            // This is MySQL specific, for SQLite/Postgres we might need different syntax
            // Since requirements say it must work with SQLite, MySQL, PostgreSQL, I should be careful.

            $driver = DB::getDriverName();
            $dateFunc = match ($driver) {
                'sqlite' => "strftime('%Y-%m-%d', created_at)",
                'pgsql' => "to_char(created_at, 'YYYY-MM-DD')",
                default => "DATE_FORMAT(created_at, '{$format}')",
            };

            return config('url-dissector.models.url')::query()
                ->select(DB::raw("{$dateFunc} as date"), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        });
    }

    public function averagePathDepth(): float
    {
        return (float) $this->remember('average_path_depth', [], function () {
            return config('url-dissector.models.path')::query()->avg('depth') ?? 0.0;
        });
    }
}
