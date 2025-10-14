<?php

namespace App\Filament\Clusters\Website\Resources\SkillResource\Pages;

use App\Filament\Clusters\Website\Resources\SkillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSkills extends ListRecords
{
    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

