<?php

namespace Stpronk\Purchases\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Icons\Heroicon;
use Stpronk\Essentials\Concerns\Plugin as Essentials;
use Stpronk\Purchases\Filament\Resources\PurchaseItemResource;

class PurchasesPlugin implements Plugin
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasGlobalSearch;
    use Essentials\HasLabels;
    use Essentials\HasNavigation;
    use Essentials\HasPluginDefaults;
    use EvaluatesClosures;

    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'stpronk-purchases';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            PurchaseItemResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    protected function getPluginDefaults(): array
    {
        return [
            'modelLabel' => 'Purchase',
            'pluralModelLabel' => 'Purchases',
            'navigationGroup' => 'Resources',
            'navigationLabel' => 'Purchases',
            'navigationIcon' => Heroicon::OutlinedShoppingCart,
            'activeNavigationIcon' => Heroicon::ShoppingCart,
        ];
    }
}
