<?php

namespace App\Filament\Resources\ShelfResource\Pages;

use App\Filament\Resources\ShelfResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageShelves extends ManageRecords
{
    protected static string $resource = ShelfResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
