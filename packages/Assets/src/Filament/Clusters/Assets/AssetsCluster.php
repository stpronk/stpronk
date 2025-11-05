<?php

namespace Stpronk\Assets\Filament\Clusters\Assets;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AssetsCluster extends Cluster
{
    protected static string|UnitEnum|null $navigationGroup = 'Resources';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;
}
