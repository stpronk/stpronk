<?php

namespace App\Filament\Clusters\Website\Resources\WorkExperienceResource\Pages;

use App\Filament\Clusters\Website\Resources\WorkExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkExperiences extends ListRecords
{
    protected static string $resource = WorkExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
