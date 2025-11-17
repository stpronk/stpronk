<?php

namespace Stpronk\Essentials;

use Illuminate\Support\ServiceProvider;

class EssentialsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
//        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'assets');

        $this->publishes([
            __DIR__.'/../config/filament-stpronk-essentials.php' => config_path('filament-stpronk-essentials.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-stpronk-essentials.php', 'filament-stpronk-essentials'
        );
    }
}
