<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources\PathResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Stpronk\UrlDissector\Models\Url;

class UrlsRelationManager extends RelationManager
{
    protected static string $relationship = 'urls';

    protected static ?string $recordTitleAttribute = 'normalized_url';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('normalized_url')
                    ->required()
                    ->url()
                    ->maxLength(2048)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('normalized_url')
                    ->searchable()
                    ->copyable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('host.full_host')
                    ->label('Host')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\Action::make('reparse')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn (Url $record) => app(\Stpronk\UrlDissector\Services\UrlDissectorService::class)->store($record->normalized_url)),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
