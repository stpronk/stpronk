<?php

namespace Stpronk\Aeries\Filament\Resources;

use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Stpronk\Aeries\Filament\Resources\GoodsEntityResource\Pages;
use Stpronk\Aeries\Models\GoodsEntity;
use Stpronk\Essentials\Concerns\Resource as Essentials;

class GoodsEntityResource extends Resource
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasNavigation;
    use Essentials\HasLabels;
    use Essentials\HasGlobalSearch;
    use Essentials\DelegatesToPlugin;

    protected static ?string $model = GoodsEntity::class;

    public static function getNavigationLabel(): string
    {
        return __('stpronk-aeries::goods-entity.model.navigation_label');
    }

    public static function getLabel(): string
    {
        return __('stpronk-aeries::goods-entity.model.label');
    }

    public static function getPluralLabel(): string
    {
        return __('stpronk-aeries::goods-entity.model.plural_label');
    }

    public static function getEssentialsPlugin(): ?\Stpronk\Aeries\Filament\AeriesPlugin
    {
        return \Stpronk\Aeries\Filament\AeriesPlugin::get();
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make(__('stpronk-aeries::general.base_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('stpronk-aeries::general.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('goods_brand_id')
                            ->label(__('stpronk-aeries::goods-entity.fields.goods_brand'))
                            ->relationship('goodsBrand', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('stpronk-aeries::general.name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->label(__('stpronk-aeries::general.type'))
                                    ->options(\Stpronk\Aeries\Enums\GoodsBrandType::class)
                                    ->required()
                                    ->native(false),
                            ])
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->label(__('stpronk-aeries::general.type'))
                            ->options(\Stpronk\Aeries\Enums\GoodsEntityType::class)
                            ->disableOptionWhen(fn (string $value): bool => in_array($value, [
                                \Stpronk\Aeries\Enums\GoodsEntityType::SERVICE->value,
                                \Stpronk\Aeries\Enums\GoodsEntityType::THREE_D_MODEL->value,a
                            ]))
                            ->hidden(fn (?GoodsEntity $record) => !is_null($record))
                            ->required()
                            ->native(false),
                ])->columnSpan(fn (?GoodsEntity $record) => is_null($record) ? 3 : 2),
                Schemas\Components\Section::make()
                    ->schema([
                        TextEntry::make('type')
                            ->label(__('stpronk-aeries::general.type'))
                            ->badge(),
                        TextEntry::make('created_at')
                            ->label(__('stpronk-aeries::general.created_at'))
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label(__('stpronk-aeries::general.updated_at'))
                            ->dateTime(),
                        TextEntry::make('user.name')
                            ->label(__('stpronk-aeries::general.owner'))
                    ])->columnSpan(1)
                    ->hidden(fn (?GoodsEntity $record) => is_null($record)),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('stpronk-aeries::general.name'))
                    ->icon(fn($record) => $record->shared_with_me ? \Filament\Support\Icons\Heroicon::Share : null)
                    ->iconColor('primary')
                    ->tooltip(fn($record) => $record->shared_with_me ? "Shared with me" : null)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('goodsBrand.name')
                    ->label(__('stpronk-aeries::goods-entity.fields.goods_brand'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('stpronk-aeries::general.type'))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('stpronk-aeries::general.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('stpronk-aeries::general.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('goodsBrand.name')->label(__('stpronk-aeries::goods-entity.fields.goods_brand'))->collapsible()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('goods_brand_id')
                    ->label(__('stpronk-aeries::goods-entity.fields.goods_brand'))
                    ->relationship('goodsBrand', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('brands')
                    ->label(__('stpronk-aeries::goods-entity.actions.brands'))
                    ->url(GoodsBrandResource::getUrl())
                    ->color('gray'),
                \Filament\Actions\CreateAction::make(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\ForceDeleteAction::make(),
                \Filament\Actions\RestoreAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\ForceDeleteBulkAction::make(),
                    \Filament\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoodsEntities::route('/'),
            'create' => Pages\CreateGoodsEntity::route('/create'),
            'edit' => Pages\EditGoodsEntity::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        if (!auth()->id()) {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }
}
