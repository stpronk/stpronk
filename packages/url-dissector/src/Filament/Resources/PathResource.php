<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Stpronk\UrlDissector\Models\Path;
use Stpronk\UrlDissector\Filament\Resources\PathResource\Pages;
use Stpronk\UrlDissector\UrlDissectorPlugin;

class PathResource extends Resource
{
    protected static ?string $model = Path::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-folder-open';

    public static function getNavigationGroup(): ?string
    {
        return UrlDissectorPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return UrlDissectorPlugin::get()->getNavigationSort() + 2;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('full_path')
                    ->readOnly()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('hash')
                    ->readOnly(),
                Forms\Components\TextInput::make('depth')
                    ->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_path')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('depth')
                    ->sortable(),
                Tables\Columns\TextColumn::make('urls_count')
                    ->counts('urls')
                    ->label('URLs')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
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
            PathResource\RelationManagers\PathSegmentsRelationManager::class,
            PathResource\RelationManagers\UrlsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaths::route('/'),
            'view' => Pages\ViewPath::route('/{record}'),
        ];
    }
}
