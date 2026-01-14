<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources\PathResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Stpronk\UrlDissector\Filament\Resources\PathResource;

class ListPaths extends ListRecords
{
    protected static string $resource = PathResource::class;
}
