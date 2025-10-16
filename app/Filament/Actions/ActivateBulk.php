<?php

namespace App\Filament\Actions;

use Filament\Actions\BulkAction;
use Illuminate\Support\Collection;

class ActivateBulk extends BulkAction
{
    public static function make(?string $name = null): static
    {
        $action = parent::make($name ?? 'activate')
            ->label('Activate selected')
            ->icon('heroicon-m-check')
            ->color('success')
            ->action(function (Collection $records): void {
                $records->each->update(['active' => true]);
            });

        return $action;
    }
}
