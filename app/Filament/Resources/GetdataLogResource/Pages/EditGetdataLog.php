<?php

namespace App\Filament\Resources\GetdataLogResource\Pages;

use App\Filament\Resources\GetdataLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGetdataLog extends EditRecord
{
    protected static string $resource = GetdataLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
