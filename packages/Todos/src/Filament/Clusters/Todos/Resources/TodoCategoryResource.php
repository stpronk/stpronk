<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Stpronk\Todos\Filament\Clusters\Todos\TodosCluster;
use Stpronk\Todos\Models\TodoCategory;

class TodoCategoryResource extends Resource
{
    protected static ?string $model = TodoCategory::class;

    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $pluralModelLabel = 'Categories';

    protected static ?string $modelLabel = 'Todo Category';

    protected static ?string $cluster = TodosCluster::class;

    protected static ?int $navigationSort = 3;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(120)
                    ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                        return $rule->where('user_id', auth()->id());
                    }),
                Forms\Components\Select::make('color')
                    ->label('Badge color')
                    ->options([
                        'Red' => 'Red',
                        'Blue' => 'Blue',
                        'Yellow' => 'Yellow',
                        'Emerald' => 'Emerald',
                        'Amber' => 'Amber',
                        'Zinc' => 'Zinc',
                    ])
                    ->default('Amber')
                    ->required()
                    ->native(false),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->badge()
                    ->color(fn ($state, $record) => Color::{$record->color ?? 'Amber'})
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('open_todos_count')
                    ->counts('todos as open_todos_count', fn ($query) => $query->whereNull('completed_at'))
                    ->label('Open')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('completed_todos_count')
                    ->counts('todos as completed_todos_count', fn ($query) => $query->whereNotNull('completed_at'))
                    ->label('Completed')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([])
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

    public static function getPages(): array
    {
        return [
            'index' => CategoryResource\Pages\ListCategories::route('/'),
            'create' => CategoryResource\Pages\CreateCategory::route('/create'),
            'edit' => CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }
}
