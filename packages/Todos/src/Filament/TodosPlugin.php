<?php

namespace Stpronk\Todos\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Icons\Heroicon;
use Stpronk\Essentials\Concerns\Plugin as Essentials;

class TodosPlugin implements Plugin
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

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    protected function getPluginDefaults(): array
    {
        return [
            'modelLabel' => __('stpronk-filament-todos::todos.model.label'),
            'pluralModelLabel' => __('stpronk-filament-todos::todos.model.plural_label'),
            'navigationGroup' => __('stpronk-filament-todos::todos.model.navigation_group'),
            'navigationLabel' => __('stpronk-filament-todos::todos.model.navigation_label'),
            'navigationIcon' => Heroicon::OutlinedClipboardDocumentCheck,
            'activeNavigationIcon' => Heroicon::ClipboardDocumentCheck,
        ];
    }
}
