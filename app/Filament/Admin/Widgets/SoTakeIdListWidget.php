<?php

namespace App\Filament\Widgets;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\TransactionalData;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class SoTakeIdListWidget extends BaseWidget

{
    public function table(Table $table): Table
    {
        return $table
        ->query(TransactionalData::where('SO_Status', '=', 'TAKE ID')
        ->orderBy('SO_Date', 'desc'))

            ->columns([
                TextColumn::make('SO_No'),
                TextColumn::make('SO_Date')
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y-M-d')),
                TextColumn::make('SO_DebtorName'),
                TextColumn::make('SO_Status'),
            ]);
    }
}
