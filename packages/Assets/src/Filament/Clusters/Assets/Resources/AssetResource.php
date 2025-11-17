<?php

namespace Stpronk\Assets\Filament\Clusters\Assets\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Stpronk\Assets\Filament\Clusters\Assets\AssetsCluster;
use Stpronk\Assets\Models\Asset;
use Stpronk\Assets\Models\AssetCategory;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $cluster = AssetsCluster::class;

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('stpronk-filament-assets::assets.model.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('stpronk-filament-assets::assets.model.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('stpronk-filament-assets::assets.model.navigation_label');
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('stpronk-filament-assets::assets.form.fields.name.label'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('asset_category_id')
                    ->label(__('stpronk-filament-assets::assets.form.fields.asset_category.label'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label(__('stpronk-filament-assets::category.form.fields.name.label'))
                            ->required()
                            ->rule(fn () => Rule::unique('asset_categories', 'name')->where('user_id', auth()->id()))
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return AssetCategory::query()
                            ->firstOrCreate(
                                ['name' => $data['name'], 'user_id' => auth()->id()],
                                ['color' => 'primary']
                            )
                            ->getKey();
                    }),
                Forms\Components\TextInput::make(__('stpronk-filament-assets::assets.form.fields.asset_category.label'))
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01)
                    ->rule('gte:0')
                    ->afterStateHydrated(function ($component, $state) {
                        // Convert cents to euros for display
                        $component->state(number_format(((int) $state) / 100, 2, '.', ''));
                    })
                    ->dehydrateStateUsing(function ($state) {
                        // Convert euros to cents for storage
                        $value = (float) str_replace([',', ' '], ['', ''], $state);
                        return (int) round($value * 100);
                    }),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('stpronk-filament-assets::assets.table.columns.name.label'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('stpronk-filament-assets::category.model.label'))
                    ->badge()
                    ->color(fn ($state, $record) => Color::{$record->category?->color ?? 'Amber'})
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_cents')
                    ->label(__('stpronk-filament-assets::assets.table.columns.price_cents.label'))
                    ->formatStateUsing(fn ($state) => '€ ' . number_format(((int) $state) / 100, 2, ',', '.'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label(__('stpronk-filament-assets::assets.table.filters.status.label'))
                    ->trueLabel(__('stpronk-filament-assets::assets.table.filters.status.true_label'))
                    ->falseLabel(__('stpronk-filament-assets::assets.table.filters.status.false_label'))
                    ->placeholder(__('stpronk-filament-assets::assets.table.filters.status.placeholder'))
                    ->queries(
                        true: fn ($query) => $query->where('status', 'active'),
                        false: fn ($query) => $query->where('status', 'closed'),
                        blank: fn ($query) => $query->where('status', 'active'),
                    ),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('take_profit')
                    ->label(__('stpronk-filament-assets::assets.table.actions.take_profit.label'))
                    ->icon(__('stpronk-filament-assets::assets.table.actions.take_profit.icon'))
                    ->color('success')
                    ->visible(fn (Asset $record) => $record->status !== 'closed')
                    ->schema([
                        Forms\Components\TextInput::make('take_profit_cents')
                            ->label(__('stpronk-filament-assets::assets.table.actions.take_profit.form.fields.take_profit_cents.label'))
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->rule('gte:0')
                            ->dehydrateStateUsing(function ($state) {
                                $value = (float) str_replace([',', ' '], ['', ''], $state);
                                return (int) round($value * 100);
                            }),
                    ])
                    ->action(function (Asset $record, array $data) {
                        $record->update([
                            'take_profit_cents' => $data['take_profit_cents'] ?? null,
                            'status' => 'closed',
                            'closed_at' => now(),
                        ]);
                    })
                    ->successNotificationTitle(__('stpronk-filament-assets::assets.table.actions.take_profit.success_notification.title')),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => AssetResource\Pages\ListAssets::route('/'),
            'create' => AssetResource\Pages\CreateAsset::route('/create'),
            'edit' => AssetResource\Pages\EditAsset::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $userId = auth()->id();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            // If no authenticated user, return empty result
            $query->whereRaw('1 = 0');
        }
        return $query;
    }
}
