<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Stpronk\UrlDissector\Services\UrlAnalyticsService;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Models\Host;
use Stpronk\UrlDissector\Models\Path;

class UrlStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $analytics = app(UrlAnalyticsService::class);

        return [
            Stat::make('Total URLs', Url::count()),
            Stat::make('Unique Hosts', Host::count()),
            Stat::make('Unique Paths', Path::count()),
            Stat::make('Avg Path Depth', number_format($analytics->averagePathDepth(), 2)),
        ];
    }
}
