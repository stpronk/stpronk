<?php

namespace Stpronk\Todos;

use Illuminate\Support\ServiceProvider;

class TodosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
//        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'todos');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'stpronk-filament-todos');

        $this->publishes([
            __DIR__.'/../config/filament-todos.php' => config_path('filament-todos.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-todos.php', 'filament-todos'
        );
    }
}
