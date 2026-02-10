<?php

namespace Stpronk\Aeries;

use Illuminate\Support\ServiceProvider;

class AeriesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'stpronk-aeries');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'stpronk-aeries');
    }
}
