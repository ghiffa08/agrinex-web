<?php

namespace App\Filament\Resources\ValveLogResource\Pages;

use App\Filament\Resources\ValveLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValveLog extends EditRecord
{
    protected static string $resource = ValveLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
