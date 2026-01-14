<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources\UrlResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Stpronk\UrlDissector\Filament\Resources\UrlResource;

class ViewUrl extends ViewRecord
{
    protected static string $resource = UrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
