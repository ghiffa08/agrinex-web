<?php

namespace App\Filament\Resources\IrrigateLogResource\Pages;

use App\Filament\Resources\IrrigateLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIrrigateLog extends EditRecord
{
    protected static string $resource = IrrigateLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
