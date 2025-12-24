<?php

namespace Stpronk\Purchases\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Stpronk\Essentials\Concerns\Resource as Essentials;
use Stpronk\Essentials\Filament\RelationshipManagers\ShareablesRelationshipManager;
use Stpronk\Purchases\Enums\PurchaseItemPriority;
use Stpronk\Purchases\Enums\PurchaseItemStatus;
use Stpronk\Purchases\Filament\Clusters\Purchases\PurchasesCluster;
use Stpronk\Purchases\Models\PurchaseItem;

class PurchaseItemResource extends Resource
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasNavigation;
    use Essentials\HasLabels;
    use Essentials\HasGlobalSearch;
    use Essentials\DelegatesToPlugin;

    protected static ?string $model = PurchaseItem::class;

    public static function getEssentialsPlugin(): ?\Stpronk\Purchases\Filament\PurchasesPlugin
    {
        return \Stpronk\Purchases\Filament\PurchasesPlugin::get();
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
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
                Forms\Components\Select::make('todo_id')
                    ->label('Todo')
                    ->relationship('todo', 'title')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->visible(fn() => class_exists('Stpronk\Todos\Models\Todo')),
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

    public static function table(Table $table): Table
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
                Tables\Columns\TextColumn::make('todo.title')
                    ->label('Todo')
                    ->sortable()
                    ->visible(fn() => class_exists('Stpronk\Todos\Models\Todo'))
                    ->url(fn($record) => $record->todo_id ? \Stpronk\Todos\Filament\Clusters\Todos\Resources\TodoResource::getUrl('edit', ['record' => $record->todo_id]) : null),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('hide_done')
                    ->label('Hide Done')
                    ->placeholder('Show All')
                    ->trueLabel('Only Done')
                    ->falseLabel('Exclude Done')
                    ->queries(
                        true: fn (Builder $query) => $query->where('status', PurchaseItemStatus::DONE),
                        false: fn (Builder $query) => $query->where('status', '!=', PurchaseItemStatus::DONE),
                        blank: fn (Builder $query) => $query,
                    )
                    ->default(false),
                Tables\Filters\TernaryFilter::make('has_todo')
                    ->label('Hide Todo')
                    ->placeholder('Show All')
                    ->trueLabel('Only Todo')
                    ->falseLabel('Exclude Todo')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('todo_id'),
                        false: fn (Builder $query) => $query->whereNull('todo_id'),
                        blank: fn (Builder $query) => $query,
                    ),
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

    public static function getRelations(): array
    {
        return [
            ShareablesRelationshipManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => PurchaseItemResource\Pages\ListPurchaseItems::route('/'),
            'create' => PurchaseItemResource\Pages\CreatePurchaseItem::route('/create'),
            'edit' => PurchaseItemResource\Pages\EditPurchaseItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!auth()->id()) {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }
}
