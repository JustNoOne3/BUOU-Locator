<?php

namespace App\Filament\Resources\SubStorageResource\Pages;

use App\Filament\Resources\SubStorageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubStorages extends ListRecords
{
    protected static string $resource = SubStorageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Sub Storage'),
        ];
    }
}
