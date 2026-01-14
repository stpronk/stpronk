<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Stpronk\UrlDissector\Filament\Resources\UrlResource;
use Stpronk\UrlDissector\Filament\Resources\HostResource;
use Stpronk\UrlDissector\Filament\Resources\PathResource;
use Stpronk\UrlDissector\Filament\Pages\AnalyticsDashboard;
use Stpronk\UrlDissector\Filament\Widgets\UrlStatsOverview;
use Stpronk\UrlDissector\Filament\Widgets\TopHostsChart;
use Stpronk\UrlDissector\Filament\Widgets\PathDepthDistribution;
use Stpronk\UrlDissector\Filament\Widgets\QueryParameterFrequency;
use Stpronk\UrlDissector\Filament\Widgets\SchemeDistribution;
use Stpronk\UrlDissector\Filament\Widgets\UrlsOverTimeChart;

class UrlDissectorPlugin implements Plugin
{
    protected bool $hasUrlResource = true;
    protected bool $hasHostResource = true;
    protected bool $hasPathResource = true;
    protected bool $hasWidgets = true;
    protected ?array $widgets = null;
    protected ?string $navigationGroup = 'URL Analytics';
    protected ?int $navigationSort = 100;

    public function __construct()
    {
        $this->hasUrlResource = (bool) config('url-dissector.filament.resources.url', true);
        $this->hasHostResource = (bool) config('url-dissector.filament.resources.host', true);
        $this->hasPathResource = (bool) config('url-dissector.filament.resources.path', true);
        $this->navigationGroup = (string) config('url-dissector.filament.navigation.group', 'URL Analytics');
        $this->navigationSort = (int) config('url-dissector.filament.navigation.sort', 100);
    }

    public function getId(): string
    {
        return 'url-dissector';
    }

    public static function make(): static
    {
        return new static();
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament('url-dissector');

        return $plugin;
    }

    public function register(Panel $panel): void
    {
        $resources = [];
        if ($this->hasUrlResource) $resources[] = UrlResource::class;
        if ($this->hasHostResource) $resources[] = HostResource::class;
        if ($this->hasPathResource) $resources[] = PathResource::class;

        $panel->resources($resources);
        $panel->pages([
            AnalyticsDashboard::class,
        ]);

        if ($this->hasWidgets) {
            $panel->widgets($this->widgets ?? [
                UrlStatsOverview::class,
                TopHostsChart::class,
                PathDepthDistribution::class,
                QueryParameterFrequency::class,
                SchemeDistribution::class,
                UrlsOverTimeChart::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function withoutUrlResource(): static
    {
        $this->hasUrlResource = false;
        return $this;
    }

    public function withoutHostResource(): static
    {
        $this->hasHostResource = false;
        return $this;
    }

    public function withPathResource(): static
    {
        $this->hasPathResource = true;
        return $this;
    }

    public function withoutPathResource(): static
    {
        $this->hasPathResource = false;
        return $this;
    }

    public function withoutWidgets(): static
    {
        $this->hasWidgets = false;
        return $this;
    }

    public function widgets(array $widgets): static
    {
        $this->widgets = $widgets;
        return $this;
    }

    public function navigationGroup(string $group): static
    {
        $this->navigationGroup = $group;
        return $this;
    }

    public function navigationSort(int $sort): static
    {
        $this->navigationSort = $sort;
        return $this;
    }

    public function tablePrefix(string $prefix): static
    {
        config(['url-dissector.table_prefix' => $prefix]);
        return $this;
    }

    public function enableCache(bool $enabled = true): static
    {
        config(['url-dissector.cache.enabled' => $enabled]);
        return $this;
    }

    public function queueParsing(bool $enabled = true): static
    {
        config(['url-dissector.queue.enabled' => $enabled]);
        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public function getNavigationSort(): ?int
    {
        return $this->navigationSort;
    }
}
