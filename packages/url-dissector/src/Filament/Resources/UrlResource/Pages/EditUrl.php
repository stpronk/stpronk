<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources\UrlResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Stpronk\UrlDissector\Filament\Resources\UrlResource;

class EditUrl extends EditRecord
{
    protected static string $resource = UrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
