<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Stpronk\UrlDissector\Services\UrlAnalyticsService;

class PathDepthDistribution extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Path Depth Distribution';
    }

    protected function getData(): array
    {
        $analytics = app(UrlAnalyticsService::class);
        $distribution = $analytics->pathDepthDistribution();

        return [
            'datasets' => [
                [
                    'label' => 'URL Count',
                    'data' => array_values($distribution),
                ],
            ],
            'labels' => array_keys($distribution),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
