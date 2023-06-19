<?php

namespace App\Filament\Resources\SubStorageResource\Pages;

use App\Filament\Resources\SubStorageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubStorage extends CreateRecord
{
    protected static string $resource = SubStorageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
