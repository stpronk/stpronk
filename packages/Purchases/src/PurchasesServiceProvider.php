<?php

namespace Stpronk\Purchases;

use Illuminate\Support\ServiceProvider;

class PurchasesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
