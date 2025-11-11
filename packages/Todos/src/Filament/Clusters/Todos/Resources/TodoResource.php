<?php

namespace Stpronk\Todos\Filament\Clusters\Todos\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Stpronk\Todos\Filament\Clusters\Todos\TodosCluster;
use Stpronk\Todos\Models\Category;
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
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('No category')
                    ->native(false)
                    ->nullable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(120)
                            ->unique(table: 'todos_categories', column: 'name'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return Category::query()
                            ->firstOrCreate(['name' => $data['name']])
                            ->getKey();
                    }),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(4)
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
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->since()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
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
//            'index' => TodoResource\Pages\ListOpenTodos::route('/open'),
//            'index_completed' => TodoResource\Pages\ListCompletedTodos::route('/completed'),
            'create' => TodoResource\Pages\CreateTodo::route('/create'),
            'edit' => TodoResource\Pages\EditTodo::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $userId = auth()->id();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->whereRaw('1 = 0');
        }
        return $query;
    }
}
