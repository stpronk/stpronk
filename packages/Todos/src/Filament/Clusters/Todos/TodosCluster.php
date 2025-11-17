<?php

namespace Stpronk\Todos\Filament\Clusters\Todos;

use Filament\Clusters\Cluster;
use Stpronk\Essentials\Concerns\Resource as Essentials;
use Stpronk\Todos\Filament\TodosPlugin;
use Stpronk\Todos\Models\Todo;

class TodosCluster extends Cluster
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasNavigation;
    use Essentials\HasLabels;
    use Essentials\HasGlobalSearch;
    use Essentials\DelegatesToPlugin;

    public static function getNavigationBadge(): ?string
    {
        $count = Todo::query()->whereNull('completed_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return Todo::query()->whereNull('completed_at')->exists() ? 'primary' : null;
    }

    public static function getEssentialsPlugin(): ?TodosPlugin
    {
        return TodosPlugin::get();
    }

}
