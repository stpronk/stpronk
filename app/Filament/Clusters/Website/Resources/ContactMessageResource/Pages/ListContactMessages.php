<?php

namespace App\Filament\Clusters\Website\Resources\ContactMessageResource\Pages;

use App\Filament\Clusters\Website\Resources\ContactMessageResource;
use Filament\Resources\Pages\ListRecords;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;
}
