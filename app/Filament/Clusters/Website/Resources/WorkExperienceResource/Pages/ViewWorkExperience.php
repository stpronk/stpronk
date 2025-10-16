<?php

namespace App\Filament\Clusters\Website\Resources\WorkExperienceResource\Pages;

use App\Filament\Clusters\Website\Resources\WorkExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkExperience extends ViewRecord
{
    protected static string $resource = WorkExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            \App\Filament\Actions\ActivateRecord::make(),
            \App\Filament\Actions\DeactivateRecord::make(),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
}
