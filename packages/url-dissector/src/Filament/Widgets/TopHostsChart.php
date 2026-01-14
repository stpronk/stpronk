<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Stpronk\UrlDissector\Services\UrlAnalyticsService;

class TopHostsChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Top 10 Hosts';
    }

    protected function getData(): array
    {
        $analytics = app(UrlAnalyticsService::class);
        $topHosts = $analytics->topHosts(10);

        return [
            'datasets' => [
                [
                    'label' => 'URL Count',
                    'data' => $topHosts->pluck('count')->toArray(),
                ],
            ],
            'labels' => $topHosts->map(fn ($h) => $h->host->full_host)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
