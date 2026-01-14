<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Stpronk\UrlDissector\Services\UrlAnalyticsService;

class SchemeDistribution extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Scheme Distribution';
    }

    protected function getData(): array
    {
        $analytics = app(UrlAnalyticsService::class);
        $distribution = $analytics->schemeDistribution();

        return [
            'datasets' => [
                [
                    'label' => 'URL Count',
                    'data' => array_values($distribution),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                ],
            ],
            'labels' => array_keys($distribution),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
