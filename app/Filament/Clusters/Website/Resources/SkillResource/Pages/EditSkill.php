<?php

namespace App\Filament\Clusters\Website\Resources\SkillResource\Pages;

use App\Filament\Clusters\Website\Resources\SkillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkill extends EditRecord
{
    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \App\Filament\Actions\ActivateRecord::make(),
            \App\Filament\Actions\DeactivateRecord::make(),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
}
