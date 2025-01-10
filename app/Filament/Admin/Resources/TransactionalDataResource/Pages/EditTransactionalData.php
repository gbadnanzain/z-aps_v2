<?php

namespace App\Filament\Resources\TransactionalDataResource\Pages;

use App\Filament\Resources\TransactionalDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionalData extends EditRecord
{
    protected static string $resource = TransactionalDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
