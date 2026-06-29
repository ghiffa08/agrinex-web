<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;

class ProductionJig extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Main';

    protected static ?string $navigationLabel = 'Firmware Flasher';

    protected static string $view = 'filament.pages.production-jig';

    protected static ?string $title = 'Production JIG Tool';

    protected static ?string $slug = 'production-jig';

    /**
     * Get headers actions for the custom page.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('scanPorts')
                ->label('Scan Ports')
                ->icon('heroicon-m-arrow-path')
                ->color('success')
                ->action(function () {
                    // This action triggers scanPorts on the frontend Alpine JS component.
                    $this->dispatch('trigger-scan-ports');
                }),
        ];
    }
}
