<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources\TodoResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Stpronk\Todos\Filament\Clusters\Todos\Resources\TodoResource;

class ListTodos extends ListRecords
{
    protected static string $resource = TodoResource::class;
}
