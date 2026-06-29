<?php

namespace App\Filament\Resources\SensorWeatherDataResource\Pages;

use App\Filament\Resources\SensorWeatherDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSensorWeatherData extends EditRecord
{
    protected static string $resource = SensorWeatherDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
