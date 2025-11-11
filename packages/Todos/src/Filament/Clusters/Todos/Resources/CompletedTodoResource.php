<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources;

use Illuminate\Database\Eloquent\Builder;
use Stpronk\Todos\Filament\Clusters\Todos\TodosCluster;
use Stpronk\Todos\Models\Todo;

class CompletedTodoResource extends TodoResource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationLabel = 'Completed';

    protected static ?string $pluralModelLabel = 'Completed Todos';

    protected static ?string $modelLabel = 'Completed Todo';

    protected static ?string $cluster = TodosCluster::class;

    protected static ?int $navigationSort = 2;

    public static function getPages(): array
    {
        return [
            'index' => TodoResource\Pages\ListCompletedTodos::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('completed_at');
    }
}
