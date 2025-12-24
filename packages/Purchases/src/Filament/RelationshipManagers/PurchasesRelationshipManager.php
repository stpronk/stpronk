<?php

namespace Stpronk\Purchases\Filament\RelationshipManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Stpronk\Purchases\Enums\PurchaseItemPriority;
use Stpronk\Purchases\Enums\PurchaseItemStatus;

class PurchasesRelationshipManager extends RelationManager
{
    protected static string $relationship = 'purchaseItems';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getBadge(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->purchaseItems()->where('status', '!=', PurchaseItemStatus::DONE)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getBadgeColor(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->purchaseItems()->where('status', '!=', PurchaseItemStatus::DONE)->exists() ? 'primary' : null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required(),
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('€'),
                Forms\Components\Select::make('status')
                    ->options(PurchaseItemStatus::class)
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('priority')
                    ->options(PurchaseItemPriority::class)
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('is_wishlist')
                    ->label('Wishlist')
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ])
                    ->default(false)
                    ->native(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->icon(fn($record) => $record->shared_with_me ? \Filament\Support\Icons\Heroicon::Share : null)
                    ->iconColor('primary')
                    ->tooltip(fn($record) => $record->shared_with_me ? "Shared with me" : null)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn ($state) => '€ ' . number_format((float) $state, 2, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (PurchaseItemStatus $state): string => $state->getColor())
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (PurchaseItemPriority $state): string => $state->getColor())
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_wishlist')
                    ->label('Wishlist')
                    ->boolean()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
                Tables\Columns\IconColumn::make('shareables')
                    ->label(__('stpronk-filament-essentials::shareables.relations.past_participle'))
                    ->getStateUsing(fn($record) => $record->isShared())
                    ->boolean()
                    ->default(false)
                    ->falseIcon(\Filament\Support\Icons\Heroicon::XMark)
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('hide_done')
                    ->label('Hide Done')
                    ->placeholder('Show All')
                    ->trueLabel('Only Done')
                    ->falseLabel('Exclude Done')
                    ->queries(
                        true: fn ($query) => $query->where('status', PurchaseItemStatus::DONE),
                        false: fn ($query) => $query->where('status', '!=', PurchaseItemStatus::DONE),
                        blank: fn ($query) => $query,
                    )
                    ->default(false),
                Tables\Filters\SelectFilter::make('status')
                    ->options(PurchaseItemStatus::class),
                Tables\Filters\SelectFilter::make('priority')
                    ->options(PurchaseItemPriority::class),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
