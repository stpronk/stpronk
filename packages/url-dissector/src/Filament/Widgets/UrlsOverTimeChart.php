<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Stpronk\UrlDissector\Services\UrlAnalyticsService;

class UrlsOverTimeChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'URLs Over Time';
    }

    protected function getData(): array
    {
        $analytics = app(UrlAnalyticsService::class);
        $data = $analytics->urlsOverTime();

        return [
            'datasets' => [
                [
                    'label' => 'Parsed URLs',
                    'data' => $data->pluck('count')->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
