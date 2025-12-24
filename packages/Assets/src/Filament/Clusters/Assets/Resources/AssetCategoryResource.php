<?php

namespace Stpronk\Assets\Filament\Clusters\Assets\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Stpronk\Assets\Filament\Clusters\Assets\AssetsCluster;
use Stpronk\Assets\Models\AssetCategory;

class AssetCategoryResource extends Resource
{
    protected static ?string $model = AssetCategory::class;

    protected static ?string $cluster = AssetsCluster::class;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('stpronk-filament-assets::category.model.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('stpronk-filament-assets::category.model.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('stpronk-filament-assets::category.model.navigation_label');
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('stpronk-filament-assets::category.form.fields.name.label'))
                    ->required()
                    ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                        return $rule->where('user_id', auth()->id());
                    })
                    ->maxLength(255),
                Forms\Components\Select::make('color')
                    ->label(__('stpronk-filament-assets::category.form.fields.color.label'))
                    ->options(function() {
                        return Arr::mapWithKeys(config('filament-stpronk-essentials.colors.options'), fn ($value) => [
                            $value => __("stpronk-filament-essentials::options.colors.{$value}")
                        ]);
                    })
                    ->default(config('filament-stpronk-essentials.colors.default'))
                    ->required()
                    ->native(false)
                ,
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('stpronk-filament-assets::category.table.columns.name.label'))
                    ->badge()
                    ->color(fn ($state, $record) => Color::{$record->color ?? 'Amber'})
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assets_count')
                    ->counts('assets')
                    ->label(__('stpronk-filament-assets::category.table.columns.assets_count.label'))
                    ->alignRight(),
            ])
            ->filters([])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->ToolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => CategoryResource\Pages\ListCategories::route('/'),
            'create' => CategoryResource\Pages\CreateCategory::route('/create'),
            'edit' => CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }
}
