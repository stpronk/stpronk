<?php

namespace App\Filament\Clusters\Website\Resources;

use App\Filament\Clusters\Website\WebsiteCluster;
use App\Models\Skill;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationLabel = 'Skills';

    protected static ?string $pluralModelLabel = 'Skills';

    protected static ?string $modelLabel = 'Skill';

    protected static ?string $cluster = WebsiteCluster::class;

    protected static ?int $navigationSort = 2;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\Hidden::make('sort_order')->default(0),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->placeholder('All'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\RestoreAction::make(),
                \Filament\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\RestoreBulkAction::make(),
                    \Filament\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Clusters\Website\Resources\SkillResource\Pages\ListSkills::route('/'),
            'create' => \App\Filament\Clusters\Website\Resources\SkillResource\Pages\CreateSkill::route('/create'),
            'edit' => \App\Filament\Clusters\Website\Resources\SkillResource\Pages\EditSkill::route('/{record}/edit'),
        ];
    }
}
