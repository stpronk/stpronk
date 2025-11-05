<?php

namespace Stpronk\Assets\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class AssetsPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'stpronk-assets';
    }

    public function register(Panel $panel): void
    {
        // Discover clusters with resources within this package
        $panel->discoverClusters(
            in: base_path('packages/Assets/src/Filament/Clusters'),
            for: 'Stpronk\\Assets\\Filament\\Clusters'
        );
    }

    public function boot(Panel $panel): void
    {
        // No boot logic required for now.
    }
}
