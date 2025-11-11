<?php

namespace Stpronk\Assets\Filament\Clusters\Assets\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Stpronk\Assets\Filament\Clusters\Assets\AssetsCluster;
use Stpronk\Assets\Models\Asset;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationLabel = 'Assets';

    protected static ?string $pluralModelLabel = 'Assets';

    protected static ?string $modelLabel = 'Asset';

    protected static ?string $cluster = AssetsCluster::class;

    protected static ?int $navigationSort = 1;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('asset_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                Forms\Components\TextInput::make('price_cents')
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
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_cents')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => '€ ' . number_format(((int) $state) / 100, 2, ',', '.'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status')
                    ->trueLabel('Active')
                    ->falseLabel('Closed')
                    ->placeholder('All')
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
                    ->label('Take Profit')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Asset $record) => $record->status !== 'closed')
                    ->schema([
                        Forms\Components\TextInput::make('take_profit_cents')
                            ->label('Realized Amount')
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
                    ->successNotificationTitle('Profit taken.'),
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
