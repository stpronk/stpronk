<?php

namespace Stpronk\Todos\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class TodosPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'stpronk-todos';
    }

    public function register(Panel $panel): void
    {
        // Discover clusters with resources within this package
        $panel->discoverClusters(
            in: base_path('packages/Todos/src/Filament/Clusters'),
            for: 'Stpronk\\Todos\\Filament\\Clusters'
        );
    }

    public function boot(Panel $panel): void
    {
        // No boot logic required for now.
    }
}
