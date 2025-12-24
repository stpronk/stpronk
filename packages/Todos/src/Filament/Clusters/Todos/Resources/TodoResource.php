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

    protected static ?string $cluster = TodosCluster::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('stpronk-filament-todos::todos.model.navigation_label');
    }

    public static function getLabel(): string
    {
        return __('stpronk-filament-todos::todos.model.label');
    }

    public static function getPluralLabel(): string
    {
        return __('stpronk-filament-todos::todos.model.plural_label');
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.form.fields.title.label'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('priority')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.form.fields.priority.label'))
                    ->options([
                        'low' => __('stpronk-filament-todos::todos.tabs.todos.priority.low'),
                        'medium' => __('stpronk-filament-todos::todos.tabs.todos.priority.medium'),
                        'high' => __('stpronk-filament-todos::todos.tabs.todos.priority.high'),
                        'extreme' => __('stpronk-filament-todos::todos.tabs.todos.priority.extreme'),
                    ])
                    ->default('low')
                    ->required()
                    ->native(false),
                Forms\Components\DatePicker::make('due_date')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.form.fields.due_date.label'))
                    ->placeholder(__('stpronk-filament-todos::todos.tabs.todos.form.fields.due_date.placeholder'))
                    ->native(false)
                    ->displayFormat('Y-m-d')
                    ->firstDayOfWeek(1)
                    ->closeOnDateSelection()
                    ->nullable(),
                Forms\Components\Select::make('todo_category_id')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.form.fields.category.label'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder(__('stpronk-filament-todos::todos.tabs.todos.form.fields.category.placeholder'))
                    ->native(false)
                    ->nullable()
                    ->disabled(fn($record) => ($record?->category && $record?->category?->user_id !== Auth::id()))
                    ->options(fn() => TodoCategory::query()->where('user_id', Auth::id())->pluck('name', 'id'))
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label(__('stpronk-filament-todos::category.form.fields.name.label'))
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
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.form.fields.notes.label'))
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('completed_at')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.form.fields.completed_at.label'))
                    ->visible(fn($record) => $record?->completed_at ?? false)
                    ->required()
                    ->native(false),
                Forms\Components\Textarea::make('completed_comment')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.form.fields.completed_comment.label'))
                    ->rows(4)
                    ->visible(fn($record) => $record?->completed_at ?? false)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.table.columns.title.label'))
                    ->icon(fn($record) => $record->shared_with_me ? Heroicon::Share : null)
                    ->iconColor('primary')
                    ->tooltip(fn($record) => $record->shared_with_me ? "Shared with me" : null)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.table.columns.priority.label'))
                    ->badge()
                    ->colors([
                        'gray' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'extreme',
                    ])
                    ->formatStateUsing(fn($state) => __("stpronk-filament-todos::todos.tabs.todos.priority.{$state}"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.table.columns.due_date.label'))
                    ->date()
                    ->toggleable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderByRaw('(due_date IS NULL) ASC')
                            ->orderBy('due_date', $direction);
                    }),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('stpronk-filament-todos::category.model.label'))
                    ->badge()
                    ->color(fn ($state, $record) => Color::{$record->category?->color ?? 'Amber'})
                    ->toggleable(),
                Tables\Columns\IconColumn::make('shareables')
                    ->label(__('stpronk-filament-essentials::shareables.relations.past_participle'))
                    ->getStateUsing(fn($record) => $record->isShared())
                    ->boolean()
                    ->default(false)
                    ->falseIcon(Heroicon::XMark)
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.table.columns.owner.label'))
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.table.columns.completed_at.label'))
                    ->dateTime()
                    ->since()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('todo_category_id')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.filters.todo_category.label'))
                    ->relationship('category', 'name')
                    ->native(false),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Create new Todo'),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('complete')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.actions.complete.label'))
                    ->icon(__('stpronk-filament-todos::todos.tabs.todos.actions.complete.icon'))
                    ->color('success')
                    ->visible(fn (Todo $record) => is_null($record->completed_at))
                    ->schema([
                        Forms\Components\DatePicker::make('completed_at')
                            ->label(__('stpronk-filament-todos::todos.tabs.todos.actions.complete.form.completed_at.label'))
                            ->default(now())
                            ->required(),
                        Forms\Components\Textarea::make('completed_comment')
                            ->label(__('stpronk-filament-todos::todos.tabs.todos.actions.complete.form.completed_comment.label'))
                            ->rows(3)
                            ->minLength(2)
                            ->maxLength(2000)
                            ->autofocus(),
                    ])
                    ->action(function (Todo $record, array $data) {
                        $record->update([
                            'completed_comment' => $data['completed_comment'] ?? null,
                            'completed_at' => $data['completed_at'],
                        ]);
                    })
                    ->successNotificationTitle(__('stpronk-filament-todos::todos.tabs.todos.actions.complete.success_notification.title')),
                \Filament\Actions\Action::make('reopen')
                    ->label(__('stpronk-filament-todos::todos.tabs.todos.actions.reopen.label'))
                    ->icon(__('stpronk-filament-todos::todos.tabs.todos.actions.reopen.icon'))
                    ->color('warning')
                    ->visible(fn (Todo $record) => ! is_null($record->completed_at))
                    ->requiresConfirmation()
                    ->action(function (Todo $record) {
                        $record->update([
                            'completed_at' => null,
                            // keep the comment for history; remove if undesired
                        ]);
                    })
                    ->successNotificationTitle(__('stpronk-filament-todos::todos.tabs.todos.actions.reopen.success_notification.title')),
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
        $relations = [
            ShareablesRelationshipManager::class,
        ];

        if (class_exists(\Stpronk\Purchases\Filament\RelationshipManagers\PurchasesRelationshipManager::class)) {
            $relations[] = \Stpronk\Purchases\Filament\RelationshipManagers\PurchasesRelationshipManager::class;
        }

        return $relations;
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
