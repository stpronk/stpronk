<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Stpronk\Todos\Filament\Clusters\Todos\TodosCluster;
use Stpronk\Todos\Models\Todo;

class OpenTodoResource extends TodoResource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationLabel = 'Open';

    protected static ?string $pluralModelLabel = 'Open Todos';

    protected static ?string $modelLabel = 'Open Todo';

    protected static ?string $cluster = TodosCluster::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = Todo::query()->whereNull('completed_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return Todo::query()->whereNull('completed_at')->exists() ? 'primary' : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => OpenTodoResource\Pages\ListOpenTodos::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Start from the base TodoResource query (which scopes to the current user)
        return parent::getEloquentQuery()
            ->whereNull('completed_at');
    }
}
