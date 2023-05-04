<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $selectedBooks = $this->record->books;

        foreach ($selectedBooks as $book) {
            $book->update([
                'stock' => $book->stock - 1,
            ]);
        }

    }
}
