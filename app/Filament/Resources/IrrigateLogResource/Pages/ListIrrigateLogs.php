<?php

namespace App\Filament\Resources\IrrigateLogResource\Pages;

use App\Filament\Resources\IrrigateLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIrrigateLogs extends ListRecords
{
    protected static string $resource = IrrigateLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
