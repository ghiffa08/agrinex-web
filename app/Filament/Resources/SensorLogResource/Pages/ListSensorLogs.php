<?php

namespace App\Filament\Resources\SensorLogResource\Pages;

use App\Filament\Resources\SensorLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSensorLogs extends ListRecords
{
    protected static string $resource = SensorLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
