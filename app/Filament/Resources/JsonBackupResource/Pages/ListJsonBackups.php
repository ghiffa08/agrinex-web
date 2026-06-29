<?php

namespace App\Filament\Resources\JsonBackupResource\Pages;

use App\Filament\Resources\JsonBackupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJsonBackups extends ListRecords
{
    protected static string $resource = JsonBackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
