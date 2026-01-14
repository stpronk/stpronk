<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Pages;

use Filament\Pages\Page;
use Stpronk\UrlDissector\UrlDissectorPlugin;
use Stpronk\UrlDissector\Filament\Widgets\UrlStatsOverview;
use Stpronk\UrlDissector\Filament\Widgets\TopHostsChart;
use Stpronk\UrlDissector\Filament\Widgets\PathDepthDistribution;
use Stpronk\UrlDissector\Filament\Widgets\QueryParameterFrequency;
use Stpronk\UrlDissector\Filament\Widgets\SchemeDistribution;
use Stpronk\UrlDissector\Filament\Widgets\UrlsOverTimeChart;

class AnalyticsDashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected string $view = 'url-dissector::filament.pages.analytics-dashboard';

    public static function getNavigationGroup(): ?string
    {
        return UrlDissectorPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return UrlDissectorPlugin::get()->getNavigationSort();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UrlStatsOverview::class,
            UrlsOverTimeChart::class,
            TopHostsChart::class,
            SchemeDistribution::class,
            PathDepthDistribution::class,
            QueryParameterFrequency::class,
        ];
    }
}
