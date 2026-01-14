<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Stpronk\UrlDissector\Models\QueryParameter;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class QueryParameterFrequency extends BaseWidget
{
    public function getTableRecordKey(Model | array $record): string
    {
        return (string) ($record instanceof Model ? $record->key : $record['key']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                QueryParameter::query()
                    ->select('key', DB::raw('count(*) as usage_count'))
                    ->groupBy('key')
            )
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Parameter Key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Usage Count')
                    ->sortable(),
            ])
            ->defaultSort('usage_count', 'desc');
    }
}
