<?php

namespace App\Filament\Actions;

use Filament\Actions\BulkAction;
use Illuminate\Support\Collection;

class DeactivateBulk extends BulkAction
{
    public static function make(?string $name = null): static
    {
        $action = parent::make($name ?? 'deactivate')
            ->label('Deactivate selected')
            ->icon('heroicon-m-x-mark')
            ->color('warning')
            ->action(function (Collection $records): void {
                $records->each->update(['active' => false]);
            });

        return $action;
    }
}
