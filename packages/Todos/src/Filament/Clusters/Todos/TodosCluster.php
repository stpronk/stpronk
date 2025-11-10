<?php

namespace Stpronk\Todos\Filament\Clusters\Todos;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Stpronk\Todos\Models\Todo;
use UnitEnum;

class TodosCluster extends Cluster
{
    protected static string|UnitEnum|null $navigationGroup = 'Resources';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ClipboardDocumentCheck;

    protected static ?int $navigationSort = 20  ;

    public static function getNavigationBadge(): ?string
    {
        $count = Todo::query()->whereNull('completed_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return Todo::query()->whereNull('completed_at')->exists() ? 'primary' : null;
    }
}
