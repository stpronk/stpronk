<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources;

use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Enums\UrlOnlineStatus;
use Stpronk\UrlDissector\Filament\Resources\UrlResource\Pages;
use Stpronk\UrlDissector\UrlDissectorPlugin;

class UrlResource extends Resource
{
    protected static ?string $model = Url::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-link';

    public static function getNavigationGroup(): ?string
    {
        return UrlDissectorPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return UrlDissectorPlugin::get()->getNavigationSort();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('normalized_url')
                    ->required()
                    ->url()
                    ->live(onBlur: true)
                    ->maxLength(2048)
                    ->columnSpanFull(),
                Forms\Components\Checkbox::make('parse_immediately')
                    ->label('Parse immediately')
                    ->default(true)
                    ->dehydrated(false)
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateUrl),
                Forms\Components\Checkbox::make('queue_parsing')
                    ->label('Queue for parsing')
                    ->default(false)
                    ->dehydrated(false)
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateUrl),
                Section::make('Parsed Components')
                    ->schema([
                        Forms\Components\TextInput::make('scheme')
                            ->disabled(),
                        Forms\Components\TextInput::make('host.full_host')
                            ->label('Host')
                            ->formatStateUsing(fn ($record) => $record?->host?->full_host)
                            ->disabled(),
                        Forms\Components\TextInput::make('port')
                            ->disabled(),
                        Forms\Components\TextInput::make('path.full_path')
                            ->label('Path')
                            ->formatStateUsing(fn ($record) => $record?->path?->full_path)
                            ->disabled(),
                        Forms\Components\TextInput::make('query_string')
                            ->disabled(),
                        Forms\Components\TextInput::make('fragment')
                            ->disabled(),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record !== null),
                Section::make('Reconstruction')
                    ->schema([
                        Forms\Components\TextInput::make('reconstructed_url')
                            ->formatStateUsing(fn ($record) => $record ? app(\Stpronk\UrlDissector\Services\UrlReconstructorService::class)->rebuild($record->id) : null)
                        ->disabled(),
                        TextEntry::make('verification')
                            ->label('Matches Original?')
                            ->state(fn ($record) => app(\Stpronk\UrlDissector\Services\UrlReconstructorService::class)->verify($record->id) ? '✅ Yes' : '❌ No'),
                    ])
                    ->visible(fn ($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_online')
                    ->label('')
                    ->tooltip(fn (Url $record) => ($lastCheckedAt = $record->last_checked_at)
                        ? 'Last scanned at: ' . $lastCheckedAt->format('Y-m-d H:i:s')
                        : 'Not scanned yet')
                    ->action(fn (Url $record) => app(\Stpronk\UrlDissector\Services\UrlStatusCheckerService::class)->check($record))
                    ->extraAttributes([
                        'wire:loading.class' => 'opacity-50 pointer-events-none',
                        'wire:target' => 'mountTableAction',
                    ]),
                Tables\Columns\TextColumn::make('last_checked_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('normalized_url')
                    ->searchable()
                    ->copyable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('usages_count')
                    ->label('Usage')
                    ->counts('usages')
                    ->sortable(),
                Tables\Columns\TextColumn::make('host.full_host')
                    ->label('Host')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('path.full_path')
                    ->label('Path')
                    ->sortable()
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('scheme')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_valid')
                    ->label('Parsing')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn (Url $record) => $record->is_valid ? 'Correctly parsed' : 'Parsing mismatch detected')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('host')
                    ->relationship('host', 'full_host')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('scheme')
                    ->options([
                        'http' => 'HTTP',
                        'https' => 'HTTPS',
                        'ftp' => 'FTP',
                    ]),
                Tables\Filters\Filter::make('path_contains')
                    ->schema([
                        Forms\Components\TextInput::make('path_query'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['path_query'],
                            fn (Builder $query, $path): Builder => $query->whereHas('path', fn ($q) => $q->where('full_path', 'like', "%{$path}%")),
                        );
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->schema([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\TernaryFilter::make('is_valid')
                    ->label('Parsing Status')
                    ->placeholder('All URLs')
                    ->trueLabel('Valid only')
                    ->falseLabel('Incorrectly parsed')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_valid', true),
                        false: fn (Builder $query) => $query->where('is_valid', false),
                        blank: fn (Builder $query) => $query,
                    ),
                Tables\Filters\TernaryFilter::make('has_query_params')
                    ->placeholder('All URLs')
                    ->trueLabel('Has query parameters')
                    ->falseLabel('No query parameters')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('query_string'),
                        false: fn (Builder $query) => $query->whereNull('query_string'),
                        blank: fn (Builder $query) => $query,
                    ),
                Tables\Filters\SelectFilter::make('is_online')
                    ->label('Online Status')
                    ->options(UrlOnlineStatus::class),
                Tables\Filters\Filter::make('path_depth')
                    ->schema([
                        Forms\Components\TextInput::make('min_depth')->numeric(),
                        Forms\Components\TextInput::make('max_depth')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['min_depth'] || $data['max_depth'],
                            fn (Builder $query) => $query->whereHas('path', function ($q) use ($data) {
                                $q->when($data['min_depth'], fn ($q, $min) => $q->where('depth', '>=', $min))
                                  ->when($data['max_depth'], fn ($q, $max) => $q->where('depth', '<=', $max));
                            })
                        );
                    }),
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
                    \Filament\Actions\BulkAction::make('check_online_selected')
                        ->label('Check Online')
                        ->icon('heroicon-o-arrow-path')
                        ->action(fn ($records) => $records->each(fn (Url $record) => app(\Stpronk\UrlDissector\Services\UrlStatusCheckerService::class)->check($record))),
                    \Filament\Actions\BulkAction::make('reparse_selected')
                        ->icon('heroicon-o-arrow-path')
                        ->action(fn ($records) => app(\Stpronk\UrlDissector\Services\UrlDissectorService::class)->bulkStore(
                            $records->pluck('normalized_url')->toArray(),
                            config('url-dissector.queue.enabled', true)
                        )),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            UrlResource\RelationManagers\QueryParametersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUrls::route('/'),
            'create' => Pages\CreateUrl::route('/create'),
            'view' => Pages\ViewUrl::route('/{record}'),
            'edit' => Pages\EditUrl::route('/{record}/edit'),
        ];
    }
}
