<?php

namespace App\Filament\Resources\TransactionalDataResource\Pages;

use App\Filament\Resources\TransactionalDataResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionalData extends CreateRecord
{
    protected static string $resource = TransactionalDataResource::class;
}
