<?php

namespace App\Filament\Resources\SensorLogResource\Pages;

use App\Filament\Resources\SensorLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSensorLog extends EditRecord
{
    protected static string $resource = SensorLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
