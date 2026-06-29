<?php

namespace App\Filament\Resources\NodeLogResource\Pages;

use App\Filament\Resources\NodeLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNodeLog extends EditRecord
{
    protected static string $resource = NodeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
