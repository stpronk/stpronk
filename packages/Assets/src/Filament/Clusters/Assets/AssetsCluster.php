<?php

namespace Stpronk\Assets\Filament\Clusters\Assets;

use Filament\Clusters\Cluster;
use Stpronk\Assets\Filament\AssetsPlugin;
use Stpronk\Essentials\Concerns\Resource as Essentials;

class AssetsCluster extends Cluster
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasNavigation;
    use Essentials\HasLabels;
    use Essentials\HasGlobalSearch;
    use Essentials\DelegatesToPlugin;

    public static function getEssentialsPlugin(): ?AssetsPlugin
    {
        return AssetsPlugin::get();
    }
}
