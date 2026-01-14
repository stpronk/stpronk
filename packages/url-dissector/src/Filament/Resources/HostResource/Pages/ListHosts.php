<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources\HostResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Stpronk\UrlDissector\Filament\Resources\HostResource;

class ListHosts extends ListRecords
{
    protected static string $resource = HostResource::class;
}
