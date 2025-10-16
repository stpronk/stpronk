<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ActivateRecord extends Action
{
    public static function make(?string $name = null): static
    {
        $action = parent::make($name ?? 'activate')
            ->label('Activate')
            ->tableIcon('heroicon-m-check')
            ->color('success')
            ->visible(fn (Model $record): bool => ! (bool) data_get($record, 'active'))
            ->action(function (Model $record): void {
                $record->setAttribute('active', true);
                $record->save();
            });

        return $action;
    }
}
