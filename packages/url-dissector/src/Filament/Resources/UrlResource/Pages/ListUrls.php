<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources\UrlResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Stpronk\UrlDissector\Filament\Resources\UrlResource;

class ListUrls extends ListRecords
{
    protected static string $resource = UrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
