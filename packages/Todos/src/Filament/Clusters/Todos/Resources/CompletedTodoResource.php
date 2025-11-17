<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources;

use Illuminate\Database\Eloquent\Builder;
use Stpronk\Todos\Filament\Clusters\Todos\TodosCluster;
use Stpronk\Todos\Models\Todo;

class CompletedTodoResource extends TodoResource
{
    protected static ?string $model = Todo::class;

    protected static ?string $cluster = TodosCluster::class;

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('stpronk-filament-todos::todos.tabs.completed_todos.navigation_label');
    }

    public static function getLabel(): string
    {
        return __('stpronk-filament-todos::todos.tabs.completed_todos.label');
    }

    public static function getPluralLabel(): string
    {
        return __('stpronk-filament-todos::todos.tabs.completed_todos.plural_label');
    }

    public static function getPages(): array
    {
        return [
            'index' => TodoResource\Pages\ListCompletedTodos::route('/'),
            'edit' => TodoResource\Pages\EditTodo::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('completed_at');
    }
}
