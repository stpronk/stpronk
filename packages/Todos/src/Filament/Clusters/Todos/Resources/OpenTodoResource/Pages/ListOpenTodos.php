<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources\OpenTodoResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Stpronk\Todos\Filament\Clusters\Todos\Resources\OpenTodoResource;

class ListOpenTodos extends ListRecords
{
    protected static string $resource = OpenTodoResource::class;
}
