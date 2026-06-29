<?php

namespace App\Filament\Resources\SensorWeatherDataResource\Pages;

use App\Filament\Resources\SensorWeatherDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSensorWeatherData extends ListRecords
{
    protected static string $resource = SensorWeatherDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
