<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources\TodoResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Stpronk\Todos\Filament\Clusters\Todos\Resources\TodoResource;

class EditTodo extends EditRecord
{
    protected static string $resource = TodoResource::class;
}
