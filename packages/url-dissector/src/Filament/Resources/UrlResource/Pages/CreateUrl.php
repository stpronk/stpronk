<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources\UrlResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Stpronk\UrlDissector\Filament\Resources\UrlResource;
use Stpronk\UrlDissector\Services\UrlDissectorService;
use Illuminate\Database\Eloquent\Model;

class CreateUrl extends CreateRecord
{
    protected static string $resource = UrlResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $service = app(UrlDissectorService::class);

        if ($data['queue_parsing'] ?? false) {
            return $service->dispatch($data['normalized_url']);
        }

        return $service->store($data['normalized_url']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
