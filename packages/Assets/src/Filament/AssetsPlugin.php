<?php

namespace Stpronk\Assets\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Icons\Heroicon;
use Stpronk\Essentials\Concerns\Plugin as Essentials;

class AssetsPlugin implements Plugin
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
        return 'stpronk-assets';
    }

    public function register(Panel $panel): void
    {
        // Discover clusters with resources within this package
        $panel->discoverClusters(
            in: __DIR__ . '/Clusters',
            for: 'Stpronk\\Assets\\Filament\\Clusters'
        );

//        $panel->discoverResources(
//            in: __DIR__ . '/Clusters/Assets/Resources',
//            for: 'Stpronk\\Assets\\Filament\\Clusters\\Assets\\Resources'
//        );
//
//        $panel->discoverPages(
//            in: __DIR__ . '/Clusters/Assets/Pages',
//            for: 'Stpronk\\Assets\\Filament\\Clusters\\Assets\\Pages'
//        );
    }

    public function boot(Panel $panel): void
    {
        // No boot logic required for now.
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
            'modelLabel' => __('stpronk-filament-assets::assets.model.label'),
            'pluralModelLabel' => __('stpronk-filament-assets::assets.model.plural_label'),
            'navigationGroup' => __('stpronk-filament-assets::assets.model.navigation_group'),
            'navigationLabel' => __('stpronk-filament-assets::assets.model.navigation_label'),
            'navigationIcon' => Heroicon::OutlinedCube,
            'activeNavigationIcon' => Heroicon::Cube,
        ];
    }
}
