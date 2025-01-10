<?php

namespace App\Filament\Admin\Resources\TransactionalDataResource\Pages;

use App\Filament\Admin\Resources\TransactionalDataResource;
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
