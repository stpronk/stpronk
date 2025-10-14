<?php

namespace App\Filament\Clusters\Website\Resources\WorkExperienceResource\Pages;

use App\Filament\Clusters\Website\Resources\WorkExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkExperience extends EditRecord
{
    protected static string $resource = WorkExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
