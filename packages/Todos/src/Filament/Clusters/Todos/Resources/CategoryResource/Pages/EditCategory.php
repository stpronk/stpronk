<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources\CategoryResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Stpronk\Todos\Filament\Clusters\Todos\Resources\CategoryResource;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;
}
