<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources\PathResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PathSegmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'segments';

    protected static ?string $recordTitleAttribute = 'segment';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('segment')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('depth')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('parent_segment_id')
                    ->relationship('parent', 'segment')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('segment'),
                Tables\Columns\TextColumn::make('depth'),
                Tables\Columns\TextColumn::make('order'),
                Tables\Columns\TextColumn::make('parent.segment')
                    ->label('Parent'),
            ])
            ->filters([
                //
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
            ])
            ->defaultSort('order');
    }
}
