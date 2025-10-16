<?php

namespace App\Filament\Clusters\Website\Resources;

use App\Filament\Clusters\Website\WebsiteCluster;
use App\Models\WorkExperience;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkExperienceResource extends Resource
{
    protected static ?string $model = WorkExperience::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Work Experiences';

    protected static ?string $pluralModelLabel = 'Work Experiences';

    protected static ?string $modelLabel = 'Work Experience';

    protected static ?string $cluster = WebsiteCluster::class;

    protected static ?int $navigationSort = 1;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('job_title')
                    ->label('Job title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('organisation')
                    ->label('Organisation')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('organisation_website')
                    ->label('Organisation website')
                    ->url()
                    ->maxLength(255)
                    ->nullable()
                    ->prefixIcon('heroicon-m-globe-alt'),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Start date')
                    ->required()
                    ->displayFormat('F Y')
                    ->closeOnDateSelection()
                    ->native(false),
                Forms\Components\DatePicker::make('end_date')
                    ->label('End date')
                    ->displayFormat('F Y')
                    ->closeOnDateSelection()
                    ->native(false)
                    ->helperText('Leave empty if this is your current role.')
                    ->nullable(),
                Forms\Components\MarkdownEditor::make('description')
                    ->columnSpanFull()
                    ->required()
                    ->fileAttachmentsDirectory('work-experience')
                    ->placeholder('Describe your role, responsibilities, achievements, and technologies used.'),
                Forms\Components\Hidden::make('sort_order')->default(0),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->recordAction('view')
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('job_title')
                    ->label('Job title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organisation')
                    ->label('Organisation')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organisation_website')
                    ->label('Website')
                    ->url(fn($record) => $record->organisation_website ?: null, shouldOpenInNewTab: true)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start')
                    ->date('F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End')
                    ->formatStateUsing(fn ($state) => $state ? $state->translatedFormat('F Y') : 'Present')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => $record->job_title . ' at ' . $record->organisation)
                    ->modalWidth('3xl'),
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\RestoreBulkAction::make(),
                    \Filament\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([
                \Filament\Infolists\Components\TextEntry::make('job_title')
                    ->label('Job title')
                    ->weight('bold'),
                \Filament\Infolists\Components\TextEntry::make('organisation')
                    ->label('Organisation')
                    ->url(fn ($record) => $record->organisation_website ?: null, shouldOpenInNewTab: true),
                \Filament\Infolists\Components\TextEntry::make('date_range')
                    ->label('Dates')
                    ->state(function ($record) {
                        $start = $record->start_date?->translatedFormat('F Y');
                        $end = $record->end_date?->translatedFormat('F Y') ?? 'Present';
                        return trim(($start ?: '—') . ' — ' . $end);
                    }),
                \Filament\Infolists\Components\TextEntry::make('description')
                    ->label('Description')
                    ->markdown(),
        ])->inlineLabel()->columns(1);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                \Illuminate\Database\Eloquent\SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Clusters\Website\Resources\WorkExperienceResource\Pages\ListWorkExperiences::route('/'),
            'create' => \App\Filament\Clusters\Website\Resources\WorkExperienceResource\Pages\CreateWorkExperience::route('/create'),
            'view' => \App\Filament\Clusters\Website\Resources\WorkExperienceResource\Pages\ViewWorkExperience::route('/{record}'),
            'edit' => \App\Filament\Clusters\Website\Resources\WorkExperienceResource\Pages\EditWorkExperience::route('/{record}/edit'),
        ];
    }
}
