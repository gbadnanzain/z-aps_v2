<?php

namespace App\Filament\Resources\TransactionalDataResource\Pages;

use App\Filament\Resources\TransactionalDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionalData extends ListRecords
{
    protected static string $resource = TransactionalDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
