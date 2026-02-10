<?php

namespace Stpronk\Aeries\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Icons\Heroicon;
use Stpronk\Essentials\Concerns\Plugin as Essentials;

use Stpronk\Aeries\Filament\Resources\GoodsEntityResource;
use Stpronk\Aeries\Filament\Resources\GoodsBrandResource;

class AeriesPlugin implements Plugin
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
        return 'stpronk-aeries';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            GoodsEntityResource::class,
            GoodsBrandResource::class,
        ])
        ;
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
            'navigationGroup' => fn () => __('stpronk-aeries::general.navigation_group'),
            'navigationIcon' => Heroicon::OutlinedCube,
            'activeNavigationIcon' => Heroicon::Cube,
        ];
    }
}
