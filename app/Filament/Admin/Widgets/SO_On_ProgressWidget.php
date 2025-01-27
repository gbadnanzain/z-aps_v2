<?php

namespace App\Filament\Admin\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

use function PHPUnit\Framework\isNull;

class SO_On_ProgressWidget extends BaseWidget
{
    protected static ?string $heading = 'Sales Orders On Progress';

    protected function getTableQuery(): Builder
    {
        // Query data dari tabel Sales Orders
        return \App\Models\TransactionalData::query()
            ->whereNotNull('SO_Status')
            ->whereNotIn('SO_Status', ['COMPLETED', 'CANCELED','W/OFF']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('SO_No')
                ->label('SO No')
                ->sortable()
                ->searchable(),
            TextColumn::make('SO_Date')
                ->label('SO Date')
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y-M-d'))
                ->sortable(),
            TextColumn::make('SO_DebtorName')
                ->label('Debtor Name')
                ->searchable(),
                TextColumn::make('SO_Target_CompletionDatePerPO	')
                ->label('Debtor Name')
                ->searchable(),
        ];
    }

}
