<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources\CategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Stpronk\Todos\Filament\Clusters\Todos\Resources\CategoryResource;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
