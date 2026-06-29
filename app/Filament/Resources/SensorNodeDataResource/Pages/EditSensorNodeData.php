<?php

namespace App\Filament\Resources\SensorNodeDataResource\Pages;

use App\Filament\Resources\SensorNodeDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSensorNodeData extends EditRecord
{
    protected static string $resource = SensorNodeDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
