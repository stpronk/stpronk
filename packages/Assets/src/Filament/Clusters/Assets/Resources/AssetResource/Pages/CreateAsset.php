<?php

namespace Stpronk\Assets\Filament\Clusters\Assets\Resources\AssetResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Stpronk\Assets\Filament\Clusters\Assets\Resources\AssetResource;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
