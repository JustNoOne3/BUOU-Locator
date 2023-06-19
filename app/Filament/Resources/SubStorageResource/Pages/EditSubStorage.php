<?php

namespace App\Filament\Resources\SubStorageResource\Pages;

use App\Filament\Resources\SubStorageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubStorage extends EditRecord
{
    protected static string $resource = SubStorageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
