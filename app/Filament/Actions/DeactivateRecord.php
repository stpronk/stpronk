<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class DeactivateRecord extends Action
{
    public static function make(?string $name = null): static
    {
        $action = parent::make($name ?? 'deactivate')
            ->label('Deactivate')
            ->tableIcon('heroicon-m-x-mark')
            ->color('warning')
            ->visible(fn (Model $record): bool => (bool) data_get($record, 'active'))
            ->action(function (Model $record): void {
                $record->setAttribute('active', false);
                $record->save();
            });

        return $action;
    }
}
