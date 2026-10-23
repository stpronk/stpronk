<?php

namespace App\Filament\Clusters\Website\Resources;

use App\Filament\Clusters\Website\Resources\ContactMessageResource\Pages\ListContactMessages;
use App\Filament\Clusters\Website\WebsiteCluster;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Envelope;

    protected static ?string $navigationLabel = 'Contact messages';

    protected static ?string $pluralModelLabel = 'Contact messages';

    protected static ?string $modelLabel = 'Contact message';

    protected static ?string $cluster = WebsiteCluster::class;

    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->placeholder('-'),
                TextEntry::make('email')->copyable(),
                TextEntry::make('message')->columnSpanFull()->prose(),
                IconEntry::make('is_spam')->label('Spam')->boolean(),
                TextEntry::make('spam_reason')->label('Spam reason')->placeholder('-'),
                TextEntry::make('ip_address')->label('IP address')->placeholder('-'),
                TextEntry::make('created_at')->label('Received')->dateTime(),
                TextEntry::make('user_agent')->label('User agent')->placeholder('-')->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->limit(60)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_spam')
                    ->label('Spam')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('spam_reason')
                    ->label('Reason')
                    ->placeholder('-')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_spam')
                    ->label('Spam')
                    ->trueLabel('Only spam')
                    ->falseLabel('Only real messages')
                    ->placeholder('All')
                    ->default(false),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('markSpam')
                    ->label('Mark as spam')
                    ->icon('heroicon-m-no-symbol')
                    ->color('danger')
                    ->visible(fn (ContactMessage $record): bool => ! $record->is_spam)
                    ->action(fn (ContactMessage $record) => $record->update(['is_spam' => true, 'spam_reason' => 'marked manually'])),
                Action::make('markNotSpam')
                    ->label('Not spam')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(fn (ContactMessage $record): bool => $record->is_spam)
                    ->action(fn (ContactMessage $record) => $record->update(['is_spam' => false, 'spam_reason' => null])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markSpam')
                        ->label('Mark as spam')
                        ->icon('heroicon-m-no-symbol')
                        ->color('danger')
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each->update(['is_spam' => true, 'spam_reason' => 'marked manually'])),
                    BulkAction::make('markNotSpam')
                        ->label('Not spam')
                        ->icon('heroicon-m-check')
                        ->color('success')
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each->update(['is_spam' => false, 'spam_reason' => null])),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactMessages::route('/'),
        ];
    }
}
