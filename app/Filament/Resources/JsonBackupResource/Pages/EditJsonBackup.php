<?php

namespace App\Filament\Resources\JsonBackupResource\Pages;

use App\Filament\Resources\JsonBackupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJsonBackup extends EditRecord
{
    protected static string $resource = JsonBackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
