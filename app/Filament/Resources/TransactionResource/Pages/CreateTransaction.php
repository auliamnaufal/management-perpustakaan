<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function afterCreate(): void
    {
        info($this->record->book->stock);

        $this->record->book->update([
            'stock' => $this->record->book->stock - 1,
        ]);

        info($this->record->book->stock);

    }
}
