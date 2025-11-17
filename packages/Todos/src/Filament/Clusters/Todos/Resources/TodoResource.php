<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Stpronk\Essentials\Filament\RelationshipManagers\ShareablesRelationshipManager;
use Stpronk\Todos\Filament\Clusters\Todos\TodosCluster;
use Stpronk\Todos\Models\TodoCategory;
use Stpronk\Todos\Models\Todo;

class TodoResource extends Resource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationLabel = 'Todos';

    protected static ?string $pluralModelLabel = 'Todos';

    protected static ?string $modelLabel = 'Todo';

    protected static ?string $cluster = TodosCluster::class;

    protected static ?int $navigationSort = 1;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('priority')
                    ->label('Priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'extreme' => 'Extreme',
                    ])
                    ->default('low')
                    ->required()
                    ->native(false),
                Forms\Components\DatePicker::make('due_date')
                    ->label('Due date')
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->firstDayOfWeek(1)
                    ->closeOnDateSelection()
                    ->nullable(),
                Forms\Components\Select::make('todo_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('No category')
                    ->native(false)
                    ->nullable()
                    ->disabled(fn($record) => ($record->category && $record->category?->user_id !== Auth::id()))
                    ->options(fn() => TodoCategory::query()->where('user_id', Auth::id())->pluck('name', 'id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(120)
                            ->rule(fn () => Rule::unique('todos_categories', 'name')->where('user_id', auth()->id())),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return TodoCategory::query()
                            ->firstOrCreate(
                                ['name' => $data['name'], 'user_id' => auth()->id()],
                                ['color' => 'primary']
                            )
                            ->getKey();
                    }),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('completed_comment')
                    ->label('Completion notes')
                    ->rows(4)
                    ->visible(fn($record) => $record->completed_at)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->icon(fn($record) => $record->shared_with_me ? Heroicon::Share : null)
                    ->iconColor('primary')
                    ->tooltip(fn($record) => $record->shared_with_me ? "Shared with me" : null)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'gray' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'extreme',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due')
                    ->date()
                    ->toggleable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderByRaw('(due_date IS NULL) ASC')
                            ->orderBy('due_date', $direction);
                    }),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color(fn ($state, $record) => Color::{$record->category?->color ?? 'Amber'})
                    ->toggleable(),
                Tables\Columns\IconColumn::make('shareables')
                    ->label('Shared')
                    ->getStateUsing(fn($record) => $record->isShared())
                    ->boolean()
                    ->default(false)
                    ->falseIcon(Heroicon::XMark)
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->since()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('todo_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->native(false),
                Tables\Filters\TernaryFilter::make('is_completed')
                    ->label('Status')
                    ->trueLabel('Completed')
                    ->falseLabel('Open')
                    ->placeholder('All')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('completed_at'),
                        false: fn ($query) => $query->whereNull('completed_at'),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Create new Todo'),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Todo $record) => is_null($record->completed_at))
                    ->schema([
                        Forms\Components\Textarea::make('completed_comment')
                            ->label('Completion comment')
                            ->rows(3)
                            ->minLength(2)
                            ->maxLength(2000),
                    ])
                    ->action(function (Todo $record, array $data) {
                        $record->update([
                            'completed_comment' => $data['completed_comment'] ?? null,
                            'completed_at' => now(),
                        ]);
                    })
                    ->successNotificationTitle('Todo completed.'),
                \Filament\Actions\Action::make('reopen')
                    ->label('Reopen')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn (Todo $record) => ! is_null($record->completed_at))
                    ->requiresConfirmation()
                    ->action(function (Todo $record) {
                        $record->update([
                            'completed_at' => null,
                            // keep the comment for history; remove if undesired
                        ]);
                    })
                    ->successNotificationTitle('Todo reopened.'),
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
            'index' => TodoResource\Pages\ListOpenTodos::route('/'),
            'create' => TodoResource\Pages\CreateTodo::route('/create'),
            'edit' => TodoResource\Pages\EditTodo::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            ShareablesRelationshipManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!Auth::id()) {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }
}
