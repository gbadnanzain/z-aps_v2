<?php

namespace App\Filament\Widgets;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\TransactionalData;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class SoTakeIdListWidget extends BaseWidget

{
    protected static ?string $heading = 'Sales Orders Take ID';

    public function table(Table $table): Table
    {
        return $table
        ->query(TransactionalData::where('SO_Status', '=', 'TAKE ID')
        ->orderBy('SO_Date', 'desc'))

            ->columns([
                TextColumn::make('SO_No')
                ->label('SO No'),
                TextColumn::make('SO_Date')
                ->label('SO Date')
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y-M-d')),
                TextColumn::make('SO_DebtorName')
                ->label('Debtor Name'),
                TextColumn::make('SO_Status')
                ->label('Status')
                ,
            ]);
    }
}
