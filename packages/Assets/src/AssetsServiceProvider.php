<?php

namespace Stpronk\Assets;

use Illuminate\Support\ServiceProvider;

class AssetsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'assets');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'stpronk-filament-assets');

        $this->publishes([
            __DIR__.'/../config/filament-assets.php' => config_path('filament-assets.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-assets.php', 'filament-assets'
        );
    }
}
