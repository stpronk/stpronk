<?php

namespace Stpronk\Essentials\Filament\RelationshipManagers;

use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Stpronk\Essentials\Models\Shareables;

class ShareablesRelationshipManager extends RelationManager
{

    protected static string $relationship = 'shareables';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('shared_with')
                    ->label(__('stpronk-filament-essentials::shareables.form.fields.shared_with.label'))
                    ->hiddenLabel()
                    ->relationship('sharedWith', 'name')
                    ->searchable()
                    ->preload()
                    ->options(fn() => User::query()
                        ->whereNot('id', $this->ownerRecord->{$this->ownerRecord->getOwnerField()})
                        ->whereNotIn('id', Shareables::query()
                                ->where('shareable_type', $this->ownerRecord->getMorphClass())
                                ->where('shareable_id', $this->ownerRecord->getKey())
                                ->pluck('shared_with')
                        )->pluck('name', 'id'))
                    ->columnSpan(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('stpronk-filament-essentials::shareables.table.heading'))
            ->columnManager(false)
            ->searchable(false)
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('sharedWith.name')
                    ->label(__('stpronk-filament-essentials::shareables.table.columns.shared_with.label'))
                    ->placeholder(__('stpronk-filament-essentials::shareables.table.columns.shared_with.placeholder'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sharedBy.name')
                    ->label(__('stpronk-filament-essentials::shareables.table.columns.shared_by.label'))
                    ->placeholder(__('stpronk-filament-essentials::shareables.table.columns.shared_by.placeholder'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sharedBy.email')
                    ->label(__('stpronk-filament-essentials::shareables.table.columns.shared_by_email.label'))
                    ->placeholder(__('stpronk-filament-essentials::shareables.table.columns.shared_by_email.placeholder'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('shared_at')
                    ->label(__('stpronk-filament-essentials::shareables.table.columns.shared_at.label'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label(__('stpronk-filament-essentials::shareables.table.actions.create.label'))
                    ->modalHeading(__('stpronk-filament-essentials::shareables.table.actions.create.modal_heading'))
                    ->visible(fn() =>
                        $this->ownerRecord->{$this->ownerRecord->getOwnerField()} === Auth::id()
                    ),
            ])
            ->recordActions([
                Actions\DeleteAction::make()
                    ->label(__('stpronk-filament-essentials::shareables.table.actions.delete.label'))
                    ->modalHeading(__('stpronk-filament-essentials::shareables.table.actions.delete.modal_heading'))
                    ->visible(fn($record) => $record->shared_by === Auth::id()),
            ]);

    }
}
