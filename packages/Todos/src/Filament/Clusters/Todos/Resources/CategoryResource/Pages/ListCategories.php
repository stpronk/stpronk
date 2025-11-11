<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources\CategoryResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Stpronk\Todos\Filament\Clusters\Todos\Resources\TodoCategoryResource;

class ListCategories extends ListRecords
{
    protected static string $resource = TodoCategoryResource::class;
}
