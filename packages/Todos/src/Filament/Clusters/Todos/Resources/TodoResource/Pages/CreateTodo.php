<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources\TodoResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Stpronk\Todos\Filament\Clusters\Todos\Resources\TodoResource;

class CreateTodo extends CreateRecord
{
    protected static string $resource = TodoResource::class;
}
