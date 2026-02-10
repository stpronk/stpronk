<?php

namespace Stpronk\Aeries\Filament\Resources;

use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Stpronk\Aeries\Enums\GoodsBrandType;
use Stpronk\Aeries\Filament\Resources\GoodsBrandResource\Pages;
use Stpronk\Aeries\Models\GoodsBrand;
use Stpronk\Aeries\Models\GoodsEntity;
use Stpronk\Essentials\Concerns\Resource as Essentials;

class GoodsBrandResource extends Resource
{
    use Essentials\BelongsToParent;
    use Essentials\BelongsToTenant;
    use Essentials\HasNavigation;
    use Essentials\HasLabels;
    use Essentials\HasGlobalSearch;
    use Essentials\DelegatesToPlugin;

    protected static ?string $model = GoodsBrand::class;
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationIcon(): \BackedEnum|\Illuminate\Contracts\Support\Htmlable|string|null
    {
        return Heroicon::OutlinedTruck;
    }

    public static function getActiveNavigationIcon(): \BackedEnum|\Illuminate\Contracts\Support\Htmlable|string|null
    {
        return Heroicon::Truck;
    }

    public static function getNavigationLabel(): string
    {
        return __('stpronk-aeries::goods-brand.model.navigation_label');
    }

    public static function getLabel(): string
    {
        return __('stpronk-aeries::goods-brand.model.label');
    }

    public static function getPluralLabel(): string
    {
        return __('stpronk-aeries::goods-brand.model.plural_label');
    }

    public static function getNavigationParentItem(): string
    {
        return __('stpronk-aeries::goods-entity.model.navigation_label');
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
                        Forms\Components\Select::make('type')
                            ->label(__('stpronk-aeries::general.type'))
                            ->options(GoodsBrandType::class)
                            ->required()
                            ->native(false),
                    ])
            ])
            ->columns(1);
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
                Tables\Columns\TextColumn::make('type')
                    ->label(__('stpronk-aeries::general.type'))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('goods_entities_count')
                    ->counts('goodsEntities')
                    ->label(__('stpronk-aeries::goods-brand.table.columns.goods_entities_count')),
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
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('stpronk-aeries::general.type'))
                    ->options(GoodsBrandType::class),
            ])
            ->headerActions([
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
            'index' => Pages\ListGoodsBrands::route('/'),
            'create' => Pages\CreateGoodsBrand::route('/create'),
            'edit' => Pages\EditGoodsBrand::route('/{record}/edit'),
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
