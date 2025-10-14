<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkExperienceResource\Pages;
use App\Models\WorkExperience;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WorkExperienceResource extends Resource
{
    protected static ?string $model = WorkExperience::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Work Experiences';

    protected static ?string $pluralModelLabel = 'Work Experiences';

    protected static ?string $modelLabel = 'Work Experience';

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
                    ->url(fn ($record) => $record->organisation_website ?: null, shouldOpenInNewTab: true)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start')
                    ->date('F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End')
                    ->formatStateUsing(fn ($state) => $state ? $state->translatedFormat('F Y') : 'Present')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkExperiences::route('/'),
            'create' => Pages\CreateWorkExperience::route('/create'),
            'edit' => Pages\EditWorkExperience::route('/{record}/edit'),
        ];
    }
}
