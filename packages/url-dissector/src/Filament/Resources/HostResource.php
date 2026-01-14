<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Stpronk\UrlDissector\Models\Host;
use Stpronk\UrlDissector\Filament\Resources\HostResource\Pages;
use Stpronk\UrlDissector\UrlDissectorPlugin;

class HostResource extends Resource
{
    protected static ?string $model = Host::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-globe-alt';

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
                Forms\Components\TextInput::make('full_host')
                    ->readOnly(),
                Forms\Components\TextInput::make('domain')
                    ->readOnly(),
                Forms\Components\TextInput::make('tld')
                    ->readOnly(),
                Forms\Components\TextInput::make('subdomain')
                    ->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_host')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tld')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subdomain')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('urls_count')
                    ->counts('urls')
                    ->label('URLs')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tld')
                    ->options(fn () => Host::query()->distinct()->pluck('tld', 'tld')->toArray()),
                Tables\Filters\Filter::make('domain_contains')
                    ->schema([
                        Forms\Components\TextInput::make('domain'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query->when($data['domain'], fn ($q, $domain) => $q->where('domain', 'like', "%{$domain}%"))),
                Tables\Filters\TernaryFilter::make('has_subdomain')
                    ->placeholder('All Hosts')
                    ->trueLabel('Has subdomain')
                    ->falseLabel('No subdomain')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('subdomain')->where('subdomain', '!=', ''),
                        false: fn (Builder $query) => $query->where(fn ($q) => $q->whereNull('subdomain')->orWhere('subdomain', '')),
                        blank: fn (Builder $query) => $query,
                    ),
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
            HostResource\RelationManagers\UrlsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHosts::route('/'),
            'view' => Pages\ViewHost::route('/{record}'),
        ];
    }
}
