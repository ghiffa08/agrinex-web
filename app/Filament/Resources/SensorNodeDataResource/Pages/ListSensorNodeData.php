<?php

namespace App\Filament\Resources\SensorNodeDataResource\Pages;

use App\Filament\Resources\SensorNodeDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSensorNodeData extends ListRecords
{
    protected static string $resource = SensorNodeDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
