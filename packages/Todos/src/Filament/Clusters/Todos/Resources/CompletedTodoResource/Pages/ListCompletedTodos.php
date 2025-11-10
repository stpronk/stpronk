<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources\CompletedTodoResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Stpronk\Todos\Filament\Clusters\Todos\Resources\CompletedTodoResource;

class ListCompletedTodos extends ListRecords
{
    protected static string $resource = CompletedTodoResource::class;
}
